<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRating;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function ratings(Request $request)
    {
        $query = CourseRating::with('user', 'course');
        
        // Handle search
        if ($search = $request->input('search')) {
            $query->whereHas('course', fn($q) => $q->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($q) => $q->where('full_name', 'like', "%{$search}%"))
                  ->orWhere('feedback', 'like', "%{$search}%");
        }

        // Handle department filter
        if ($department = $request->input('department')) {
            $query->whereHas('course', fn($q) => $q->where('department', $department));
        }

        // Paginate ratings
        $ratings = $query->paginate(10);

        // Prepare JSON for JavaScript
        $ratingsJson = $ratings->getCollection()->map(function ($rating) {
            return [
                'id' => $rating->id,
                'user' => ['full_name' => $rating->user->full_name],
                'course' => ['title' => $rating->course->title],
                'responses' => $rating->responses,
                'feedback' => $rating->feedback,
                'created_at' => $rating->created_at->toDateTimeString(),
            ];
        })->toJson();

        // Get departments for filter
        $departments = Course::distinct('department')->pluck('department');

        return view('admin.rating', compact('ratings', 'search', 'department', 'departments', 'ratingsJson'));
    }
}