<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$errors = [];
$count = 0;
$inventory = json_decode(file_get_contents($root.'/source-modules.json'), true, flags: JSON_THROW_ON_ERROR);
foreach ($inventory['retained'] as $module) {
    if (preg_match('/filament|livewire/i', $module['package'])) {
        $errors[] = 'UI package retained: '.$module['package'];
    }
    if (! is_file($root.'/'.$module['path'].'/composer.json')) {
        $errors[] = 'Missing module: '.$module['path'];
    }
}
$lock = json_decode(file_get_contents($root.'/composer.lock'), true, flags: JSON_THROW_ON_ERROR);
foreach (array_merge($lock['packages'] ?? [], $lock['packages-dev'] ?? []) as $package) {
    if (preg_match('/filament|livewire/i', $package['name'])) {
        $errors[] = 'Resolved UI dependency: '.$package['name'];
    }
}
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)) as $file) {
    $path = $file->getPathname();
    if (preg_match('~/vendor/|/\.git/~', $path)) {
        continue;
    }
    if ($file->getFilename() === 'composer.json') {
        $manifest = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        foreach (array_keys($manifest['require'] ?? []) as $package) {
            if (preg_match('/filament|livewire/i', $package) || $package === 'laravel/pulse') {
                $errors[] = 'UI dependency in '.$path.': '.$package;
            }
        }
    }
    if ($file->getExtension() === 'php') {
        $count++;
        try {
            token_get_all(file_get_contents($path), TOKEN_PARSE);
        } catch (ParseError $error) {
            $errors[] = $path.': '.$error->getMessage();
        }
    }
}
foreach ($errors as $error) {
    fwrite(STDERR, $error.PHP_EOL);
}
echo 'Checked '.$count.' PHP files and the domain/API package boundary.'.PHP_EOL;
exit($errors === [] ? 0 : 1);
