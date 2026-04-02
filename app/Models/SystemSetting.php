<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    private const CACHE_KEY = 'system_settings.current';

    protected $fillable = [
        'shop_name',
        'shop_tagline',
        'shop_phone_primary',
        'shop_phone_secondary',
        'shop_address_line_1',
        'shop_address_line_2',
        'receipt_footer_company_name',
        'receipt_footer_phone',
        'receipt_footer_email',
        'logo_path',
    ];

    public static function defaults(): array
    {
        return [
            'shop_name' => 'MASTER RASHID',
            'shop_tagline' => 'Digital Order Slip',
            'shop_phone_primary' => '0313-5271056',
            'shop_phone_secondary' => '057-6108185',
            'shop_address_line_1' => 'Shop # 4, Faizan Plaza, Upside Mezan Bank Basement',
            'shop_address_line_2' => 'Near Soneri Bank Main PWD Islamabad',
            'receipt_footer_company_name' => 'ShaQ Technologies',
            'receipt_footer_phone' => '+923028913283',
            'receipt_footer_email' => 'contact@shaqtechnologies.com',
            'logo_path' => 'images/shaq-logo.png',
        ];
    }

    public static function current(): self
    {
        $attributes = Cache::rememberForever(self::CACHE_KEY, function (): array {
            return static::query()->firstOrCreate([], static::defaults())->attributesToArray();
        });

        $settings = new static();
        $settings->exists = true;
        $settings->setRawAttributes($attributes, true);

        return $settings;
    }

    public function refreshCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
