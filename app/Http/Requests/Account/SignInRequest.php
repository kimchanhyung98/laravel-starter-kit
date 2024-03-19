<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
