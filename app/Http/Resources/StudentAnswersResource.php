<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class StudentAnswersResource extends JsonResource
{
    public $resource;
    public $exam;
    public $course;
    public $student;
    public $locale;

    public function __construct($resource, $exam, $course, $student)
    {
        parent::__construct($resource);
        $this->exam = $exam;
        $this->course = $course;
        $this->student = $student;
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
            'userId' => $this->student->id,
            'userName' => $this->student->name,
            'userAvatar' => $this->student->getAvatarPath(),
            'examId' => $this->exam->id,
            'examName' => $this->exam->name,
            'examType' => $this->exam->type,
            'courseId' => $this->course->id,
            'courseName' => $this->course->translate($this->locale)->name ?? $this->course->name,
            'answers' => $this->exam->type == 'mcq' ? $this->getMcqQuestions() : $this->getAnswerFile(),
        ];
    }

    private function getMcqQuestions()
    {
        return StudentMcqAnswersResource::collection($this->resource);
    }

    private function getAnswerFile()
    {
        $fileName = $this->student->studentExams()->where('exam_id', $this->exam->id)->wherePivot('course_id', $this->course->id)->first()->pivot->file_name;
        return $fileName ? Storage::disk('public')->url("exams/{$this->exam->id}/students_answers/{$this->student->id}/{$fileName}") : null;
    }
}
