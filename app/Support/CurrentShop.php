<?php

namespace App\Support;

use App\Models\Shop;

class CurrentShop
{
    public static function scopeShopId(): ?int
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return session('superadmin_shop_context_id') ?: null;
        }

        return $user->shop_id ?: null;
    }

    public static function shouldRestrictToEmptySet(): bool
    {
        $user = auth()->user();

        return (bool) $user
            && (! method_exists($user, 'isSuperAdmin') || ! $user->isSuperAdmin())
            && blank($user->shop_id);
    }

    public static function creationShopId(): ?int
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return session('superadmin_shop_context_id') ?: null;
        }

        return $user->shop_id ?: null;
    }

    public static function contextShop(): ?Shop
    {
        $shopId = self::scopeShopId();

        return $shopId ? Shop::query()->find($shopId) : null;
    }

    public static function isScopedForSuperAdmin(): bool
    {
        $user = auth()->user();

        return (bool) $user
            && method_exists($user, 'isSuperAdmin')
            && $user->isSuperAdmin()
            && filled(session('superadmin_shop_context_id'));
    }
}
