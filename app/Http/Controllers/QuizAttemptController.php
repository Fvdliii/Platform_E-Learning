<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    /**
     * Add / update instructor note on a quiz attempt.
     */
    public function addNote(Request $request, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Only allow instructor of the course or admin
        if ($user->role === 'instructor' && $attempt->quiz->course->instructor_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $attempt->update(['note' => $request->note]);

        return back()->withSuccess('Catatan berhasil disimpan.');
    }

    /**
     * Reset (delete) a student's quiz attempt so they can retake the quiz.
     */
    public function reset(QuizAttempt $attempt)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $attempt->quiz->course->instructor_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $attempt->delete();

        return back()->withSuccess('Percobaan kuis siswa berhasil direset. Siswa dapat mengerjakan ulang.');
    }

    /**
     * Show the detailed answers submitted by a student.
     */
    public function answers(QuizAttempt $attempt)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $attempt->quiz->course->instructor_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $attempt->load([
            'user',
            'quiz.questions.answers',
            'attemptAnswers.question',
            'attemptAnswers.answer',
        ]);

        return view('quiz.attempt_detail', [
            'title'   => 'Jawaban Siswa: ' . $attempt->user->name,
            'attempt' => $attempt,
        ]);
    }
}
