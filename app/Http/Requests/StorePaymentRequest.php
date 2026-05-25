<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'allocation_id'  => 'required|exists:allocations,id',
            'amount'         => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'period_from'    => 'required|date',
            'period_to'      => 'required|date|after:period_from',
            'payment_method' => 'required|in:cash,gcash,bank_transfer,check',
            'notes'          => 'nullable|string|max:500',
        ];
    }
}