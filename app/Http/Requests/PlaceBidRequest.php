<?php

namespace App\Http\Requests;

use App\Models\Lot;
use Illuminate\Foundation\Http\FormRequest;

class PlaceBidRequest extends FormRequest
{
    public function authorize(): bool
    {
        $lot = $this->route('lot');
        return $this->user()?->can('create', [\App\Models\Bid::class, $lot]) ?? false;
    }

    public function rules(): array
    {
        /** @var Lot $lot */
        $lot = $this->route('lot');
        $min = $lot->minNextBid();

        return [
            'amount' => "required|numeric|min:{$min}",
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Ставка має бути не меншою ніж :min ₴ (поточна + крок).',
        ];
    }
}
