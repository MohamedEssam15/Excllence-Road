<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CourseInfoResource extends JsonResource
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
            'name' => $this->translate($this->locale)->name,
            'description' => $this->translate($this->locale)->description,
            'coverPhoto' => $this->getCoverPhotoPath(),
            'teacher' => new TeacherInfoResource($this->teacher),
            'isJoined' => $this->checkJoined(),
            'category' => new CategoryInfoResource($this->category),
            'units' => UnitInfoResource::collection($this->units),
            'level' => new CourseLevelResource($this->level),
            'price' => $this->new_price ?? $this->price,
            'oldPrice' => $this->price,
            'discount' => $this->discount,
            'discountType' => $this->discount_type,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'isSpecific' => $this->is_specific,
            'specificTo' => $this->translate($this->locale)->specific_to,
            'isMobileOnly' => $this->is_mobile_only,
            'rating' => $this->average_rating,
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }
    private function checkJoined()
    {
        return $this->enrollments()->where('user_id', auth()->user()->id ?? null)->where(function ($q) {
            $q->where(function ($q) {
                $q->where('courses_users.end_date', '>', Carbon::today())->orWhereNull('courses_users.end_date');
            });
        })->exists();
    }
}
