<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function ratings(Request $request)
    {
        $search = $request->query('search');
        $department = $request->query('department');
        $departments = Course::distinct()->pluck('department')->filter()->values();

        $query = CourseRating::with(['user', 'course']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('course', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                })
                ->orWhere('feedback', 'LIKE', "%{$search}%");
            });
        }

        if ($department) {
            $query->whereHas('course', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $ratings = $query->latest()->paginate(10);
        $ratingsJson = $ratings->toJson();

        return view('admin.rating', compact('ratings', 'ratingsJson', 'search', 'department', 'departments'));
    }

    public function payments(Request $request)
    {
        $search = $request->input('search');
        $department = $request->input('department');

        $query = Payment::with(['user', 'course'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('course', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhere('payment_id', 'like', "%{$search}%");
            })
            ->when($department, function ($query, $department) {
                return $query->whereHas('course', function ($q) use ($department) {
                    $q->where('department', $department);
                });
            })
            ->latest();

        $payments = $query->paginate(10);
        $departments = Course::distinct()->pluck('department')->sort()->values();

        return view('admin.payments', compact('payments', 'departments', 'search', 'department'));
    }
}