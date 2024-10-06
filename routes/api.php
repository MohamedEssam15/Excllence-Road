<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\Auth\AuthController;
use App\Http\Controllers\ApiControllers\CategoryController;
use App\Http\Controllers\ApiControllers\CourseController;
use App\Http\Controllers\ApiControllers\PackageController;
use App\Http\Controllers\ApiControllers\TeacherController;
use App\Http\Controllers\ApiControllers\PaymentController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\ExamController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\LessonsController;
use App\Http\Controllers\ApiControllers\TeacherPanalControllers\UnitController;

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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('teacher-register', [AuthController::class, 'teacherRegister']);
    Route::post('password/email', [AuthController::class, 'sendResetCode']);
    Route::post('password/reset', [AuthController::class, 'resetWithCode']);
    Route::post('/email/send-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
    Route::delete('/delete', [AuthController::class, 'delete']);
});

Route::group(['middleware' => 'guest:api', 'prefix' => 'courses'], function () {
    Route::get('popular', [CourseController::class, 'getPopularCourses']);
    Route::get('teacher/{id}', [CourseController::class, 'getTeacherCourses']);
    Route::get('search', [CourseController::class, 'courseSearch']);
    Route::get('/', [CourseController::class, 'coursesFilters']);
    Route::get('/levels', [CourseController::class, 'getCourseLevels']);
    Route::get('/course-info/{id}', [CourseController::class, 'guestCourseInfo']);
});

//teacher routes
Route::group(['prefix' => 'teachers'], function () {
    Route::get('/', [TeacherController::class, 'getAllTeachers']);
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
        Route::post('courses/add', [TeacherController::class, 'addCourse']);
        Route::put('courses/{course}/update', [TeacherController::class, 'updateCourse']);
        //units routes
        Route::post('courses/{course}/units/add', [UnitController::class, 'addUnits']);
        Route::put('courses/{course}/units/{unit}/update', [UnitController::class, 'updateUnit']);
        Route::delete('courses/{course}/units/{unit}/delete', [UnitController::class, 'deleteUnit']);
        //exams routes
        Route::post('courses/{course}/add-exam', [ExamController::class, 'addExam']);
        Route::post('exams/{exam}/add-questions', [ExamController::class, 'addQuestions']);
        Route::put('exams/{exam}/edit', [ExamController::class, 'updateExam']);
        Route::delete('exams/{exam}/delete', [ExamController::class, 'deleteExam']);
        Route::post('exams/{exam}/remove-questions', [ExamController::class, 'removeQuestions']);
        Route::get('exams/{exam}', [ExamController::class, 'getExam']);
        //questions
        Route::get('questions/teacher-bank', [ExamController::class, 'teacherQuestionsBank']);
        Route::get('questions/public-bank', [ExamController::class, 'publicQuestionsBank']);
    });
});

//categories routes
Route::group(['middleware' => 'guest:api', 'prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'getCategories']);
});

//packages Routes
Route::group(['middleware' => 'guest:api', 'prefix' => 'packages'], function () {
    Route::get('/', [PackageController::class, 'getPackages']);
    Route::get('/popular', [PackageController::class, 'getPopularPackages']);
    Route::get('/{id}', [PackageController::class, 'show']);
});

//paymnets
Route::group(['middleware' => 'auth:api', 'prefix' => 'payments'], function () {
    Route::post('/', [PaymentController::class, 'pay']);
});
