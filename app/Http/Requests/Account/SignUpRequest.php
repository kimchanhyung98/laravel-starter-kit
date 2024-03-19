<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email', 'unique:users,email', 'max:100'],
            'name'     => ['required', 'string', 'max:50'],
            'nickname' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
