<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherLessonInfoResource extends JsonResource
{
    public function __construct($lesson)
    {
        parent::__construct($lesson);
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
            'enName' => $this->name,
            'arName' => $this->translate('ar')->name,
            'enDescription' => $this->description,
            'arDescription' => $this->translate('ar')->description,
            'type' => $this->type,
            'lesson' => $this->type == 'meeting' ? $this->video_link : $this->getVideoLink(),
            'attachments'=>LessonAttachmentsResource::collection($this->attachments)
        ];
    }
}
