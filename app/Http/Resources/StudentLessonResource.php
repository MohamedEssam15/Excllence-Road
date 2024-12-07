<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class StudentLessonResource extends JsonResource
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
        $type = $this->type;
        if ($type == 'meeting') {
            $availableIn = $this->meeting_date?->subHours(1);
            $isAvailable = $availableIn?->lt(now());
        } else {
            $isAvailable = false;
            $availableIn = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->translate($this->locale)->name,
            'description' => $this->translate($this->locale)->description,
            'type' => $this->type,
            'lesson' => $this->type == 'meeting' ? $this->video_link : $this->getVideoLink(),
            'attachments' => LessonAttachmentsResource::collection($this->attachments),
            'isAvailable' => $isAvailable,
            'availableIn' => $availableIn?->format('Y-m-d g:i A'),
        ];
    }
}
