<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentExamResource extends JsonResource
{
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'course' => new CourseBasicInfoResource(Course::find($this->pivot->course_id)),
            'examTime' => $this->exam_time,
            'degree' => $this->getExamDegree(),
            'studentGrade' =>  $this->pivot->grade != null ? (string) $this->pivot->grade : __('response.pending'),
            'isUnitExam' => $this->is_unit_exam,
            'units' => $this->is_unit_exam ? UnitInfoResource::collection($this->units) : null,

        ];
    }
}
