<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ! $this->user()->isBanned();
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ];
    }
}
