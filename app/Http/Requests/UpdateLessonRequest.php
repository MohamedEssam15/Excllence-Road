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
        if($this->route('lesson')->unit->course->teacher_id == auth()->id()){
            return true;
        }else{
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
        return [
            'enName' => ['string', 'required'],
            'arName' => ['string', 'required'],
            'enDescription' => ['string', 'required'],
            'arDescription' => ['string', 'required'],
            'order' => ['required', 'integer', Rule::unique('lessons')->where(function ($query) {
                return $query->where('unit_id', $this->input('unitId'));
            })->ignore($this->route('lesson')->id)],
            'type' => ['nullable', 'string', 'in:video,meeting'],
            'meetingLink' => ['required_if:type,meeting', 'url'],
            'video' => ['required_if:type,video', 'file', 'max:4194304'],
        ];
    }
}
