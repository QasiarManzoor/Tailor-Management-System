<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use BelongsToShop;
    use HasFactory;

    public const CATEGORIES = ['fabric', 'accessories', 'buttons', 'thread', 'lining', 'miscellaneous'];
    public const UNITS = ['meter', 'yard', 'piece', 'roll', 'pack'];

    protected $fillable = [
        'shop_id',
        'name',
        'sku',
        'category',
        'unit',
        'stock_quantity',
        'reorder_level',
        'cost_price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'reorder_level' => 'integer',
            'cost_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest('movement_date')->latest();
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }
}
