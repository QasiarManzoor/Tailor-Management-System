<?php

namespace App\Services;

use App\Models\User;

class UserSafetyService
{
    public function canChangeRole(User $user, string $nextRole): bool
    {
        if (! $user->isSuperAdmin() || $nextRole === 'super_admin') {
            return true;
        }

        return User::where('role', 'super_admin')->count() > 1;
    }

    public function canDeactivate(User $user, ?int $actingUserId = null): bool
    {
        if ($actingUserId !== null && $user->id === $actingUserId && $user->is_active) {
            return false;
        }

        if (! $user->isSuperAdmin()) {
            return true;
        }

        return User::where('role', 'super_admin')
            ->where('is_active', true)
            ->count() > 1;
    }

    public function canDelete(User $user, ?int $actingUserId = null): bool
    {
        if ($actingUserId !== null && $user->id === $actingUserId) {
            return false;
        }

        if (! $user->isSuperAdmin()) {
            return true;
        }

        return User::where('role', 'super_admin')->count() > 1;
    }
}
