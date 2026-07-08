<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use BelongsToShop;
    use HasFactory;

    public const TYPES = ['in', 'out', 'adjustment'];

    protected $fillable = [
        'shop_id',
        'inventory_item_id',
        'type',
        'quantity',
        'movement_date',
        'note',
    ];

    protected static function booted(): void
    {
        static::saving(function (InventoryMovement $movement): void {
            if ($movement->inventory_item_id) {
                $movement->shop_id = InventoryItem::withoutGlobalScopes()
                    ->whereKey($movement->inventory_item_id)
                    ->value('shop_id') ?: $movement->shop_id;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'movement_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
