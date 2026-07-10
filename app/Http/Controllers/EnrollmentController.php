<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Get enrollments with course and eager load lessons and progress
        $enrollments = Enrollment::with(['course.lessons', 'course.quizzes'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Calculate progress percentage for each enrolled course
        foreach ($enrollments as $enrollment) {
            $totalLessons = $enrollment->course->lessons->count();
            if ($totalLessons > 0) {
                $completedLessons = $user->progressRecords()
                    ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                    ->count();
                
                $enrollment->progress_percentage = round(($completedLessons / $totalLessons) * 100);
                $enrollment->completed_lessons = $completedLessons;
                $enrollment->total_lessons = $totalLessons;
            } else {
                $enrollment->progress_percentage = 0;
                $enrollment->completed_lessons = 0;
                $enrollment->total_lessons = 0;
            }
        }

        return view('enrollment.index', [
            'title'       => 'Kursus Saya',
            'enrollments' => $enrollments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        // Check if already enrolled
        $exists = Enrollment::where('user_id', $user->id)
            ->where('course_id', $validate['course_id'])
            ->exists();

        if ($exists) {
            return back()->withError('Anda sudah terdaftar di kursus ini.');
        }

        Enrollment::create([
            'user_id'     => $user->id,
            'course_id'   => $validate['course_id'],
            'enrolled_at' => now(),
        ]);

        return back()->withSuccess('Berhasil mendaftar kursus!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $user = Auth::user();

        if ($enrollment->user_id !== $user->id) {
            abort(403);
        }

        $enrollment->delete();

        return back()->withSuccess('Berhasil berhenti dari kursus.');
    }
}
