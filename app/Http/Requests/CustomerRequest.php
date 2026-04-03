<?php

namespace App\Http\Requests;

use App\Support\CurrentShop;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\+?[0-9]{7,20}$/'],
            'alternate_phone' => ['nullable', 'regex:/^\+?[0-9]{7,20}$/'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'notes' => ['nullable', 'string'],
        ];

        if (auth()->user()?->isSuperAdmin()) {
            $rules['shop_id'] = ['required', Rule::exists('shops', 'id')];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'shop_id' => 'shop',
            'name' => 'customer name',
            'phone' => 'phone number',
            'alternate_phone' => 'alternate phone',
        ];
    }
}
