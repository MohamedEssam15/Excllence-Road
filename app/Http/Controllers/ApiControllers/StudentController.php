<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentInfoWithCoursesAndExamsResource;
use App\Http\Resources\StudentMcqAnswersResource;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getStudentInfo()
    {
        $student = auth()->user();
        $student->load(['studentExams', 'enrollments']);
        return apiResponse('Data Retrieved', new StudentInfoWithCoursesAndExamsResource($student));
    }
    public function getStudentAnswers($id)
    {
        $student = auth()->user();
        $student->load(['studentQuestions']);
        return apiResponse('Data Retrieved', ['questions' => StudentMcqAnswersResource::collection($student->studentQuestions()->where('exam_id', $id)->get())]);
    }
}
