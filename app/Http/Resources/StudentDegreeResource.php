<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudentDegreeResource extends JsonResource
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
            'avatar' => $this->getAvatarPath(),
            'grade' => $this->pivot->grade,
            'AnswerFile' => $this->pivot->file_name ? Storage::disk('public')->url("exams/{$this->pivot->exam_id}/students_answers/{$this->pivot->user_id}/{$this->pivot->file_name}") : null
        ];
    }
}
