<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherLessonInfoResource extends JsonResource
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
            'name' => $this->translate($this->locale)?->name ?? $this->name,
            'enName' => $this->translate('en')?->name,
            'arName' => $this->translate('ar')?->name,
            'description' => $this->translate($this->locale)?->description ?? $this->description,
            'enDescription' => $this->translate('en')?->description,
            'arDescription' => $this->translate('ar')?->description,
            'type' => $this->type,
            'order' => $this->order,
            'meetingDate' => $this->meeting_date?->format('Y-m-d g:i A'),
            'lesson' => $this->type == 'meeting' ? $this->video_link : $this->getVideoLink(),
            'attachments' => LessonAttachmentsResource::collection($this->attachments)
        ];
    }
}
