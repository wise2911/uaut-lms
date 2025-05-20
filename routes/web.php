<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::controller(AuthController::class)->group(function() {
    Route::get('/user/login', 'showLogin')->name('login');
    Route::post('/user/login', 'login');
    Route::get('/user/signup', 'showSignup');
    Route::post('/user/signup', 'signup');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(AdminAuthController::class)->group(function() {
    Route::get('/admin/login', 'showLogin')->name('admin.login');
    Route::post('/admin/login', 'login')->name('admin.login.post');
});

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
    });
});

Route::middleware(['auth'])->group(function() {
    Route::get('/user/home', [UserHomeController::class, 'index'])->name('user.home');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/learn', [CourseController::class, 'learn'])->name('courses.learn');
    Route::post('/courses/{courseId}/enroll', [UserHomeController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{courseId}/progress', [UserHomeController::class, 'updateProgress'])->name('courses.progress.update');
    Route::post('/courses/{course}/mark-watched', [CourseController::class, 'markWatched'])->name('courses.mark-watched');
    Route::get('/api/courses/{course}/topics', function (Course $course) {
        return response()->json($course->videos);
    })->name('courses.topics');
});

// Public course routes (no auth required)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{courseId}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
Route::get('/courses/{course}/learn', [CourseController::class, 'learn'])->name('courses.learn');
Route::post('/courses/{course}/mark-watched', [CourseController::class, 'markWatched'])->name('courses.mark-watched');