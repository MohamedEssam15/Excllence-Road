<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherUnitInfoResource extends JsonResource
{

    public function __construct($unit)
    {
        parent::__construct($unit);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this ->id,
            'enName'=>$this->name,
            'arName'=>$this->translate('ar'),
            'lessons'=> TeacherLessonInfoResource::collection($this->lessons),
        ];
    }
}
