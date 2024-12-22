<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class StudentInfoWithCoursesAndExamsResource extends JsonResource
{
    protected $locale;

    public function __construct($resource)
    {
        parent::__construct($resource);
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->getAvatarPath(),
            'phone' => $this->phone,
            'role' => $this->userRole(),
            'courses' => StudentCourseResource::collection($this->enrollments),
            'exams' => StudentExamResource::collection($this->studentExams),
        ];
    }
    protected function userRole()
    {
        return is_null($this->roles()) ? null : $this->roles->implode('name', ',');
    }
}
