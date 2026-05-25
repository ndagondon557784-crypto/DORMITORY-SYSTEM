<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'  => 'required|string|unique:students,student_id',
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email',
            'phone'       => 'nullable|string|max:20',
            'gender'      => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'course'      => 'nullable|string|max:100',
            'year_level'  => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'      => 'required|in:active,inactive,graduated',
        ];
    }
}