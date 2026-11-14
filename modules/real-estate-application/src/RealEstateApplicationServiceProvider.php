<?php

namespace Liberu\RealEstate\Application;

use Illuminate\Support\ServiceProvider;
use Liberu\Foundation\UsersRoles\DatabaseServiceProvider;

final class RealEstateApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DatabaseServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
