<?php

namespace App\Http\Controllers\ApiControllers\TeacherPanalControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddExamRequest;
use App\Http\Requests\AddExamToCoursesRequest;
use App\Http\Requests\AddQuestionsRequest;
use App\Http\Requests\CopyExamRequest;
use App\Http\Requests\RemoveQuestionsExamRequest;
use App\Http\Requests\SubmitExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\StudentExamResource;
use App\Http\Resources\TeacherCourseExamResource;
use App\Http\Resources\TeacherExamResource;
use App\Http\Resources\TeacherQuestionsResource;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Services\Exams\TeacherExamsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class ExamController extends Controller
{
    public function addExam(AddExamRequest $request, Course $course)
    {
        $teacherExamServices = new TeacherExamsServices();
        $exam = $teacherExamServices->addExam($course, $request->type, $request->name, $request->description, $request->examTime,$request->examDegree, $request->availableFrom, $request->availableTo, $request->isUnitExam, $request->units, $request->examFile);
        return apiResponse(__('response.addedSuccessfully'), new TeacherExamResource($exam));
    }

    public function addQuestions(AddQuestionsRequest $request, Exam $exam)
    {
        $questions = $request->bankQuestions ?? [];
        if ($request->questions != null) {
            foreach ($request->questions as $question) {
                if ($question['type'] == 'image') {
                    $image_parts = explode(";base64,", $question['question']);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image = base64_decode($image_parts[1]);
                    $imageName = Str::random(10) . '.' . $image_type;
                    $questionModel = Question::create([
                        'question' => $imageName,
                        'category_id' => $exam->courses[0]->category_id,
                        'user_id' => auth()->id(),
                        'question_type' => $question['type'],
                        'is_question_bank' => $question['addToPublicQuestionBank'],
                    ]);
                    $path = 'teacher_questions/' . auth()->id() . '/' . $questionModel->id . '/' . $imageName;
                    Storage::disk('public')->put($path, $image);
                } else {
                    $questionModel = Question::create([
                        'question' => $question['question'],
                        'category_id' => $exam->courses[0]->category_id,
                        'user_id' => auth()->id(),
                        'question_type' => $question['type'],
                        'is_question_bank' => $question['addToPublicQuestionBank'],
                    ]);
                }

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
        $exam = $teacherExamServices->updateExam($exam, $request->type, $request->name, $request->description, $request->examTime,$request->examDegree, $request->isUnitExam, $request->units, $request->examFile);
        return apiResponse(__('response.updatedSuccessfully'), new TeacherExamResource($exam));
    }
    public function deleteExam(Exam $exam)
    {
        if ($exam->courses[0]->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        $teacherExamServices = new TeacherExamsServices();
        $exam = $teacherExamServices->deleteExam($exam);
        return apiResponse(__('response.deletedSuccessfully'));
    }
    public function getExam(Exam $exam)
    {
        if ($exam->courses[0]->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        return apiResponse('Data Retrieved', new TeacherExamResource($exam));
    }
    public function removeQuestions(RemoveQuestionsExamRequest $request, Exam $exam)
    {
        if ($exam->courses[0]->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
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
        $exams = Exam::whereHas('courses', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->paginate(request()->perPage ?? 10);
        return apiResponse('Data Retrieved', new PaginatedCollection($exams, TeacherExamResource::class));
    }

    public function deleteQuestion(Question $question)
    {
        if ($question->user_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        if ($question->examsNotCreatedByTeacher()) {
            return apiResponse(__('response.cantDeleteQuestion'), new stdClass(), [__('response.cantDeleteQuestion')], 403);
        }
        $question->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }

    public function assignExamToCourse(AddExamToCoursesRequest $request, Exam $exam)
    {
        $course = Course::find($request->courseId);
        if ($course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        $exists = $exam->courses()->wherePivot('course_id', $course->id)->exists();
        if ($exam->units()->exists()) {
            return apiResponse(__('response.examHasUnits'), new stdClass(), [__('response.examHasUnits')], 401);
        }
        if (!$exists) {
            $exam->courses()->attach([
                $course->id => ['available_from' => $request->availableFrom, 'available_to' => $request->availableTo]
            ]);
        } else {
            return apiResponse(__('response.courseAlreadyAssigned'), new stdClass(), [__('response.courseAlreadyAssigned')], 422);
        }
        return apiResponse(__('response.addedSuccessfully'), new TeacherExamResource($exam));
    }

    public function copyExam(CopyExamRequest $request, Exam $exam)
    {
        $course = Course::find($request->courseId);
        if ($course->teacher_id != auth()->id()) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        if ($exam->units()->exists()) {
            return apiResponse(__('response.examHasUnits'), new stdClass(), [__('response.examHasUnits')], 401);
        }
        if ($exam->type == 'file') {
            return apiResponse(__('response.examIsFile'), new stdClass(), [__('response.examIsFile')], 401);
        }
        $examQuestions = $exam->questions()->pluck('questions.id')->toArray();
        $newExam = $exam->replicate();
        $newExam->name = $request->name;
        $newExam->save();
        $newExam->courses()->attach($course->id, [
            'available_from' => $request->availableFrom,
            'available_to' => $request->availableTo,
        ]);
        $newExam->questions()->attach($examQuestions);
        return apiResponse(__('response.addedSuccessfully'), new TeacherExamResource($newExam));
    }
}
