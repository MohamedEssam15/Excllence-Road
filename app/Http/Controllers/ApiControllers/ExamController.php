<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignGradeRequest;
use App\Http\Requests\SubmitExamRequest;
use App\Http\Resources\StudentAnswersResource;
use App\Http\Resources\StudentDegreeExamResource;
use App\Http\Resources\StudentDegreeResource;
use App\Http\Resources\StudentExamResource;
use App\Jobs\CalcUserGradeJob;
use App\Models\Course;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use stdClass;

class ExamController extends Controller
{
    public function getCourseExams(Course $course, Exam $exam)
    {
        $user = auth()->user();
        if (! $course->enrollments()->where('user_id', $user->id)->exists()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        if (!$course->exams()->where('exam_id', $exam->id)->exists()) {
            return apiResponse(__('response.courseNotHaveThatExam'), new stdClass(), [__('response.courseNotHaveThatExam')], 422);
        }
        $currentTime = now();
        if ($user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id)->exists()) {
            $userExamResult = $user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id)->first();
            $elapsedTime = $currentTime->diffInMinutes($userExamResult->pivot->start_time);
            if ($exam->exam_time == null || $elapsedTime < $exam->exam_time) {
                return apiResponse('Data Retrieved', ['exam' => new StudentExamResource($course->exams()->where('exam_id', $exam->id)->first())]);
            }
            // else {
            //     return apiResponse(__('response.youHaveThatExam'), new stdClass(), [__('response.youHaveThatExam')], 422);
            // }
        }
        if ($exam->type == 'mcq') {
            $degree = $exam->questions()->count();
        } else {
            $degree = null;
        }

        $user->studentExams()->attach([$exam->id => ['file_name' => null, 'grade' => null, 'degree' => $degree, 'start_time' => $currentTime->addMinutes(5), 'course_id' => $course->id]]);

        return apiResponse('Data Retrieved', ['exam' => new StudentExamResource($course->exams()->where('exam_id', $exam->id)->first())]);
    }

    public function submitExam(SubmitExamRequest $request, Course $course, Exam $exam)
    {
        $user = auth()->user();
        $currentTime = now();
        $userExamResultQuery = $user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id);
        $userExamResult = $userExamResultQuery->first();
        $elapsedTime = $currentTime->diffInMinutes($userExamResult->pivot->start_time);

        // if ($exam->exam_time != null && $elapsedTime > $exam->exam_time) {
        //     return apiResponse(__('response.examTimeExpired'), new stdClass(), [__('response.examTimeExpired')], 422);
        // }
        // if ($userExamResult->pivot->grade != null || $userExamResult->pivot->file_name != null) {
        //     return apiResponse(__('response.youHaveThatExam'), new stdClass(), [__('response.youHaveThatExam')], 422);
        // }
        if ($exam->type == 'file') {
            $file = $request->file('answerFile');
            $path = "/exams/{$exam->id}/students_answers/{$user->id}/";
            $file->storeAs($path, $file->getClientOriginalName(), 'public');
            $userExamResultQuery->updateExistingPivot($exam->id, ['file_name' => $file->getClientOriginalName()]);
        } else {
            $grade  = 0;
            $questions = $exam->questions;
            $answers = $request->answers;
            dispatch(new CalcUserGradeJob($user, $questions, $answers, $exam->id, $course->id))->delay(1);
            $userExamResult->update(['grade' => $grade]);
        }
        return apiResponse(__('response.examSubmittedSuccessfully'), new stdClass());
    }

    public function studentsDegree(Course $course, Exam $exam)
    {
        $user = auth()->user();
        if ($course->teacher_id != $user->id) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        return apiResponse(__('response.dataRetrieved'), new StudentDegreeExamResource($exam, $course->id));
    }
    public function studentAnswers(Course $course, Exam $exam, User $user)
    {

        $teacher = auth()->user();
        if ($course->teacher_id != $teacher->id) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        if (!$user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id)->exists()) {
            return apiResponse(__('response.studentDidNotTakeThatExam'), new stdClass(), [__('response.studentDidNotTakeThatExam')], 422);
        }
        return apiResponse(__('response.dataRetrieved'), new StudentAnswersResource($user->studentQuestions, $exam, $course, $user));
    }
    public function updateExamGrade(AssignGradeRequest $request, Course $course, Exam $exam, User $user)
    {
        $teacher = auth()->user();
        if ($course->teacher_id != $teacher->id) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        if ($exam->type != 'file') {
            return apiResponse(__('response.theGradeIsCalculatedAutomatically'), new stdClass(), [__('response.theGradeIsCalculatedAutomatically')], 422);
        }
        if (!$user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id)->exists()) {
            return apiResponse(__('response.studentDidNotTakeThatExam'), new stdClass(), [__('response.studentDidNotTakeThatExam')], 422);
        }
        $user->studentExams()->where('exam_id', $exam->id)->wherePivot('course_id', $course->id)->update(['grade' => $request->grade]);
        return apiResponse(__('response.updatedSuccessfully'));
    }
}
