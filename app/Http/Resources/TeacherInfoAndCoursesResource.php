<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherInfoAndCoursesResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->userRole(),
            'avatar' => $this->getAvatarPath(),
            'courses' => CourseBasicInfoResource::collection($this->teacherCourses),
            'certificates' => CertificationResource::collection($this->attachments),
        ];
    }
    protected function userRole()
    {
        return is_null($this->roles()) ? null : $this->roles->implode('name', ',');
    }
}
