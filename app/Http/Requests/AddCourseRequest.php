<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddCourseRequest extends FormRequest
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
                    if (request()->filled('arDescription') || request()->filled('arSpecificTo')) {
                        $fail(__('response.oneLangEn'));
                    }
                }
            }],
            'arName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value && ! request()->filled('enName')) {
                    if (request()->filled('enDescription') || request()->filled('enSpecificTo')) {
                        $fail(__('response.oneLangAr'));
                    }
                }
            }],
            'enDescription' => ['string', 'nullable'],
            'arDescription' => ['string', 'nullable'],
            'categoryId' => ['required', 'exists:categories,id'],
            'levelId' => ['required', 'exists:course_levels,id'],
            'startDate' => ['date', 'required', 'after:' . now()->format('Y-m-d')],
            'endDate' => ['date', 'nullable', 'after:startDate'],
            'enSpecificTo' => ['string', 'nullable'],
            'arSpecificTo' => ['string', 'nullable'],
            'coverPhoto' => ['image', 'required', 'mimes:png,jpg,jpeg'],
            'courseTrailer' => ['nullable', 'file', 'mimes:mp4', 'max:524288'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
