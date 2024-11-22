<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDegreeExamResource extends JsonResource
{
    private $courseId;
    public function __construct($resource, $courseId)
    {
        $this->courseId = $courseId;
        parent::__construct($resource);
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
            'type' => $this->type,
            'examName' => $this->name,
            'description' => $this->description,
            'degree' => $this->getExamDegree(),
            'students' => StudentDegreeResource::collection($this->studentsAnswer()->wherePivot('course_id', $this->courseId)->get()),

        ];
    }
}
