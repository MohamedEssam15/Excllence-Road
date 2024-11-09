<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddExamToCoursesRequest extends FormRequest
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
        $course = Course::find(request('courseId'));
        return [
            'courseId' => ['required', 'integer', 'exists:courses,id'],
            'availableFrom' => ['required', 'date', 'after_or_equal:' . $course?->start_date],
            'availableTo' => ['required', 'date', 'before:' . $course?->end_date, 'after:' . request('availableFrom')],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
