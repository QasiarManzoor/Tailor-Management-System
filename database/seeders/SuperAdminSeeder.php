<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\DefaultShopProvisioner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $defaultShop = DefaultShopProvisioner::ensureDefaultShopExists();

        User::updateOrCreate(
            ['email' => 'admin@shaqtechnologies.com'],
            [
                'shop_id' => $defaultShop->id,
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@shop.com'],
            [
                'shop_id' => $defaultShop->id,
                'name' => 'Shop Owner',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'is_active' => true,
            ]
        );
    }
}
