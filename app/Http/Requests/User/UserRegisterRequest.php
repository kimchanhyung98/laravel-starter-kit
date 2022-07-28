<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserRegisterRequest extends FormRequest
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
        'name' => 'string',
        'password' => 'string',
    ])]
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:50',
                'unique:users',
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:20',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'max:50',
            ],
        ];
    }
}
