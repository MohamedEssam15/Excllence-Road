<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $course = $this->route('course');
        $unit =$this->route('unit');

        if($course->teacher_id == auth()->id() && $unit->course_id == $course->id){
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
            'enName' => 'required|string|min:1',
            'arName' => 'required|string|min:1',
            'order' => ['required','integer', Rule::unique('units')->where(function ($query) {
                return $query->where('course_id', $this->route('course')->id);
            })->ignore($this->route('unit')->id)],
        ];
    }
}
