<?php

use App\Http\Controllers\VideoProgressController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/courses/{course}/topics', function (Course $course) {
        return response()->json($course->topics);
    });
    Route::post('/complete-segment', [VideoProgressController::class, 'completeSegment']);
});