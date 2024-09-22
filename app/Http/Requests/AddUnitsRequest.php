<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AddUnitsRequest extends FormRequest
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
            // 'courseId'=>['required','exists:courses,id'],
            'units' => 'required|array|min:1',
            'units.*.enName' => 'required|string|min:1',
            'units.*.arName' => 'required|string|min:1',
            'units.*.order' => ['required','integer','distinct', Rule::unique('units')->where(function ($query) {
                return $query->where('course_id', $this->route('course')->id);
            })],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
