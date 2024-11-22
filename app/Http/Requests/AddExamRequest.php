<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddExamRequest extends FormRequest
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
        $course = request()->course;
        // ds(request()->course)->die();
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'isUnitExam' => ['required', 'boolean'],
            'examTime' => ['nullable', 'integer'],
            'availableFrom' => ['required', 'date', 'after_or_equal:' . $course->start_date],
            'availableTo' => ['required', 'date', 'before:' . $course->end_date, 'after:' . request()->availableFrom],
            'units' => ['required_if:isUnitExam,1', 'array'],
            'units.*' => ['required', 'exists:units,id'],
            'type' => ['required', 'in:mcq,file'],
            'examFile' => ['required_if:type,file', 'max:1048576'],
            'examDegree' => ['required_if:type,file', 'integer', 'min:0', 'max:100'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
