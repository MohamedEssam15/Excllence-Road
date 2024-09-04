<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CourseLevelResource extends JsonResource
{

    private $locale;
    private $level;
    public function __construct($level)
    {
        $this->locale =App::getLocale();
        $this->level =$level;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->level->id,
            "name"=>$this->level->translate()
        ];
    }
}
