<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use BelongsToShop;
    use HasFactory;

    public const METHODS = [
        'cash',
        'bank_transfer',
        'easypaisa',
        'jazzcash',
        'card',
    ];

    protected $fillable = [
        'shop_id',
        'order_id',
        'amount',
        'payment_method',
        'payment_date',
        'note',
    ];

    protected static function booted(): void
    {
        static::saving(function (Payment $payment): void {
            if ($payment->order_id) {
                $payment->shop_id = Order::withoutGlobalScopes()
                    ->whereKey($payment->order_id)
                    ->value('shop_id') ?: $payment->shop_id;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
