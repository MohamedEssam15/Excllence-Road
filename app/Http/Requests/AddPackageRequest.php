<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPackageRequest extends FormRequest
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
            'arName' => 'required|string',
            'enName' => 'required|string',
            'arDescription' => 'required|string',
            'enDescription' => 'required|string',
            'price' => 'required|numeric',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'addToPopularPackages' => "nullable",
            'courses' => ['required', 'array'],
            'courses.*' => ['required', 'distinct', 'exists:courses,id'],
            'coverPhoto' => ['required', 'image']
        ];
    }
}
