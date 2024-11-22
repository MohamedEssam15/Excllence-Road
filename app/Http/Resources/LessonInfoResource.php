<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class LessonInfoResource extends JsonResource
{
    protected $locale;

    public function __construct($lesson)
    {
        parent::__construct($lesson);
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
            'name' => $this->translate($this->locale)?->name  ?? $this->name,
            'description' => $this->translate($this->locale)?->description ?? $this->description,
            'type' => $this->type,
        ];
    }
}
