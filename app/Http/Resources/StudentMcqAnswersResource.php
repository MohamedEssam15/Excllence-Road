<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentMcqAnswersResource extends JsonResource
{
    public $resource;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dsd($this->resource);
        return [
            'id' => $this->id,
            'question'=> $this->getQuestion(),
            'questionType' => $this->question_type,
            'answers'=> $this->answers()->select('id', 'answer')->get(),
            'studentAnswerId'=> $this->pivot->answer_id,
            'isCorrect'=> $this->pivot->is_correct,
            'correctAnswerId'=> $this->answer_id
        ];
    }
}
