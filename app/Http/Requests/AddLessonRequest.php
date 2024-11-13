<?php

namespace App\Http\Requests;

use App\Models\Unit;
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
        $unit = Unit::find(request('unitId'));
        return [
            'enName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value && ! request()->filled('arName')) {
                    if (request()->filled('arDescription')) {
                        $fail(__('response.oneLangEn'));
                    }
                }
            }],
            'arName' => ['string', 'nullable', function ($attribute, $value, $fail) {
                if ($value && ! request()->filled('enName')) {
                    if (request()->filled('enDescription')) {
                        $fail(__('response.oneLangAr'));
                    }
                }
            }],
            'enDescription' => ['string', 'nullable'],
            'arDescription' => ['string', 'nullable'],
            'unitId' => ['required', 'exists:units,id'],
            'order' => ['required', 'integer', Rule::unique('lessons')->where(function ($query) {
                return $query->where('unit_id', $this->input('unitId'));
            })],
            'type' => ['required', 'string', 'in:video,meeting'],
            'meetingLink' => ['required_if:type,meeting', 'url'],
            'meetingDate' => ['required_if:type,meeting', 'date_format:Y-m-d H:i', 'after_or_equal:' . $unit?->course->start_date, 'before:' . $unit?->course->end_date],
            'video' => ['required_if:type,video', 'file', 'max:419304'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => 'required|file|max:1048576',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
