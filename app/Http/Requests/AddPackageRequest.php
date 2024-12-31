<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPackageRequest extends FormRequest
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

        return [
            'enName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value && ! request()->filled('arName')) {
                    if (request()->filled('arDescription')) {
                        $fail(__('response.oneLangEn'));
                    }
                }
            }],
            'arName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value && ! request()->filled('enName')) {
                    if (request()->filled('enDescription')) {
                        $fail(__('response.oneLangAr'));
                    }
                }
            }],
            'enDescription' => ['string', 'nullable'],
            'arDescription' => ['string', 'nullable'],
            'price' => 'required|numeric',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after:startDate',
            'addToPopularPackages' => "nullable",
            'courses' => ['required', 'array'],
            'courses.*' => ['required', 'distinct', 'exists:courses,id'],
            'coverPhoto' => ['required', 'image']
        ];
    }
}
