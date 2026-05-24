<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')->id;

        return [
            'building_id' => ['nullable', 'exists:buildings,id'],
            'room_number' => ['nullable', 'string', "unique:rooms,room_number,{$roomId}"],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0'],
            'type' => ['nullable', 'in:single,double,triple'],
            'status' => ['nullable', 'in:available,occupied,maintenance'],
            'amenities' => ['nullable', 'string'],
            'floor' => ['nullable', 'string'],
        ];
    }
}