<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherQuestionAnswerResource extends JsonResource
{
    public function __construct($answer)
    {
        parent::__construct($answer);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->id == $this->question->answer_id) {
            $isCorrect = true;
        } else {
            $isCorrect = false;
        }
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'isCorrect' => $isCorrect,
        ];
    }
}
