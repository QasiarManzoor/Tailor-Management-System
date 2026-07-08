<?php

namespace App\Support;

use App\Models\Shop;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class DefaultShopProvisioner
{
    private const DEFAULT_SHOP_CODE = 'default-tailor-shop';
    private const LEGACY_DEFAULT_SHOP_CODE = 'master-rashid';

    public static function ensureDefaultShopExists(): Shop
    {
        $defaults = SystemSetting::defaults();

        if (! Schema::hasTable('shops')) {
            return new Shop([
                'name' => $defaults['shop_name'],
                'code' => self::DEFAULT_SHOP_CODE,
                'tagline' => $defaults['shop_tagline'],
                'phone_primary' => $defaults['shop_phone_primary'],
                'phone_secondary' => $defaults['shop_phone_secondary'],
                'address_line_1' => $defaults['shop_address_line_1'],
                'address_line_2' => $defaults['shop_address_line_2'],
                'logo_path' => $defaults['logo_path'],
                'is_active' => true,
            ]);
        }

        $legacyShop = Shop::query()->where('code', self::LEGACY_DEFAULT_SHOP_CODE)->first();

        if ($legacyShop) {
            if ($legacyShop->name === 'MASTER RASHID') {
                $legacyShop->name = $defaults['shop_name'];
            }

            if ($legacyShop->tagline === null || $legacyShop->tagline === 'Digital Order Slip') {
                $legacyShop->tagline = $defaults['shop_tagline'];
            }

            if (! Shop::query()->where('code', self::DEFAULT_SHOP_CODE)->whereKeyNot($legacyShop->id)->exists()) {
                $legacyShop->code = self::DEFAULT_SHOP_CODE;
            }

            $legacyShop->save();

            return $legacyShop;
        }

        return Shop::query()->firstOrCreate(
            ['code' => self::DEFAULT_SHOP_CODE],
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
