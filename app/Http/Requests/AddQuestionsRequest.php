<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use stdClass;

class AddQuestionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $exam = $this->route('exam');
        if ($exam->course->teacher_id == auth()->id()) {
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
        return [
            'bankQuestions' => 'nullable|array|min:1',
            'bankQuestions.*' => 'required|exists:questions,id',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.answer' => 'required|string|max:255',
            'questions.*.answers.*.isCorrect' => 'required|boolean',
            'questions.*.addToPublicQuestionBank' => 'required|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exam = $this->route('exam');
            if ($exam->type == 'file') {
                $validator->errors()->add('exam_type', __('response.pleaseChangeExamType'));
            }
            $questions = $this->input('questions') ?? [];
            foreach ($questions as $index => $question) {
                $correctAnswersCount = collect($question['answers'])->where('isCorrect', true)->count();
                if ($correctAnswersCount != 1) {
                    $validator->errors()->add("questions.$index.answers", __('response.questionShouldHaveCorrectAnswer'));
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
