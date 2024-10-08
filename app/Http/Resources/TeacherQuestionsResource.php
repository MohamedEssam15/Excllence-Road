<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherQuestionsResource extends JsonResource
{

    public function __construct($question)
    {
        parent::__construct($question);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answers' => TeacherQuestionAnswerResource::collection($this->answers),
            'category' => new CategoryResource($this->category),
            'isPublicQuestionBank' => $this->is_question_bank
        ];
    }
}
