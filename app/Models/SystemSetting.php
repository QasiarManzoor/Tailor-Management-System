<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
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
            'shop_name' => 'XYZ Tailor Shop',
            'shop_tagline' => 'Digital Order Slip',
            'shop_phone_primary' => null,
            'shop_phone_secondary' => null,
            'shop_address_line_1' => null,
            'shop_address_line_2' => null,
            'receipt_footer_company_name' => 'ShaQ Technologies',
            'receipt_footer_phone' => '+923028913283',
            'receipt_footer_email' => 'contact@shaqtechnologies.com',
            'logo_path' => 'images/shaq-logo-web-safe.png',
        ];
    }

    public static function current(): self
    {
        try {
            $attributes = Cache::rememberForever(self::CACHE_KEY, function (): array {
                return static::query()->firstOrCreate([], static::defaults())->attributesToArray();
            });
        } catch (QueryException) {
            $attributes = static::defaults();
        }

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
