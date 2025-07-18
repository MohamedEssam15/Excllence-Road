<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->getAvatarPath(),
            'phone' => $this->phone,
            'role' => $this->userRole(),
            'coursesCount' => $this->teacher_courses_count,
        ];
    }
    protected function userRole()
    {
        return is_null($this->roles()) ? null : $this->roles->implode('name', ',');
    }
}
