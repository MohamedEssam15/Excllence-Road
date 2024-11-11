<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherCourseResource extends JsonResource
{
    private $locale;
    private $course;
    public function __construct($course)
    {
        $this->locale = App::getLocale();
        $this->course = $course;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        ds($this->course->status, $this->course->level);
        return [
            'id' => $this->course->id,
            'name' => $this->course->translate($this->locale)->name ?? $this->name,
            'description' => $this->course->translate($this->locale)->description ?? $this->description,
            'coverPhoto' => $this->course->getCoverPhotoPath(),
            'teacher' => new TeacherInfoResource($this->course->teacher),
            'category' => new CategoryInfoResource($this->course->category),
            'price' => $this->course->price,
            'startDate' => $this->course->start_date,
            'endDate' => $this->course->end_date,
            'isSpecific' => $this->course->is_specific,
            'specificTo' => $this->course->translate($this->locale)->specific_to ?? $this->specific_to,
            'status' => new CourseStatusResource($this->course->status),
            'level' => new CourseLevelResource($this->course->level),
            'rating' => $this->average_rating,
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }
}
