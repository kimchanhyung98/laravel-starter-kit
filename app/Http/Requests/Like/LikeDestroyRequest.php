<?php

namespace App\Http\Requests\Like;

use Illuminate\Foundation\Http\FormRequest;

class LikeDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'like_id' => ['required', 'integer'],
            'like_type' => ['required', 'string', 'in:post,comment'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
