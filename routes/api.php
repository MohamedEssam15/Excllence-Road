<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\Auth\AuthController;
use App\Http\Controllers\ApiControllers\CategoryController;
use App\Http\Controllers\ApiControllers\CourseController;
use App\Http\Controllers\ApiControllers\PackageController;
use App\Http\Controllers\ApiControllers\TeacherController;
use App\Http\Controllers\PaymentController;

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

Route::group(['middleware' => ['api'],'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('teacher-register', [AuthController::class,'teacherRegister']);
    Route::post('password/email', [AuthController::class, 'sendResetCode']);
    Route::post('password/reset', [AuthController::class, 'resetWithCode']);
    Route::post('/email/send-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
    Route::delete('/delete', [AuthController::class,'delete']);
});

Route::group(['middleware' => 'guest:api','prefix'=>'courses'], function () {
    Route::get('popular',[CourseController::class,'getPopularCourses']);
    Route::get('teacher/{id}',[CourseController::class,'getTeacherCourses']);
    Route::get('search',[CourseController::class,'courseSearch']);
    Route::get('/',[CourseController::class,'coursesFilters']);
    Route::get('/levels',[CourseController::class,'getCourseLevels']);
    Route::get('/course-info/{id}',[CourseController::class,'guestCourseInfo']);
});
// Route::get('teachers/courses',[TeacherController::class,'getAllTeacherCourses']);
//teacher routes
Route::group(['prefix'=>'teachers'], function () {
    Route::get('/',[TeacherController::class,'getAllTeachers']);
    Route::group(['middleware'=>['auth:api','checkUserActivation']], function () {
        Route::get('courses',[TeacherController::class,'getAllTeacherCourses']);
    });
});

//categories routes
Route::group(['middleware' => 'guest:api','prefix'=>'categories'], function () {
    Route::get('/',[CategoryController::class,'getCategories']);
});

//packages Routes
Route::group(['middleware' => 'guest:api','prefix'=>'packages'], function () {
    Route::get('/',[PackageController::class,'getPackages']);
    Route::get('/popular',[PackageController::class,'getPopularPackages']);
    Route::get('/{id}',[PackageController::class,'show']);
});

//paymnets
Route::group(['middleware' => 'auth:api','prefix'=>'paymnets'], function () {
    Route::post('/',[PaymentController::class,'pay']);
    Route::get('/popular',[PackageController::class,'getPopularPackages']);
    Route::get('/{id}',[PackageController::class,'show']);
});
