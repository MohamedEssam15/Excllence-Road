<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $course = $this->route('course');
        if ($course->teacher_id == auth()->id()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ((isset(request()->enSpecificTo) && ! is_null(request()->enSpecificTo)) || (isset(request()->arSpecificTo) && ! is_null(request()->arSpecificTo))) {
            request()->merge(['isSpecific' => true]);
        } else {
            request()->merge(['isSpecific' => false]);
        }
        return [
            'enName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value || ! request()->filled('arName')) {
                    if (request()->filled('arDescription') || request()->filled('arSpecificTo')) {
                        $fail(__('response.oneLangEn'));
                    }
                }
            }],
            'arName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value || ! request()->filled('enName')) {
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
            'endDate' => ['date', 'required', 'after:startDate'],
            'enSpecificTo' => ['string', 'nullable'],
            'arSpecificTo' => ['string', 'nullable'],
            'coverPhoto' => ['string', 'nullable'],

        ];
    }
}
