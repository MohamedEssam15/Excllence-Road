<?php

use App\Http\Controllers\TeachersControllers\CoursesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['verify' => true]);


Route::group(['middleware' => ['auth:web', 'verified']], function () {
    Route::get('dashboard/{any}', [App\Http\Controllers\HomeController::class, 'index']);
    //Language Translation
    Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);
    Route::get('change-lang/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
    Route::post('/formsubmit', [App\Http\Controllers\HomeController::class, 'FormSubmit'])->name('FormSubmit');

    Route::group(['prefix' => 'courses'], function () {
        Route::get('/', [CoursesController::class, 'index'])->name('courses.all');
        Route::get('/{id}/show', [CoursesController::class, 'show'])->name('courses.info');
        Route::get('/{id}/edit', [CoursesController::class, 'edit'])->name('courses.edit');
        Route::get('/add', [CoursesController::class, 'create'])->name('courses.add');
    });
});



