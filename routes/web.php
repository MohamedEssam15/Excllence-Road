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
    // Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index']);

    Route::group(['prefix' => 'courses'], function () {
        Route::get('/', [CoursesController::class, 'index'])->name('courses.all');
        Route::get('/{id}/show', [CoursesController::class, 'show'])->name('courses.info');
        Route::get('/{id}/edit', [CoursesController::class, 'edit'])->name('courses.edit');
        Route::get('/add', [CoursesController::class, 'create'])->name('courses.add');
    });
});
