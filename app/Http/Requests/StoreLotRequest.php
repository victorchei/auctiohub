<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ! $this->user()->isBanned();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20|max:5000',
            'starting_price' => 'required|numeric|min:1|max:1000000',
            'bid_increment' => 'required|numeric|min:1|max:10000',
            'starts_at' => 'required|date|after_or_equal:now',
            'ends_at' => 'required|date|after:starts_at',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|max:5120',
        ];
    }
}
