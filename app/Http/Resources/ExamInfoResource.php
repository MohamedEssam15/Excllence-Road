<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamInfoResource extends JsonResource
{
    public $resource;
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
            'degree' => $this->degree,
            'type' => $this->type,
            'examTime' => $this->exam_time,
            'isUnitExam' => $this->is_unit_exam,
            'units' => $this->is_unit_exam ? UnitInfoResource::collection($this->units) : null,
            'isPassed' => $this->studentsAnswer()->where('user_id', auth()->user()->id ?? null)->exists(),
            'availableFrom' => $this->pivot->available_from,
            'availableTo' => $this->pivot->available_to
        ];
    }
}
