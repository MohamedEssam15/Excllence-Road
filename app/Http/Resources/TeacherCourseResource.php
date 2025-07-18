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
        return [
            'id' => $this->course->id,
            'name' => $this->course->translate($this->locale)->name ?? $this->course->name,
            'arName' => $this->course->translate('ar')->name ?? null,
            'enName' => $this->course->translate('en')->name ?? null,
            'description' => $this->course->translate($this->locale)->description ?? $this->course->description,
            'arDescription' => $this->course->translate('ar')->description ?? null,
            'enDescription' => $this->course->translate('en')->description ?? null,
            'coverPhoto' => $this->course->getCoverPhotoPath(),
            'teacher' => new TeacherInfoResource($this->course->teacher),
            'category' => new CategoryInfoResource($this->course->category),
            'price' => $this->course->price,
            'startDate' => $this->course->start_date,
            'endDate' => $this->course->end_date,
            'isSpecific' => $this->course->is_specific,
            'specificTo' => $this->course->translate($this->locale)->specific_to ?? $this->course->specific_to,
            'arSpecificTo' => $this->course->translate('ar')->specific_to ?? null,
            'enSpecificTo' => $this->course->translate('en')->specific_to ?? null,
            'status' => new CourseStatusResource($this->course->status),
            'level' => new CourseLevelResource($this->course->level),
            'rating' => $this->course->average_rating,
            'courseTrailer' => $this->course->getCourseTrailerPath(),
            'reviews' => ReviewResource::collection($this->course->reviews),
        ];
    }
}
