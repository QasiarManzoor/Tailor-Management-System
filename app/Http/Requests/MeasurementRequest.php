<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $decimalFields = [
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
        ];

        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'front_style' => ['nullable', 'string', 'max:255'],
            'collar_style' => ['nullable', 'string', 'max:255'],
            'pocket_style' => ['nullable', 'string', 'max:255'],
            'trouser_style' => ['nullable', 'string', 'max:255'],
            'special_notes' => ['nullable', 'string'],
        ];

        foreach ($decimalFields as $field) {
            $rules[$field] = ['nullable', 'numeric', 'decimal:0,2', 'min:0'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'kameez_length' => 'kameez length',
            'arm_hole' => 'arm hole',
            'shalwar_length' => 'shalwar length',
            'bottom_width' => 'bottom width',
            'special_notes' => 'special notes',
        ];
    }
}
