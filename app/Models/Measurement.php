<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Measurement extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'customer_id',
        'title',
        'kameez_length',
        'chest',
        'waist',
        'hip',
        'shoulder',
        'sleeve',
        'collar',
        'arm_hole',
        'shalwar_length',
        'thigh',
        'knee',
        'bottom_width',
        'cuff',
        'front_style',
        'collar_style',
        'pocket_style',
        'trouser_style',
        'special_notes',
    ];

    protected static function booted(): void
    {
        static::saving(function (Measurement $measurement): void {
            if ($measurement->customer_id) {
                $measurement->shop_id = Customer::withoutGlobalScopes()
                    ->whereKey($measurement->customer_id)
                    ->value('shop_id') ?: $measurement->shop_id;
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
