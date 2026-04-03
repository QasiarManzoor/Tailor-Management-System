<?php

namespace App\Http\Requests;

use App\Models\Measurement;
use App\Models\Order;
use App\Support\CurrentShop;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerExists = Rule::exists('customers', 'id');
        $measurementExists = Rule::exists('measurements', 'id');

        if ($shopId = CurrentShop::scopeShopId()) {
            $customerExists = $customerExists->where(fn ($query) => $query->where('shop_id', $shopId));
            $measurementExists = $measurementExists->where(fn ($query) => $query->where('shop_id', $shopId));
        }

        return [
            'customer_id' => ['required', $customerExists],
            'measurement_id' => ['nullable', $measurementExists],
            'order_type' => ['required', 'string', 'max:255'],
            'fabric_details' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total_amount' => ['required', 'integer', 'min:0'],
            'advance_amount' => ['required', 'integer', 'min:0', 'lte:total_amount'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'trial_date' => ['nullable', 'date', 'after_or_equal:booking_date'],
            'delivery_date' => ['required', 'date', 'after_or_equal:booking_date'],
            'delivered_date' => ['nullable', 'date', 'after_or_equal:booking_date'],
            'status' => ['required', Rule::in(Order::STATUSES)],
            'priority' => ['required', Rule::in(Order::PRIORITIES)],
            'special_instructions' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'measurement_id' => 'saved measurement',
            'order_type' => 'order type',
            'fabric_details' => 'fabric details',
            'total_amount' => 'total amount',
            'advance_amount' => 'advance received',
            'booking_date' => 'booking date',
            'trial_date' => 'trial date',
            'delivery_date' => 'delivery date',
            'delivered_date' => 'delivered date',
            'special_instructions' => 'special instructions',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $customerId = $this->input('customer_id');
            $measurementId = $this->input('measurement_id');

            if ($customerId && $measurementId) {
                $exists = Measurement::query()
                    ->whereKey($measurementId)
                    ->where('customer_id', $customerId)
                    ->exists();

                if (! $exists) {
                    $validator->errors()->add('measurement_id', 'The selected saved measurement does not belong to the chosen customer.');
                }
            }
        });
    }
}
