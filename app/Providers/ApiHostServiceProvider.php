<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

final class ApiHostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (class_exists(Jetstream::class)) {
            Jetstream::ignoreRoutes();
        }
    }
}
