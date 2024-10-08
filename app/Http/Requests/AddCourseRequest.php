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
        return $this->isJson();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(isset(request()->enSpecificTo) && ! is_null(request()->enSpecificTo)){
            request()->merge(['isSpecific' => true]);
        }else{
            request()->merge(['isSpecific' => false]);
        }
        return [
            'enName'=>['string','required'],
            'arName'=>['string','required'],
            'enDescription'=>['string','required'],
            'arDescription'=>['string','required'],
            'categoryId'=>['required','exists:categories,id'],
            'levelId'=>['required','exists:course_levels,id'],
            'startDate'=>['date','required','after:'.now()->format('Y-m-d')],
            'endDate'=>['date','required','after:startDate'],
            'enSpecificTo'=>['required_with:arSpecificTo','string'],
            'arSpecificTo'=>['required_with:enSpecificTo','string'],
            'coverPhoto'=>['string','required'],

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
