<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbookEntry extends Model
{
    use BelongsToShop;
    use HasFactory;

    public const TYPES = ['income', 'expense'];

    public const EXPENSE_CATEGORIES = [
        'fabric',
        'accessories',
        'worker_payment',
        'rent',
        'electricity',
        'miscellaneous',
    ];

    protected $fillable = [
        'shop_id',
        'entry_date',
        'type',
        'category',
        'amount',
        'payment_method',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }
}
