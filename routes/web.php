<?php

use App\Http\Controllers\Courses\CoursesController;
use App\Http\Controllers\Packages\PackagesController;
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

require __DIR__ . '/auth.php';


Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect('/home'); // Redirect authenticated users to home
    }
    return redirect('/login'); // Redirect unauthenticated users to login
});

Route::middleware('auth:web')->group(function () {

    //Language Translation
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'root']);
    Route::get('change-lang/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
    Route::post('/formsubmit', [App\Http\Controllers\HomeController::class, 'FormSubmit'])->name('FormSubmit');
    Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index']);


    Route::group(['prefix' => 'courses'], function () {
        Route::get('active', [CoursesController::class, 'activeCourses'])->name('courses.active');
        Route::get('pending', [CoursesController::class, 'pendingCourses'])->name('courses.pending');
        Route::get('/expired', [CoursesController::class, 'expiredCourses'])->name('courses.expired');
        Route::get('/cancelled', [CoursesController::class, 'cancelledCourses'])->name('courses.cancelled');
        Route::get('/{id}/show', [CoursesController::class, 'show'])->name('courses.info');

        Route::post('/accept-course', [CoursesController::class, 'acceptCourse']);
        Route::post('/modify-course', [CoursesController::class, 'modifyCourse']);
        Route::post('/cancel-course', [CoursesController::class, 'cancelCourse']);
        Route::post('/return-course-to-pending', [CoursesController::class, 'returnToPending']);
    });

    Route::group(['prefix' => 'packages'], function () {
        Route::get('active', [PackagesController::class, 'activePackages'])->name('packages.active');
        Route::get('/expired', [PackagesController::class, 'expiredPackages'])->name('packages.expired');
        Route::get('/cancelled', [PackagesController::class, 'cancelledPackages'])->name('packages.cancelled');
        Route::get('/{package}/show', [PackagesController::class, 'show'])->name('packages.info');
        Route::get('/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
        Route::put('/{package}/update', [PackagesController::class, 'update'])->name('packages.update');
        Route::get('/create', [PackagesController::class, 'create'])->name('packages.create');
        Route::post('/store', [PackagesController::class, 'store'])->name('packages.store');
        Route::get('/get-categories', [PackagesController::class, 'getCategories'])->name('categories.get');
        Route::get('/get-courses/{categoryId}', [PackagesController::class, 'getCoursesByCategoryId'])->name('courses.getbycategory');
        Route::post('/modify-course', [PackagesController::class, 'modifyPackage']);
        Route::post('/cancel-course', [PackagesController::class, 'cancelPackage']);
        Route::post('/return-course-to-pending', [PackagesController::class, 'returnToPending']);
    });
});
