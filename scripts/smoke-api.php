<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require dirname(__DIR__).'/vendor/autoload.php';
putenv('APP_ENV=testing');
putenv('APP_KEY=base64:'.base64_encode(str_repeat('x', 32)));
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=array');
putenv('TELESCOPE_ENABLED=false');
$app = require dirname(__DIR__).'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
foreach (['/up' => 200, '/api/user' => 401] as $uri => $status) {
    $request = Request::create($uri, 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
    $response = $kernel->handle($request);
    if ($response->getStatusCode() !== $status) {
        fwrite(STDERR, $uri.' expected '.$status.'; got '.$response->getStatusCode().PHP_EOL.$response->getContent().PHP_EOL);
        exit(1);
    }
    $kernel->terminate($request, $response);
}
echo 'API health and unauthenticated access checks passed.'.PHP_EOL;
