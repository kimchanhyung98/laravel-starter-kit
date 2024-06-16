<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'nickname' => ['required', 'string', 'max:50', 'unique:users,nickname'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->max(100)->mixedCase()->symbols()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
