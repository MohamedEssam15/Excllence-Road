<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentInfoWithCoursesAndExamsResource;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getStudentInfo()
    {
        $student = auth()->user();
        $student->load(['studentExams','enrollments']);
        return apiResponse('Data Retrieved', new StudentInfoWithCoursesAndExamsResource($student));
    }
}
