<?php

namespace Liberu\Foundation\Tenancy;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class TenantContext
{
    public static function set(?int $teamId): void
    {
        app(TenantState::class)->teamId = $teamId;
    }

    public static function clear(): void
    {
        app(TenantState::class)->teamId = null;
    }

    public static function forUser(?Authenticatable $user): ?int
    {
        if (! $user instanceof Model || ! method_exists($user, 'currentTeam')) {
            return null;
        }

        $team = $user->getRelationValue('currentTeam');

        return $team instanceof Model ? (int) $team->getKey() : null;
    }


    public static function currentId(): ?int
    {
        return app(TenantState::class)->teamId;
    }
}
