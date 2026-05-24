<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'exists:students,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'check_in_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}