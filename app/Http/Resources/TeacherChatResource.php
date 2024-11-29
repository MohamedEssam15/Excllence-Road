<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherChatResource extends JsonResource
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
            'user' => new TeacherInfoResource($this->user),
            'course' => new CourseBasicInfoResource($this->course),
            'latestMessageTime' => $this->latest_message_time->diffForHumans()
        ];
    }
}
