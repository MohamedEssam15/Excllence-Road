<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentInfoWithCoursesAndExamsResource;
use App\Http\Resources\StudentMcqAnswersResource;
use App\Models\ContactUsMessage;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

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

    public function contactUS(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        ContactUsMessage::create($request->all());
        return apiResponse(__('response.messageSent'));
    }
}

