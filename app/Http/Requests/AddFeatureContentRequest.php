<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddFeatureContentRequest extends FormRequest
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
        $rules = [
            'subject' => 'nullable|string',
            'contentType' => 'required|string|in:photo,video',
            'assignToType' => 'nullable|string|in:course,package,0',
        ];
        if ($this->contentType == 'photo') {
            $rules['uploadedFile'] = 'required|file|image|max:2048';
        } elseif ($this->contentType == 'video') {
            $rules['uploadedFile'] = 'required|file|mimes:mp4,avi,mkv|max:51200';
        }
        if ($this->assignToType == 'course') {
            $rules['assignTo'] = 'required|exists:courses,id';
        } elseif ($this->assignToType == 'package') {
            $rules['assignTo'] = 'required|exists:packages,id';
        } else {
            $rules['assignTo'] = 'nullable';
        }
        return $rules;
    }
}
