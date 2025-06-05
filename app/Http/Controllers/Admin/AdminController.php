<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRating;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function ratings()
    {
        $courseRatings = CourseRating::with(['user', 'course'])->paginate(10);
        return view('admin.rating', compact('courseRatings'));
    }
}
?>