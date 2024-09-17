<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class UnitInfoResource extends JsonResource
{
    protected $locale;

    public function __construct($unit)
    {
        parent::__construct($unit);
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
            'id'=>$this ->id,
            'name'=>$this->translate($this->locale),
            'lessons'=> LessonInfoResource::collection($this->lessons),
        ];
    }
}
