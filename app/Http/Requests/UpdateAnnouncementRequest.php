<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'type' => ['nullable', 'in:general,maintenance,event,urgent'],
            'published_at' => ['nullable', 'datetime'],
            'expires_at' => ['nullable', 'datetime'],
        ];
    }
}