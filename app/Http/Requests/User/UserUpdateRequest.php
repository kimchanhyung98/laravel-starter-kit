<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'name' => ['required', 'string', 'min:2', 'max:50'],
            'nickname' => ['required', 'string', 'min:2', 'max:50'],
            // 'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::id())],
            'password' => ['nullable', 'string', Password::min(8)->max(100)->mixedCase()->symbols()],
            // 'current_password' => ['required', 'string', Password::min(8)->max(100)->mixedCase()->symbols()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
