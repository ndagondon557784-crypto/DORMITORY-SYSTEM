<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', 'in:general,maintenance,event,urgent'],
            'published_at' => ['nullable', 'datetime'],
            'expires_at' => ['nullable', 'datetime', 'after:published_at'],
        ];
    }
}