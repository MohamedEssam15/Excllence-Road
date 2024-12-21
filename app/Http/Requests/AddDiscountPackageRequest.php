<?php

namespace App\Http\Requests;

use App\Enum\DiscountTypes;
use Illuminate\Foundation\Http\FormRequest;

class AddDiscountPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->discountType == DiscountTypes::PERCENTAGE) {
            return [
                'packageId' => 'required|exists:packages,id',
                'discount' => 'required|numeric|min:0|max:100',
            ];
        }
        return [
            'packageId' => 'required|exists:packages,id',
            'discountType' => 'required|in:' . DiscountTypes::PERCENTAGE . ',' . DiscountTypes::FIXED,
            'discount' => 'required|numeric|min:0',
        ];
    }
}
