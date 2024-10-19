<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseExamResource extends JsonResource
{
    public function __construct($exam)
    {
        parent::__construct($exam);
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
            'name' => $this->name,
            'description' => $this->description,
            'isUnitExam' => $this->is_unit_exam,
            'units' => UnitInfoResource::collection($this->units),
            'type' => $this->type,
            'examFile' => $this->getExamFile(),
            'questions' => TeacherQuestionsResource::collection($this->questions)
        ];
    }
}
