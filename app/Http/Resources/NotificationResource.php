<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
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
            'message' => __('response.' . $this->message),
            'type' => $this->type,
            'isRead' => $this->is_read,
            'sentAt' => $this->created_at->diffForHumans(),
            'sender' => new TeacherInfoResource($this->sender),
            'course' => new CourseBasicInfoResource($this->course),
            'exam' => $this->exam != null ? new ExamInfoResource($this->course->exam($this->exam->id)) : null,
        ];
    }
}
