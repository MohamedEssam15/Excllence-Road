<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\Auth\AuthController;
use App\Http\Controllers\ApiControllers\CategoryController;
use App\Http\Controllers\ApiControllers\CourseController;
use App\Http\Controllers\ApiControllers\PackageController;
use App\Http\Controllers\ApiControllers\TeacherController;
use Illuminate\Support\Facades\URL;

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

Route::group(['middleware' => ['api'],'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('password/email', [AuthController::class, 'sendResetCode']);
    Route::post('password/reset', [AuthController::class, 'resetWithCode']);
    Route::post('/email/send-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'guest:api','prefix'=>'courses'], function () {
    Route::get('popular',[CourseController::class,'getPopularCourses']);
    Route::get('teacher/{id}',[CourseController::class,'getTeacherCourses']);
    Route::get('search',[CourseController::class,'courseSearch']);
    Route::get('/',[CourseController::class,'coursesFilters']);
    Route::get('/levels',[CourseController::class,'getCourseLevels']);
});

//teacher routes
Route::group(['middleware' => 'guest:api','prefix'=>'teachers'], function () {
    Route::get('/',[TeacherController::class,'getAllTeachers']);

});

//categories routes
Route::group(['middleware' => 'guest:api','prefix'=>'categories'], function () {
    Route::get('/',[CategoryController::class,'getCategories']);
});

//packages Routes
Route::group(['middleware' => 'guest:api','prefix'=>'packages'], function () {
    Route::get('/',[PackageController::class,'getPackages']);
    Route::get('/{id}',[PackageController::class,'show']);
});
