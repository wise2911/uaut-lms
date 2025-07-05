<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// User Authentication Routes
Route::controller(AuthController::class)->group(function() {
    Route::get('/user/login', 'showLogin')->name('login');
    Route::post('/user/login', 'login');
    Route::get('/user/signup', 'showSignup');
    Route::post('/user/signup', 'signup');
    Route::post('/logout', 'logout')->name('logout');
});

// Admin Authentication Routes
Route::controller(AdminAuthController::class)->group(function() {
    Route::get('/admin/login', 'showLogin')->name('admin.login');
    Route::post('/admin/login', 'login')->name('admin.login.post');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    })->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
        Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
        Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
        Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
        Route::post('/videos/{video}/segments', [VideoController::class, 'storeSegment'])->name('segments.store');
        Route::delete('/videos/{video}/segments/{segment}', [VideoController::class, 'destroySegment'])->name('segments.destroy');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::delete('/users/{course}/{user}', [AdminUserController::class, 'unenroll'])->name('users.unenroll');
        Route::delete('/users/{user}', [AdminUserController::class, 'delete'])->name('users.delete');
        Route::get('/ratings', [AdminController::class, 'ratings'])->name('ratings');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    });
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function() {
    Route::get('/user/home', [UserHomeController::class, 'index'])->name('user.home');
    Route::post('/courses/{course}/enroll', [UserHomeController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course}/enroll/success', [UserHomeController::class, 'enrollSuccess'])->name('courses.payment.success');
    Route::get('/courses/{course}/enroll/cancel', [UserHomeController::class, 'enrollCancel'])->name('courses.payment.cancel');
    Route::get('/courses/{course}/learn', [CourseController::class, 'learn'])->name('courses.learn');
    Route::post('/courses/{course}/mark-watched', [CourseController::class, 'markWatched'])->name('courses.mark-watched');
    Route::get('/courses/{course}/certificate', [CourseController::class, 'certificate'])->name('courses.certificate');
    Route::get('/courses/{course}/rate', [CourseController::class, 'showRatingForm'])->name('courses.rate');
    Route::post('/courses/{course}/rate', [CourseController::class, 'storeRating'])->name('courses.rate.store');
    Route::get('/api/courses/{course}/topics', function (Course $course) {
        return response()->json($course->video);
    })->name('courses.topics');
});

// Public Course Routes
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');