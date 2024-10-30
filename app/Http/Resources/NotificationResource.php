<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'sender' => new TeacherInfoResource($this->sender),
            'course' => new CourseBasicInfoResource($this->course),
            'lesson' => new LessonInfoResource($this->lesson),
            'is_read' => $this->is_read,
            'sentAt' => $this->created_at->diffForHumans()
        ];
    }
}
