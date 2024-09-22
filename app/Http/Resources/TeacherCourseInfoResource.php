<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'enName'=>$this->name,
            'arName'=>$this->translate('ar')->name,
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
            'enSpecificTo'=>$this->specific_to,
            'arSpecificTo'=>$this->translate('ar')->specific_to,
        ];
    }
}
