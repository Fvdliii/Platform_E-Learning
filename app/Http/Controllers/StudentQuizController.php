<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentQuizController extends Controller
{
    /**
     * Show the quiz questions to the student.
     */
    public function show(Quiz $quiz)
    {
        $user = Auth::user();

        // Check enrollment
        $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('course.show', $quiz->course)
                ->withError('Anda harus mendaftar ke kursus ini untuk mengerjakan kuis.');
        }

        // Check if student already passed the quiz
        $previousAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')
            ->first();

        if ($previousAttempt && $previousAttempt->passed) {
            return redirect()->route('student.quiz.result', $previousAttempt)
                ->withInfo('Anda telah lulus kuis ini sebelumnya.');
        }

        $quiz->load(['questions.answers']);

        // Pastikan kuis sudah memiliki tepat 10 soal
        if ($quiz->questions->count() < 10) {
            return redirect()->route('course.show', $quiz->course)
                ->withError('Kuis ini belum dapat dikerjakan. Instruktur belum melengkapi 10 soal yang diperlukan.');
        }

        return view('quiz.take', [
            'title' => 'Kerjakan Kuis: ' . $quiz->title,
            'quiz'  => $quiz,
        ]);
    }

    /**
     * Submit and process quiz answers.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|exists:answers,id',
        ]);

        $quiz->load('questions.answers');
        $correctAnswersCount = 0;
        $answerResults = []; // store per-question result for saving

        foreach ($quiz->questions as $question) {
            if (isset($validate['answers'][$question->id])) {
                $submittedAnswerId = $validate['answers'][$question->id];
                $isCorrect = $question->answers->where('id', $submittedAnswerId)->where('is_correct', true)->isNotEmpty();
                if ($isCorrect) {
                    $correctAnswersCount++;
                }
                $answerResults[] = [
                    'question_id' => $question->id,
                    'answer_id'   => $submittedAnswerId,
                    'is_correct'  => $isCorrect,
                ];
            }
        }

        // Nilai = jumlah benar × 10 (karena wajib 10 soal, skor range 0-100)
        $score = $correctAnswersCount * 10;
        $passed = $score >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id'       => $user->id,
            'quiz_id'       => $quiz->id,
            'score'         => $score,
            'passed'        => $passed,
            'correct_count' => $correctAnswersCount,
        ]);

        // Simpan setiap jawaban yang dipilih siswa
        foreach ($answerResults as $result) {
            $attempt->attemptAnswers()->create([
                'question_id' => $result['question_id'],
                'answer_id'   => $result['answer_id'],
                'is_correct'  => $result['is_correct'],
            ]);
        }

        $isGenerated = false;
        if ($passed) {
            $isGenerated = \App\Models\Certificate::checkAndGenerate($user->id, $quiz->course_id);
        }

        $msg = 'Kuis selesai! Berikut adalah hasil Anda.';
        if ($isGenerated) {
            $msg .= ' Selamat! Anda telah menyelesaikan seluruh syarat dan mendapatkan Sertifikat Kelulusan!';
        }

        return redirect()->route('student.quiz.result', $attempt)
            ->withSuccess($msg);
    }

    /**
     * Show the quiz result.
     */
    public function result(QuizAttempt $attempt)
    {
        $user = Auth::user();

        if ($attempt->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $attempt->load('quiz.course');

        return view('quiz.result', [
            'title'   => 'Hasil Kuis',
            'attempt' => $attempt,
        ]);
    }
}
