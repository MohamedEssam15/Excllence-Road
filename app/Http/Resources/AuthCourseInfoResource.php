<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class AuthCourseInfoResource extends JsonResource
{
    protected $locale;

    public function __construct($course)
    {
        parent::__construct($course);
        $this->locale = App::getLocale();
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
            'name' => $this->translate($this->locale)->name ?? $this->name,
            'description' => $this->translate($this->locale)->description ?? $this->description,
            'coverPhoto' => $this->getCoverPhotoPath(),
            'teacher' => new TeacherInfoResource($this->teacher),
            'category' => new CategoryInfoResource($this->category),
            'units' => UnitInfoResource::collection($this->units),
            'level' => new CourseLevelResource($this->level),
            'price' => $this->price,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'isJoined' => $this->enrollments()->where('user_id', auth()->user()->id ?? null)->exists(),
            'isSpecific' => $this->is_specific,
            'specificTo' => $this->translate($this->locale)->specific_to ?? $this->specific_to,
            'rating' => $this->average_rating,
            'reviews' => ReviewResource::collection($this->reviews),
            'exams' => ExamInfoResource::collection($this->exams),
        ];
    }
}
