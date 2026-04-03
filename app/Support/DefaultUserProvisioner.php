<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DefaultUserProvisioner
{
    public static function ensureSuperAdminExists(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasColumns('users', ['name', 'email', 'password', 'role'])) {
            return;
        }

        if (User::query()->where('role', 'super_admin')->exists()) {
            return;
        }

        $defaultShop = DefaultShopProvisioner::ensureDefaultShopExists();

        $attributes = [
            'shop_id' => $defaultShop->id,
            'name' => 'Super Admin',
            'email' => 'admin@shaqtechnologies.com',
            'password' => 'password',
            'role' => 'super_admin',
        ];

        if (Schema::hasColumn('users', 'is_active')) {
            $attributes['is_active'] = true;
        }

        User::query()->create($attributes);
    }
}
