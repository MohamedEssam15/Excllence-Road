<?php

namespace App\Http\Controllers\ApiControllers\TeacherPanalControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddExamRequest;
use App\Http\Requests\AddQuestionsRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\TeacherCourseExamResource;
use App\Http\Resources\TeacherExamResource;
use App\Http\Resources\TeacherQuestionsResource;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Services\Exams\TeacherExamsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class ExamController extends Controller
{
    public function addExam(AddExamRequest $request, Course $course)
    {
        $teacherExamServices = new TeacherExamsServices();
        $exam = $teacherExamServices->addExam($course, $request->type, $request->name, $request->description, $request->isUnitExam, $request->units, $request->examFile);
        return apiResponse(__('response.addedSuccessfully'), new TeacherExamResource($exam));
    }

    public function addQuestions(AddQuestionsRequest $request, Exam $exam)
    {
        $questions = $request->bankQuestions ?? [];
        if ($request->questions != null) {
            foreach ($request->questions as $question) {
                $questionModel = Question::create([
                    'question' => $question['question'],
                    'category_id' => $exam->course->category_id,
                    'user_id' => auth()->id(),
                    'is_question_bank' => $question['addToPublicQuestionBank'],
                ]);
                foreach ($question['answers'] as $answer) {
                    $questionAnswersModel = QuestionAnswer::create([
                        'answer' => $answer['answer'],
                        'question_id' => $questionModel->id
                    ]);

                    if ($answer['isCorrect']) {
                        $questionModel->answer_id = $questionAnswersModel->id;
                    }
                }
                $questionModel->save();
                $questions[] = $questionModel->id;
            }
        }

        $exam->questions()->attach($questions);
        return apiResponse(__('response.addedSuccessfully'), ['questions' => TeacherQuestionsResource::collection($exam->questions)]);
    }

    public function updateExam(UpdateExamRequest $request, Exam $exam)
    {
        $teacherExamServices = new TeacherExamsServices();
        $exam = $teacherExamServices->updateExam($exam, $request->type, $request->name, $request->description, $request->isUnitExam, $request->units, $request->examFile);
        return apiResponse(__('response.updatedSuccessfully'), new TeacherExamResource($exam));
    }
    public function deleteExam(Exam $exam)
    {
        if ($exam->course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        $teacherExamServices = new TeacherExamsServices();
        $exam = $teacherExamServices->deleteExam($exam);
        return apiResponse(__('response.deletedSuccessfully'));
    }
    public function getExam(Exam $exam)
    {
        if ($exam->course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        return apiResponse('Data Retrieved', new TeacherExamResource($exam));
    }
    public function removeQuestions(Request $request, Exam $exam)
    {
        if ($exam->course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        $request->validate([
            'questions' => ['required', 'array'],
            'questions.*' => 'required|exists:questions,id',
        ]);
        $exam->questions()->detach($request->questions);
        return apiResponse('Data Retrieved', new TeacherExamResource($exam));
    }

    public function teacherQuestionsBank()
    {
        $questionQuery = Question::filterBy(request()->all());
        $questionQuery->where('user_id', auth()->id());
        $questions = $questionQuery->paginate(request()->perPage ?? 10);
        if (!isset($questions[0])) {
            return apiResponse(__('response.notFound'), new stdClass(), [__('response.notFound')]);
        }
        return apiResponse('Data Retrieved', new PaginatedCollection($questions, TeacherQuestionsResource::class));
    }
    public function publicQuestionsBank()
    {
        $questionQuery = Question::filterBy(request()->all());
        $questionQuery->where('is_question_bank', true);
        $questions = $questionQuery->paginate(request()->perPage ?? 10);
        if (!isset($questions[0])) {
            return apiResponse(__('response.notFound'), new stdClass(), [__('response.notFound')]);
        }
        return apiResponse('Data Retrieved', new PaginatedCollection($questions, TeacherQuestionsResource::class));
    }

    public function couresExams(Course $course)
    {
        if ($course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }

        return apiResponse('Data Retrieved', ['exams' => TeacherCourseExamResource::collection($course->exams)]);
    }
    public function teacherExams()
    {
        $teacherId = auth()->user()->id;
        $exams = Exam::whereHas('course', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->paginate(request()->perPage ?? 10);
        return apiResponse('Data Retrieved', new PaginatedCollection($exams, TeacherExamResource::class));
    }
}
