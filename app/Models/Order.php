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

        static::created(function (Order $order) {
            $order->statusHistory()->create([
                'changed_by' => auth()->id(),
                'from_status' => null,
                'to_status' => $order->status,
            ]);
        });

        static::updating(function (Order $order) {
            $order->shop_id = static::resolveShopId($order);
            $order->balance_amount = static::calculateBalance($order->total_amount, $order->advance_amount);
        });

        static::updated(function (Order $order) {
            if (! $order->wasChanged('status')) {
                return;
            }

            $order->statusHistory()->create([
                'changed_by' => auth()->id(),
                'from_status' => $order->getOriginal('status'),
                'to_status' => $order->status,
            ]);
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

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->oldest();
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

    public function whatsappReceiptUrl(): ?string
    {
        return $this->whatsappUrl(sprintf(
            "Assalam o Alaikum %s, your order %s has been booked. Total: Rs. %s, advance: Rs. %s, balance: Rs. %s. Delivery date: %s.",
            $this->customer?->name,
            $this->order_no,
            number_format((float) $this->total_amount, 0),
            number_format((float) $this->advance_amount, 0),
            number_format((float) $this->balance_amount, 0),
            $this->delivery_date?->format('d M Y') ?: 'not set'
        ));
    }

    public function whatsappDeliveryReminderUrl(): ?string
    {
        return $this->whatsappUrl(sprintf(
            "Assalam o Alaikum %s, reminder for order %s. Delivery date is %s and current status is %s.",
            $this->customer?->name,
            $this->order_no,
            $this->delivery_date?->format('d M Y') ?: 'not set',
            str_replace('_', ' ', $this->status)
        ));
    }

    public function whatsappPaymentReminderUrl(): ?string
    {
        if ((float) $this->balance_amount <= 0) {
            return null;
        }

        return $this->whatsappUrl(sprintf(
            "Assalam o Alaikum %s, payment reminder for order %s. Remaining balance is Rs. %s.",
            $this->customer?->name,
            $this->order_no,
            number_format((float) $this->balance_amount, 0)
        ));
    }

    protected function whatsappUrl(string $message): ?string
    {
        $phone = static::normalizeWhatsappPhone($this->customer?->phone);

        if (! $phone) {
            return null;
        }

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    protected static function normalizeWhatsappPhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            return '92'.substr($digits, 1);
        }

        return $digits;
    }
}
