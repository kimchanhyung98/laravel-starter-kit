<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'deleted_reason' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
