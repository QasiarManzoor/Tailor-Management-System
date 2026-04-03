<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'alternate_phone',
        'address',
        'gender',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (blank($customer->customer_no)) {
                $customer->customer_no = static::generateCustomerNumber();
            }
        });
    }

    public static function generateCustomerNumber(): string
    {
        return DB::transaction(function () {
            $prefix = '2026';

            $lastCustomerNo = static::query()
                ->select('customer_no')
                ->where('customer_no', 'like', $prefix.'%')
                ->whereNotNull('customer_no')
                ->lockForUpdate()
                ->orderByDesc('customer_no')
                ->value('customer_no');

            $nextSequence = $lastCustomerNo
                ? ((int) substr($lastCustomerNo, strlen($prefix))) + 1
                : 1;

            do {
                $candidate = sprintf('%s%05d', $prefix, $nextSequence);
                $nextSequence++;
            } while (static::withoutGlobalScope('shop')->where('customer_no', $candidate)->exists());

            return $candidate;
        }, 3);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class)->latest();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest('booking_date')->latest();
    }
}
