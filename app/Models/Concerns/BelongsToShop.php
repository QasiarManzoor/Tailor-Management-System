<?php

namespace App\Models\Concerns;

use App\Models\Shop;
use App\Support\CurrentShop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToShop
{
    public static function bootBelongsToShop(): void
    {
        static::addGlobalScope('shop', function (Builder $builder): void {
            $shopId = CurrentShop::scopeShopId();

            if ($shopId !== null) {
                $builder->where($builder->qualifyColumn('shop_id'), $shopId);

                return;
            }

            if (CurrentShop::shouldRestrictToEmptySet()) {
                $builder->whereRaw('1 = 0');
            }
        });

        static::creating(function ($model): void {
            if (blank($model->shop_id) && CurrentShop::creationShopId() !== null) {
                $model->shop_id = CurrentShop::creationShopId();
            }
        });
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeForShop(Builder $query, Shop|int $shop): Builder
    {
        $shopId = $shop instanceof Shop ? $shop->id : $shop;

        return $query->withoutGlobalScope('shop')->where($query->qualifyColumn('shop_id'), $shopId);
    }
}
