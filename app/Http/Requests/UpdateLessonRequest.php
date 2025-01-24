<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('lesson')->unit->course->teacher_id == auth()->id()) {
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
        $course = request()->lesson->unit->course;
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
            'order' => ['required', 'integer', Rule::unique('lessons')->where(function ($query) {
                return $query->where('unit_id', $this->route('lesson')->unit_id);
            })->ignore($this->route('lesson')->id)],
            'type' => ['nullable', 'string', 'in:video,meeting,video_link'],
            'meetingLink' => ['required_if:type,meeting', 'url'],
            'videoLink' => ['required_if:type,video_link', 'url'],
            'meetingDate' => ['required_if:type,meeting', 'date_format:Y-m-d H:i', 'after_or_equal:' . $course?->start_date, 'before:' . $course?->end_date],
            'video' => ['required_if:type,video', 'file', 'max:4194304'],
        ];
    }
}
