<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
