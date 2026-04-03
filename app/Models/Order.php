<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use BelongsToShop;
    use HasFactory;

    public const STATUSES = [
        'booked',
        'cutting',
        'stitching',
        'trial',
        'ready',
        'delivered',
        'cancelled',
    ];

    public const PRIORITIES = [
        'normal',
        'urgent',
    ];

    public const STATUS_BADGES = [
        'booked' => 'bg-primary-subtle text-primary-emphasis',
        'cutting' => 'bg-warning-subtle text-warning-emphasis',
        'stitching' => 'bg-info-subtle text-info-emphasis',
        'trial' => 'bg-secondary-subtle text-secondary-emphasis',
        'ready' => 'bg-success-subtle text-success-emphasis',
        'delivered' => 'bg-dark-subtle text-dark-emphasis',
        'cancelled' => 'bg-danger-subtle text-danger-emphasis',
    ];

    protected $fillable = [
        'shop_id',
        'order_no',
        'customer_id',
        'measurement_id',
        'order_type',
        'fabric_details',
        'quantity',
        'total_amount',
        'advance_amount',
        'balance_amount',
        'booking_date',
        'trial_date',
        'delivery_date',
        'delivered_date',
        'status',
        'priority',
        'special_instructions',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (blank($order->order_no)) {
                $order->order_no = static::generateOrderNumber(
                    $order->booking_date ? Carbon::parse($order->booking_date) : now()
                );
            }

            $order->shop_id = static::resolveShopId($order);
            $order->balance_amount = static::calculateBalance($order->total_amount, $order->advance_amount);
        });

        static::updating(function (Order $order) {
            $order->shop_id = static::resolveShopId($order);
            $order->balance_amount = static::calculateBalance($order->total_amount, $order->advance_amount);
        });
    }

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'trial_date' => 'date',
            'delivery_date' => 'date',
            'delivered_date' => 'date',
            'total_amount' => 'decimal:2',
            'advance_amount' => 'decimal:2',
            'balance_amount' => 'decimal:2',
        ];
    }

    public static function calculateBalance(float|int|string|null $totalAmount, float|int|string|null $advanceAmount): float
    {
        return max(0, (float) $totalAmount - (float) $advanceAmount);
    }

    public static function generateOrderNumber(Carbon $date): string
    {
        return DB::transaction(function () {
            $prefix = 'ORD-2026';

            $lastOrderNo = static::query()
                ->select('order_no')
                ->where('order_no', 'like', $prefix.'-%')
                ->lockForUpdate()
                ->orderByDesc('order_no')
                ->value('order_no');

            $lastSequence = 0;

            if (filled($lastOrderNo)) {
                $lastOrderNo = (string) $lastOrderNo;
                $lastSeparatorPosition = strrpos($lastOrderNo, '-');

                if ($lastSeparatorPosition !== false) {
                    $lastSequence = (int) substr($lastOrderNo, $lastSeparatorPosition + 1);
                }
            }

            $nextSequence = $lastSequence + 1;

            do {
                $candidate = sprintf('%s-%04d', $prefix, $nextSequence);
                $nextSequence++;
            } while (static::withoutGlobalScope('shop')->where('order_no', $candidate)->exists());

            return $candidate;
        }, 3);
    }

    protected static function resolveShopId(Order $order): ?int
    {
        if ($order->customer_id) {
            return Customer::withoutGlobalScopes()->whereKey($order->customer_id)->value('shop_id') ?: $order->shop_id;
        }

        if ($order->measurement_id) {
            return Measurement::withoutGlobalScopes()->whereKey($order->measurement_id)->value('shop_id') ?: $order->shop_id;
        }

        return $order->shop_id;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest('payment_date')->latest();
    }

    public function refreshBalance(): void
    {
        $this->balance_amount = static::calculateBalance($this->total_amount, $this->advance_amount);
        $this->save();
    }

    public function statusBadgeClass(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'bg-light text-dark';
    }

    public function isOverdue(): bool
    {
        return $this->delivery_date
            && $this->delivery_date->isPast()
            && ! in_array($this->status, ['delivered', 'cancelled'], true);
    }
}
