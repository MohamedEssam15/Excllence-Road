<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email,' . $this->id],
            'name' => ['required', 'string'],
            'permissions' => ['required', 'array'],
            'profileImage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'permissions.*' => ['required', 'exists:permissions,name'],
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'permissions' => json_decode($this->permissions),
        ]);
    }
}
