<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $quizzes = Quiz::with('course')->latest()->get();
        } else {
            $quizzes = Quiz::whereHas('course', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->with('course')->latest()->get();
        }

        return view('quiz.index', [
            'title'   => 'Manajemen Kuis',
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::all()
            : Course::where('instructor_id', $user->id)->get();

        return view('quiz.create', [
            'title'   => 'Tambah Kuis Baru',
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'course_id'     => 'required|exists:courses,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        if ($user->role === 'instructor') {
            $course = Course::findOrFail($validate['course_id']);
            if ($course->instructor_id !== $user->id) {
                return back()->withError('Anda tidak memiliki akses untuk menambah kuis di kursus ini.');
            }
        }

        Quiz::create($validate);

        return to_route('quiz.index')->withSuccess('Kuis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            abort(403);
        }

        $quiz->load('questions.answers');

        return view('quiz.show', [
            'title' => 'Detail Kuis: ' . $quiz->title,
            'quiz'  => $quiz,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            abort(403);
        }

        $courses = $user->role === 'admin'
            ? Course::all()
            : Course::where('instructor_id', $user->id)->get();

        return view('quiz.edit', [
            'title'   => 'Edit Kuis',
            'quiz'    => $quiz,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses untuk mengubah kuis ini.');
        }

        $validate = $request->validate([
            'course_id'     => 'required|exists:courses,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        if ($user->role === 'instructor') {
            $course = Course::findOrFail($validate['course_id']);
            if ($course->instructor_id !== $user->id) {
                return back()->withError('Anda tidak memiliki akses ke kursus yang dipilih.');
            }
        }

        $quiz->update($validate);

        return to_route('quiz.index')->withSuccess('Kuis berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses untuk menghapus kuis ini.');
        }

        $quiz->delete();

        return to_route('quiz.index')->withSuccess('Kuis berhasil dihapus.');
    }
}
