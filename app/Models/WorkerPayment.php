<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerPayment extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'worker_id',
        'order_id',
        'payment_date',
        'amount',
        'payment_method',
        'note',
    ];

    protected static function booted(): void
    {
        static::saving(function (WorkerPayment $payment): void {
            if ($payment->worker_id) {
                $payment->shop_id = Worker::withoutGlobalScopes()
                    ->whereKey($payment->worker_id)
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

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
