<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use stdClass;

class RegisterUserRequest extends FormRequest
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
        return [
            'name'=>['string','required'],
            'email'=>['string','email','unique:users,email','required'],
            'password'=>['string','min:8','required'],
            'photo'=>['string','required'],
        ];
    }
    // public function failedValidation(Validator $validator)
    // {
    //     $errors = $validator->errors()->all();
    //     throw new HttpResponseException(apiResponse('Validation Error', new stdClass(), $errors, 422));
    // }
}
