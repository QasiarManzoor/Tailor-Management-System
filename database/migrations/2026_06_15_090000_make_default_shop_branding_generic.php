<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('system_settings')
            ->where('shop_name', 'MASTER RASHID')
            ->update([
                'shop_name' => 'XYZ Tailor Shop',
                'shop_phone_primary' => null,
                'shop_phone_secondary' => null,
                'shop_address_line_1' => null,
                'shop_address_line_2' => null,
                'logo_path' => 'images/shaq-logo-web-safe.png',
                'updated_at' => now(),
            ]);

        $defaultCodeExists = DB::table('shops')->where('code', 'default-tailor-shop')->exists();

        $shopUpdates = [
            'name' => 'XYZ Tailor Shop',
            'phone_primary' => null,
            'phone_secondary' => null,
            'address_line_1' => null,
            'address_line_2' => null,
            'logo_path' => 'images/shaq-logo-web-safe.png',
            'updated_at' => now(),
        ];

        if (! $defaultCodeExists) {
            $shopUpdates['code'] = 'default-tailor-shop';
        }

        DB::table('shops')
            ->where('code', 'master-rashid')
            ->where('name', 'MASTER RASHID')
            ->update($shopUpdates);

        Cache::forget('system_settings.current');
        Cache::forget('bootstrap.default-shop');
    }

    public function down(): void
    {
        DB::table('system_settings')
            ->where('shop_name', 'XYZ Tailor Shop')
            ->update([
                'shop_name' => 'MASTER RASHID',
                'shop_phone_primary' => '0313-5271056',
                'shop_phone_secondary' => '057-6108185',
                'shop_address_line_1' => 'Shop # 4, Faizan Plaza, Upside Mezan Bank Basement',
                'shop_address_line_2' => 'Near Soneri Bank Main PWD Islamabad',
                'logo_path' => 'images/shaq-logo.png',
                'updated_at' => now(),
            ]);

        DB::table('shops')
            ->where('code', 'default-tailor-shop')
            ->where('name', 'XYZ Tailor Shop')
            ->update([
                'name' => 'MASTER RASHID',
                'code' => 'master-rashid',
                'phone_primary' => '0313-5271056',
                'phone_secondary' => '057-6108185',
                'address_line_1' => 'Shop # 4, Faizan Plaza, Upside Mezan Bank Basement',
                'address_line_2' => 'Near Soneri Bank Main PWD Islamabad',
                'logo_path' => 'images/shaq-logo.png',
                'updated_at' => now(),
            ]);

        Cache::forget('system_settings.current');
        Cache::forget('bootstrap.default-shop');
    }
};
