<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function __construct($review)
    {
        parent::__construct($review);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "rating" => $this->rating,
            "comment" => $this->comment,
            "course" => new CourseBasicInfoResource($this->course),
            'student' => new TeacherInfoResource($this->student)
        ];
    }
}
