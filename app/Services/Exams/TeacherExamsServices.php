<?php

namespace App\Services\Exams;

use App\Models\Exam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;


class TeacherExamsServices
{
    protected $updateExamHandlers = [
        'mcq-file' => 'handleMcqFile',
        'mcq-mcq' => 'handleMcqMcq',
        'file-mcq' => 'handleFileMcq',
        'file-file' => 'handleFileFile',
    ];

    public function addExam($course, $type, $name, $description, $isUnitExam, $units = null, $file = null)
    {
        if ($type == 'file') {
            $path = "/courses/{$course->id}/exams/";
            $examFile = $file;
            $fileExtension = $examFile->getClientOriginalExtension();
            $fileName = Str::random(10) . '.' . $fileExtension;
            $examFile->storeAs($path, $fileName, 'public');
        } else {
            $fileName = null;
        }
        $exam = Exam::create([
            'name' => $name,
            'description' => $description,
            'course_id' => $course->id,
            'is_unit_exam' => $isUnitExam,
            'type' => $type,
            'file_name' => $fileName
        ]);
        if ($isUnitExam) {
            $exam->units()->sync($units);
        }
        return $exam;
    }

    public function updateExam($exam, $type, $name, $description, $isUnitExam, $units = null, $file = null)
    {
        if ($type != null) {
            $newType = $type;
            $oldType = $exam->type;

            $key = "{$oldType}-{$newType}";

            if (!array_key_exists($key, $this->updateExamHandlers)) {
                return null;
            }

            $handlerMethod = $this->updateExamHandlers[$key];
            $this->$handlerMethod($exam, $file);
            $exam->type = $type;
        }

        $exam->name = $name;
        $exam->description = $description;
        $exam->is_unit_exam = $isUnitExam;
        $exam->save();
        if ($isUnitExam) {
            $exam->units()->sync($units);
        } else {
            $exam->units()->detach();
        }
        return $exam;
    }
    public function deleteExam($exam)
    {
        if ($exam->type == 'file') {
            $deletePath = "/courses/{$exam->course->id}/exams/{$exam->file_name}";
            Storage::disk('public')->delete($deletePath);
        }
        $exam->delete();
    }
    private function handleMcqFile($exam, $file = null)
    {
        $path = "/courses/{$exam->course->id}/exams/";
        $examFile = $file;
        $fileExtension = $examFile->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $examFile->storeAs($path, $fileName, 'public');
        $exam->file_name = $fileName;
        $exam->type = 'file';
        $exam->questions()->detach();
    }
    private function handleMcqMcq($exam, $file = null) {}
    private function handleFileFile($exam, $file = null)
    {
        $deletePath = "/courses/{$exam->course->id}/exams/{$exam->file_name}";
        $path = "/courses/{$exam->course->id}/exams/";
        Storage::disk('public')->delete($deletePath);
        $examFile = $file;
        $fileExtension = $examFile->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $examFile->storeAs($path, $fileName, 'public');
        $exam->file_name = $fileName;
    }
    private function handleFileMcq($exam, $file = null)
    {
        $deletePath = "/courses/{$exam->course->id}/exams/{$exam->file_name}";
        Storage::disk('public')->delete($deletePath);
        $exam->file_name = null;
        $exam->type = 'mcq';
    }
}
