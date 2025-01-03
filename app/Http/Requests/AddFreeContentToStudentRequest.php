<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddFreeContentToStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('add-course-to-student')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->type == 'course') {
            return [
                'type' => ['required', 'string', 'in:course,package'],
                'itemId' => ['required', 'integer', 'exists:courses,id'],
                'discountPercentage' => ['required', 'integer', 'min:0', 'max:100'],
            ];
        } else {
            return [
                'type' => ['required', 'string', 'in:course,package'],
                'itemId' => ['required', 'integer', 'exists:package,id'],
                'discountPercentage' => ['required', 'integer', 'min:0', 'max:100'],
            ];
        }
    }
}
