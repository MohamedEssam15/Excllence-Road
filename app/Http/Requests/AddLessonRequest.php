<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AddLessonRequest extends FormRequest
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
            'enName' => ['string', 'required'],
            'arName' => ['string', 'required'],
            'enDescription' => ['string', 'required'],
            'arDescription' => ['string', 'required'],
            'unitId' => ['required', 'exists:units,id'],
            'order' => ['required', 'integer', Rule::unique('lessons')->where(function ($query) {
                return $query->where('unit_id', $this->input('unitId'));
            })],
            'type' => ['required', 'string', 'in:video,meeting'],
            'meetingLink' => ['required_if:type,meeting', 'url'],
            'video' => ['required_if:type,video', 'file', 'max:10240'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => 'required|file|max:10240',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
