<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'email' => 'email',
        'password' => 'string',
    ])]
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
            ],
            'password' => [
                'required',
                'string',
                'min:4',
                'max:50',
            ],
        ];
    }
}
