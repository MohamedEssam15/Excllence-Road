<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterTeacherRequest extends FormRequest
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
            'name'=>['string','required'],
            'email'=>['string','email','unique:users,email','required'],
            'password'=>['string','min:8','required'],
            'photo'=>'required|file|mimes:jpg,jpeg,png|max:10240',
            'phone'=>['required'],
            'Certificates'=>['nullable','array'],
            'certificates.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
