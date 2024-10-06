<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $exam = $this->route('exam');
        if ($exam->course->teacher_id == auth()->id()) {
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
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'isUnitExam' => ['required', 'boolean'],
            'units' => ['required_if:isUnitExam,1', 'array'],
            'units.*' => ['required', 'exists:units,id'],
            'type' => ['nullable', 'in:mcq,file'],
            'examFile' => ['required_if:type,file', 'max:1048576'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
