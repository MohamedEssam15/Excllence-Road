<?php

use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Courses\CoursesController;
use App\Http\Controllers\FeatureContentController;
use App\Http\Controllers\Packages\PackagesController;
use App\Http\Controllers\Transactions\TransactionController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\StudentController;
use App\Http\Controllers\Users\TeacherController;
use App\Models\Course;
use App\Models\Package;
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

Route::get('/tess',function (){
    dd([Package::find(1)->orders,Package::find(1)->haveOrders]);
});

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
        Route::get('all', [CategoryController::class, 'index'])->name('categories.all')->middleware('permission:categories');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:add-category');
        Route::put('/update', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit-category');
    });

    Route::group(['prefix' => 'courses'], function () {
        Route::get('active', [CoursesController::class, 'activeCourses'])->name('courses.active')->middleware('permission:active-courses');
        Route::get('pending', [CoursesController::class, 'pendingCourses'])->name('courses.pending')->middleware('permission:pending-courses');
        Route::get('/expired', [CoursesController::class, 'expiredCourses'])->name('courses.expired')->middleware('permission:expired-courses');
        Route::get('/cancelled', [CoursesController::class, 'cancelledCourses'])->name('courses.cancelled')->middleware('permission:rejected-courses');
        Route::delete('/delete-course', [CoursesController::class, 'deleteCourse'])->name('courses.delete')->middleware('permission:delete-course');
        Route::get('/{id}/show', [CoursesController::class, 'show'])->name('courses.info')->middleware('permission:course-info');

        Route::post('/accept-course', [CoursesController::class, 'acceptCourse'])->middleware('permission:accept-reject-courses');
        Route::post('/add-discount', [CoursesController::class, 'addDiscount'])->middleware('permission:discount');
        Route::post('/remove-discount', [CoursesController::class, 'destroyDiscount'])->middleware('permission:discount');
        Route::post('/modify-course', [CoursesController::class, 'modifyCourse'])->middleware('permission:edit-course');
        Route::post('/cancel-course', [CoursesController::class, 'cancelCourse'])->middleware('permission:accept-reject-courses');
        Route::post('/return-course-to-pending', [CoursesController::class, 'returnToPending'])->middleware('permission:return-course');
    });

    Route::group(['prefix' => 'packages'], function () {
        Route::get('active', [PackagesController::class, 'activePackages'])->name('packages.active')->middleware('permission:active-packages');
        Route::get('in-progress', [PackagesController::class, 'inProgressPackages'])->name('packages.in-progress')->middleware('permission:inprogress-packages');
        Route::get('/expired', [PackagesController::class, 'expiredPackages'])->name('packages.expired')->middleware('permission:expired-packages');
        Route::get('/{package}/show', [PackagesController::class, 'show'])->name('packages.info')->middleware('permission:show-package');
        Route::get('/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit')->middleware('permission:edit-package');
        Route::put('/{package}/update', [PackagesController::class, 'update'])->name('packages.update')->middleware('permission:edit-package');
        Route::get('/create', [PackagesController::class, 'create'])->name('packages.create')->middleware('permission:add-package');
        Route::post('/store', [PackagesController::class, 'store'])->name('packages.store')->middleware('permission:add-package');
        Route::get('/get-categories', [PackagesController::class, 'getCategories'])->name('categories.get');
        Route::get('/get-courses/{categoryId}', [PackagesController::class, 'getCoursesByCategoryId'])->name('courses.getbycategory');
        Route::post('/modify-course', [PackagesController::class, 'modifyPackage']);
        Route::post('/cancel-course', [PackagesController::class, 'cancelPackage']);
        Route::post('/return-course-to-pending', [PackagesController::class, 'returnToPending']);
        Route::post('/add-discount', [PackagesController::class, 'addDiscount'])->middleware('permission:discount');
        Route::post('/remove-discount', [PackagesController::class, 'destroyDiscount'])->middleware('permission:discount');
        Route::delete('/delete-package', [PackagesController::class, 'deletePackage'])->middleware('permission:delete-package');
    });



    Route::group(['prefix' => 'users'], function () {
        Route::group(['prefix' => 'admins', 'middleware' => ['role:super-admin']], function () {
            Route::get('all-admins', [AdminController::class, 'allAdmins'])->name('users.admin.all');
            Route::get('create', [AdminController::class, 'create'])->name('users.admin.create');
            Route::post('store', [AdminController::class, 'store'])->name('users.admin.store');
            Route::get('{id}/edit', [AdminController::class, 'edit'])->name('users.admin.edit');
            Route::put('{id}/update', [AdminController::class, 'update'])->name('users.admin.update');
            Route::post('block', [AdminController::class, 'blockAdmin'])->name('users.admin.block');
            Route::post('unblock', [AdminController::class, 'unblockAdmin'])->name('users.admin.unblock');
        });
        Route::group(['prefix' => 'students'], function () {
            Route::get('active', [StudentController::class, 'active'])->name('users.student.active')->middleware('permission:active-students');
            Route::get('/blocked', [StudentController::class, 'blocked'])->name('users.student.blocked')->middleware('permission:blocked-students');
            Route::post('/block', [StudentController::class, 'block'])->name('users.student.block')->middleware('permission:block-unblock-student');
            Route::post('/reactive', [StudentController::class, 'reactive'])->name('users.student.reactive')->middleware('permission:block-unblock-student');
            Route::post('/add-free-course-or-package', [StudentController::class, 'addFreeCourseOrPackage'])->name('users.student.addFreeCourseOrPackage')->middleware('permission:add-course-to-student');
            Route::get('/get-courses-or-packages/{type}', [StudentController::class, 'getCoursesOrPackages'])->name('users.student.getCoursesOrPackages');
        });
        Route::group(['prefix' => 'teachers'], function () {
            Route::get('active', [TeacherController::class, 'active'])->name('users.teacher.active')->middleware('permission:active-teachers');
            Route::get('pending', [TeacherController::class, 'pending'])->name('users.teacher.pending')->middleware('permission:pending-teachers');
            Route::get('/blocked', [TeacherController::class, 'blocked'])->name('users.teacher.blocked')->middleware('permission:blocked-teachers');
            Route::post('/block', [TeacherController::class, 'block'])->name('users.teacher.block')->middleware('permission:block-unblock-teacher');
            Route::post('/accept', [TeacherController::class, 'accept'])->name('users.teacher.accept')->middleware('permission:accept-reject-teacher');
            Route::post('/reactive', [TeacherController::class, 'reactive'])->name('users.teacher.reactive')->middleware('permission:block-unblock-teacher');
            Route::get('{id}/show', [TeacherController::class, 'show'])->name('users.teacher.show')->middleware('permission:show-teacher');
            Route::get('/download-certificate/{id}', [TeacherController::class, 'downloadCertificate'])->name('users.teacher.certificate');
        });
    });

    Route::group(['prefix' => 'transactions', 'middleware' => ['permission:transactions']], function () {
        Route::get('orders', [TransactionController::class, 'orders'])->name('transactions.orders')->middleware('permission:orders');
        Route::get('teachers/revenue', [TransactionController::class, 'teacherRevenue'])->name('transactions.teachers.revenue')->middleware('permission:teacher-revenue');
        Route::get('teachers/{id}/all-revenue', [TransactionController::class, 'teacherAllRevenue'])->name('transactions.teacher.all.revenue')->middleware('permission:teacher-revenue');
        Route::get('courses/best-seller', [TransactionController::class, 'bestSellerCourses'])->name('transactions.bestSeller.courses')->middleware('permission:top-seller');
        Route::get('packages/best-seller', [TransactionController::class, 'bestSellerPackages'])->name('transactions.bestSeller.packages')->middleware('permission:top-seller');
    });
    //feature content
    Route::group(['prefix' => 'feature-content', 'middleware' => ['permission:feature-content']], function () {
        Route::get('all', [FeatureContentController::class, 'index'])->name('featureContent.all')->middleware('permission:feature-content');
        Route::get('create', [FeatureContentController::class, 'create'])->name('featureContent.create')->middleware('permission:add-feature-content');
        Route::post('store', [FeatureContentController::class, 'store'])->name('featureContent.store')->middleware('permission:add-feature-content');
        Route::delete('delete', [FeatureContentController::class, 'delete'])->name('featureContent.delete')->middleware('permission:delete-feature-content');
        Route::get('get-courses', [FeatureContentController::class, 'getCourses'])->name('featureContent.getCourses');
        Route::get('get-packages', [FeatureContentController::class, 'getPackages'])->name('featureContent.getPackages');
    });
    Route::get('/notifications/{id}/redirect', [AdminNotificationController::class, 'deleteAndRedirect'])->name('notifications.deleteRedirect');
    //contact us
    Route::group(['prefix' => 'contact-us', 'middleware' => ['permission:contact-us']], function () {
        Route::get('all', [ContactUsController::class, 'index'])->name('contactUs.all');
    });
});
