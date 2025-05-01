<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Models\Course;

Route::get('/', function () {
    return view('welcome');
});

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

    Route::middleware('auth')->group(function () {
        Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
        Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::delete('/users/{course}/{user}', [AdminUserController::class, 'unenroll'])->name('users.unenroll');
        Route::delete('/users/{user}', [AdminUserController::class, 'delete'])->name('users.delete');
    });
});

// Authenticated User Routes
Route::middleware('auth')->group(function() {
    Route::get('/user/home', [CourseController::class, 'index'])->name('user.home');
    Route::post('/enroll/{courseId}', [CourseController::class, 'enroll'])->name('enroll');
    Route::post('/courses/{courseId}/progress', [CourseController::class, 'updateProgress'])->name('courses.progress.update');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
});

// API Route for Fetching Topics
Route::middleware('auth')->group(function () {
    Route::get('/api/courses/{course}/topics', function (Course $course) {
        return response()->json($course->topics);
    })->name('courses.topics');
});