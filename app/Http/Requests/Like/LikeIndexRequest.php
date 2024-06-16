<?php

namespace App\Http\Requests\Like;

use Illuminate\Foundation\Http\FormRequest;

class LikeIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
