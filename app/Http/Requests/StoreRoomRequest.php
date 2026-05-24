<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id' => ['required', 'exists:buildings,id'],
            'room_number' => ['required', 'string', 'unique:rooms,room_number'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:single,double,triple'],
            'amenities' => ['nullable', 'string'],
            'floor' => ['nullable', 'string'],
        ];
    }
}