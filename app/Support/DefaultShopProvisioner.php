<?php

namespace App\Support;

use App\Models\Shop;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DefaultShopProvisioner
{
    public static function ensureDefaultShopExists(): Shop
    {
        $defaults = SystemSetting::defaults();

        if (! Schema::hasTable('shops')) {
            return new Shop([
                'name' => $defaults['shop_name'],
                'code' => 'master-rashid',
                'tagline' => $defaults['shop_tagline'],
                'phone_primary' => $defaults['shop_phone_primary'],
                'phone_secondary' => $defaults['shop_phone_secondary'],
                'address_line_1' => $defaults['shop_address_line_1'],
                'address_line_2' => $defaults['shop_address_line_2'],
                'logo_path' => $defaults['logo_path'],
                'is_active' => true,
            ]);
        }

        return Shop::query()->firstOrCreate(
            ['code' => 'master-rashid'],
            [
                'name' => $defaults['shop_name'],
                'tagline' => $defaults['shop_tagline'],
                'phone_primary' => $defaults['shop_phone_primary'],
                'phone_secondary' => $defaults['shop_phone_secondary'],
                'address_line_1' => $defaults['shop_address_line_1'],
                'address_line_2' => $defaults['shop_address_line_2'],
                'logo_path' => $defaults['logo_path'],
                'is_active' => true,
            ]
        );
    }
}
