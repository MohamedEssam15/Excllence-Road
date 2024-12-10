<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\Auth\AuthController;
use App\Http\Controllers\ApiControllers\CategoryController;
use App\Http\Controllers\ApiControllers\CourseController;
use App\Http\Controllers\ApiControllers\NotificationController;
use App\Http\Controllers\ApiControllers\PackageController;
use App\Http\Controllers\ApiControllers\TeacherController;
use App\Http\Controllers\ApiControllers\PaymentController;
use App\Http\Controllers\ApiControllers\ReviewController;
use App\Http\Controllers\ApiControllers\ExamController as StudentExamController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\ExamController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\LessonsController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\UnitController;
use App\Models\Exam;
use App\Http\Controllers\ApiControllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::post('auth/teacher-register', [AuthController::class,'teacherRegister']);

Route::group(['middleware' => ['api'], 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->middleware('checkPlatformHeader');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register'])->middleware('checkPlatformHeader');
    Route::get('user-info', [AuthController::class, 'me']);
    Route::put('user-update', [AuthController::class, 'updateProfile']);
    Route::post('teacher-register', [AuthController::class, 'teacherRegister']);
    Route::post('password/email', [AuthController::class, 'sendResetCode']);
    Route::post('password/reset', [AuthController::class, 'resetWithCode']);
    Route::post('/email/send-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
    Route::delete('/delete', [AuthController::class, 'delete']);
});

Route::group(['prefix' => 'courses'], function () {
    Route::get('popular', [CourseController::class, 'getPopularCourses']);
    Route::get('teacher/{id}', [CourseController::class, 'getTeacherCourses']);
    Route::get('search', [CourseController::class, 'courseSearch']);
    Route::get('/', [CourseController::class, 'coursesFilters']);
    Route::get('/levels', [CourseController::class, 'getCourseLevels']);
    Route::get('/course-info/{id}', [CourseController::class, 'courseInfo']);
});
Route::group(["middleware" => "auth:api", 'prefix' => 'courses'], function () {
    Route::get('{id}/students', [CourseController::class, 'getCourseStudents']);
    Route::get('{course}/exams/{exam}', [StudentExamController::class, 'getCourseExams']);
    Route::post('{course}/exams/{exam}', [StudentExamController::class, 'submitExam']);
    Route::get('{course}/exams/{exam}/get-students-degree', [StudentExamController::class, 'studentsDegree']);
    Route::get('{course}/exams/{exam}/get-student-answers/{user}', [StudentExamController::class, 'studentAnswers']);
    Route::put('{course}/exams/{exam}/assign-grade/{user}', [StudentExamController::class, 'updateExamGrade']);
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('lessons/{lesson}', [CourseController::class, 'lessonInfo']);
    Route::get('students/courses', [CourseController::class, 'getStudentCourses']);
    Route::get('students/info',[StudentController::class,'getStudentInfo']);
    Route::get('students/answers/{id}',[StudentController::class,'getStudentAnswers']);
});

//teacher routes
Route::group(['prefix' => 'teachers'], function () {
    Route::get('/', [TeacherController::class, 'getAllTeachers']);
    Route::get('/{id}/courses', [TeacherController::class, 'getTeacherInfoAndCourses']);
    Route::group(['middleware' => ['auth:api', 'checkUserActivation', 'role:teacher,api']], function () {
        Route::group(['prefix' => 'lessons'], function () {
            Route::delete('attachments/{id}', [LessonsController::class, 'deleteLessonAttachment']);
            Route::post('{lesson}/attachments/add', [LessonsController::class, 'addLessonAttachment']);
            Route::put('{lesson}/update', [LessonsController::class, 'updateLesson']);
            Route::delete('{lesson}/delete', [LessonsController::class, 'deleteLesson']);
            Route::post('add-lesson', [LessonsController::class, 'addLesson']);
        });

        Route::get('courses', [TeacherController::class, 'getAllTeacherCourses']);
        Route::get('courses/{id}', [TeacherController::class, 'getCourseInfo']);
        Route::get('courses/{id}/units', [TeacherController::class, 'courseUnits']);
        Route::post('courses/add', [TeacherController::class, 'addCourse']);
        Route::put('courses/{course}/update', [TeacherController::class, 'updateCourse']);
        //units routes
        Route::post('courses/{course}/units/add', [UnitController::class, 'addUnits']);
        Route::put('courses/{course}/units/{unit}/update', [UnitController::class, 'updateUnit']);
        Route::delete('courses/{course}/units/{unit}/delete', [UnitController::class, 'deleteUnit']);
        Route::get('units/{id}/lessons', [TeacherController::class, 'unitLessons']);
        //exams routes
        Route::post('courses/{course}/add-exam', [ExamController::class, 'addExam']);
        Route::get('courses/{course}/exams', [ExamController::class, 'couresExams']);
        Route::post('exams/{exam}/add-questions', [ExamController::class, 'addQuestions']);
        Route::post('exams/{exam}/add-course', [ExamController::class, 'assignExamToCourse']);
        Route::post('exams/{exam}/copy-exam', [ExamController::class, 'copyExam']);
        Route::put('exams/{exam}/edit', [ExamController::class, 'updateExam']);
        Route::delete('exams/{exam}/delete', [ExamController::class, 'deleteExam']);
        Route::post('exams/{exam}/remove-questions', [ExamController::class, 'removeQuestions']);
        Route::get('exams/{exam}', [ExamController::class, 'getExam']);
        Route::get('exams', [ExamController::class, 'teacherExams']);
        //questions
        Route::get('questions/teacher-bank', [ExamController::class, 'teacherQuestionsBank']);
        Route::get('questions/public-bank', [ExamController::class, 'publicQuestionsBank']);
        Route::delete('questions/{question}', [ExamController::class, 'deleteQuestion']);
    });
});

//categories routes
Route::group([ 'prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'getCategories']);
});

//messages routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'messages'], function () {
    Route::post('/send', [NotificationController::class, 'sendMessage']);
    Route::get('/latest-notifications', [NotificationController::class, 'userNotification']);
    Route::get('/', [NotificationController::class, 'userAllMessages']);
    Route::delete('/{id}/delete', [NotificationController::class, 'deleteMessage']);
    Route::get('/teacher-chats', [NotificationController::class, 'teacherChats']);
});

//notifications routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'notifications'], function () {
    Route::get('/', [NotificationController::class, 'userNotification']);
    Route::get('/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/{id}/delete', [NotificationController::class, 'deleteNotification']);
});

//packages Routes
Route::group(['prefix' => 'packages'], function () {
    Route::get('/', [PackageController::class, 'getPackages']);
    Route::get('/popular', [PackageController::class, 'getPopularPackages']);
    Route::get('/{id}', [PackageController::class, 'show']);
});

//paymnets
Route::group(['middleware' => 'auth:api', 'prefix' => 'payments'], function () {
    Route::post('/', [PaymentController::class, 'pay']);
});
//paymnets
Route::group(['middleware' => 'auth:api', 'prefix' => 'reviews'], function () {
    Route::post('/add', [ReviewController::class, 'addReview']);
    Route::put('/{review}', [ReviewController::class, 'editReview']);
    Route::delete('/{review}', [ReviewController::class, 'deleteReview']);
});
