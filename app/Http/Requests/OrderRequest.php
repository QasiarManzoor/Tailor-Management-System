<?php

namespace App\Http\Requests;

use App\Models\Measurement;
use App\Models\Order;
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
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'measurement_id' => ['nullable', 'exists:measurements,id'],
            'order_type' => ['required', 'string', 'max:255'],
            'fabric_details' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'advance_amount' => ['required', 'numeric', 'min:0', 'lte:total_amount'],
            'booking_date' => ['required', 'date'],
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
                $exists = Measurement::whereKey($measurementId)
                    ->where('customer_id', $customerId)
                    ->exists();

                if (! $exists) {
                    $validator->errors()->add('measurement_id', 'The selected saved measurement does not belong to the chosen customer.');
                }
            }
        });
    }
}
