<?php

use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Courses\CoursesController;
use App\Http\Controllers\FeatureContentController;
use App\Http\Controllers\Packages\PackagesController;
use App\Http\Controllers\Transactions\TransactionController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\StudentController;
use App\Http\Controllers\Users\TeacherController;
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
    Route::get('/get-package-course-counts', [App\Http\Controllers\HomeController::class, 'getPackageCourseCounts']);
    Route::get('change-lang/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
    Route::post('/formsubmit', [App\Http\Controllers\HomeController::class, 'FormSubmit'])->name('FormSubmit');
    Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index']);

    Route::group(['prefix' => 'categories'], function () {
        Route::get('all', [CategoryController::class, 'index'])->name('categories.all');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/update', [CategoryController::class, 'update'])->name('categories.update');
    });

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



    Route::group(['prefix' => 'users'], function () {
        Route::group(['prefix' => 'admins'], function () {
            Route::get('all-admins', [AdminController::class, 'allAdmins'])->name('users.admin.all');
            Route::post('block', [AdminController::class, 'blockAdmin'])->name('users.admin.block');
            Route::post('unblock', [AdminController::class, 'unblockAdmin'])->name('users.admin.unblock');
        });
        Route::group(['prefix' => 'students'], function () {
            Route::get('active', [StudentController::class, 'active'])->name('users.student.active');
            Route::get('/blocked', [StudentController::class, 'blocked'])->name('users.student.blocked');
            Route::post('/block', [StudentController::class, 'block'])->name('users.student.block');
            Route::post('/accept', [StudentController::class, 'accept'])->name('users.student.accept');
            Route::post('/reactive', [StudentController::class, 'reactive'])->name('users.student.reactive');
            Route::get('{id}/show', [StudentController::class, 'show'])->name('users.student.show');
        });
        Route::group(['prefix' => 'teachers'], function () {
            Route::get('active', [TeacherController::class, 'active'])->name('users.teacher.active');
            Route::get('pending', [TeacherController::class, 'pending'])->name('users.teacher.pending');
            Route::get('/blocked', [TeacherController::class, 'blocked'])->name('users.teacher.blocked');
            Route::post('/block', [TeacherController::class, 'block'])->name('users.teacher.block');
            Route::post('/accept', [TeacherController::class, 'accept'])->name('users.teacher.accept');
            Route::post('/reactive', [TeacherController::class, 'reactive'])->name('users.teacher.reactive');
            Route::get('{id}/show', [TeacherController::class, 'show'])->name('users.teacher.show');
            Route::get('/download-certificate/{id}', [TeacherController::class, 'downloadCertificate'])->name('users.teacher.certificate');
        });
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('orders', [TransactionController::class, 'orders'])->name('transactions.orders');
        Route::get('teachers/revenue', [TransactionController::class, 'teacherRevenue'])->name('transactions.teachers.revenue');
        Route::get('teachers/{id}/all-revenue', [TransactionController::class, 'teacherAllRevenue'])->name('transactions.teacher.all.revenue');
        Route::get('courses/best-seller', [TransactionController::class, 'bestSellerCourses'])->name('transactions.bestSeller.courses');
        Route::get('packages/best-seller', [TransactionController::class, 'bestSellerPackages'])->name('transactions.bestSeller.packages');
    });
    //feature content
    Route::group(['prefix' => 'feature-content'], function () {
        Route::get('all', [FeatureContentController::class, 'index'])->name('featureContent.all');
        Route::get('create', [FeatureContentController::class, 'create'])->name('featureContent.create');
        Route::post('store', [FeatureContentController::class, 'store'])->name('featureContent.store');
        Route::delete('delete', [FeatureContentController::class, 'delete'])->name('featureContent.delete');
        Route::get('get-courses', [FeatureContentController::class, 'getCourses'])->name('featureContent.getCourses');
        Route::get('get-packages', [FeatureContentController::class, 'getPackages'])->name('featureContent.getPackages');
    });

    //contact us
    Route::group(['prefix' => 'contact-us'], function () {
        Route::get('all', [ContactUsController::class, 'index'])->name('contactUs.all');
    });



});
