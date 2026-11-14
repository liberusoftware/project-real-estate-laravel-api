<?php

return [
    'guard' => 'web',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'home' => '/api/user',
    'prefix' => '',
    'domain' => null,
    'middleware' => ['web'],
    'limiters' => ['login' => 'login', 'two-factor' => 'two-factor'],
    'views' => false,
    'features' => [],
];
