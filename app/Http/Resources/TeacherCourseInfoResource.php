<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherCourseInfoResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->translate($this->locale)->name,
            'enName'=>$this->name,
            'arName'=>$this->translate('ar')->name,
            'description'=>$this->translate($this->locale)->description,
            'enDescription'=>$this->description,
            'arDescription'=>$this->translate('ar')->description,
            'coverPhoto'=>$this->getCoverPhotoPath(),
            'teacher'=> new TeacherInfoResource($this->teacher),
            'category'=> new CategoryInfoResource($this->category),
            'units'=> TeacherUnitInfoResource::collection($this->units),
            'level'=> new CourseLevelResource($this->level),
            'price'=>$this->price,
            'startDate'=>$this->start_date,
            'endDate'=>$this->end_date,
            'isSpecific'=>$this->is_specific,
            'specificTo'=>$this->translate($this->locale)->specific_to,
            'enSpecificTo'=>$this->specific_to,
            'arSpecificTo'=>$this->translate('ar')->specific_to,
        ];
    }
}
