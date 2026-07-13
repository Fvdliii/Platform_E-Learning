<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $quiz = Quiz::findOrFail($request->quiz_id);
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            abort(403);
        }

        // Batasi maksimal 10 soal per kuis
        if ($quiz->questions()->count() >= 10) {
            return redirect()->route('quiz.show', $quiz)
                ->withError('Kuis ini sudah memiliki 10 soal (batas maksimum). Hapus soal yang ada jika ingin menggantinya.');
        }

        return view('question.create', [
            'title' => 'Tambah Pertanyaan',
            'quiz'  => $quiz,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quiz = Quiz::findOrFail($request->quiz_id);
        $user = Auth::user();

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses.');
        }

        // Batasi maksimal 10 soal per kuis
        if ($quiz->questions()->count() >= 10) {
            return redirect()->route('quiz.show', $quiz)
                ->withError('Kuis ini sudah memiliki 10 soal (batas maksimum).');
        }

        $validate = $request->validate([
            'quiz_id'        => 'required|exists:quizzes,id',
            'text'           => 'required|string',
            'answers'        => 'required|array|size:4',
            'answers.*.text' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
        ], [
            'answers.*.text.required' => 'Setiap opsi jawaban wajib diisi',
            'correct_answer.required' => 'Jawaban benar wajib dipilih',
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create([
                'quiz_id' => $validate['quiz_id'],
                'text'    => $validate['text'],
            ]);

            foreach ($validate['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'text'       => $answerData['text'],
                    'is_correct' => ($index == $validate['correct_answer']),
                ]);
            }

            DB::commit();
            return to_route('quiz.show', $quiz)->withSuccess('Pertanyaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menambahkan pertanyaan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $user = Auth::user();
        $quiz = $question->quiz;

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            abort(403);
        }

        $question->load('answers');

        return view('question.edit', [
            'title'    => 'Edit Pertanyaan',
            'question' => $question,
            'quiz'     => $quiz,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $user = Auth::user();
        $quiz = $question->quiz;

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses.');
        }

        $validate = $request->validate([
            'text'           => 'required|string',
            'answers'        => 'required|array|size:4',
            'answers.*.id'   => 'required|exists:answers,id',
            'answers.*.text' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
        ], [
            'answers.*.text.required' => 'Setiap opsi jawaban wajib diisi',
            'correct_answer.required' => 'Jawaban benar wajib dipilih',
        ]);

        DB::beginTransaction();
        try {
            $question->update([
                'text' => $validate['text'],
            ]);

            foreach ($validate['answers'] as $index => $answerData) {
                $answer = $question->answers()->where('id', $answerData['id'])->first();
                if ($answer) {
                    $answer->update([
                        'text'       => $answerData['text'],
                        'is_correct' => ($index == $validate['correct_answer']),
                    ]);
                }
            }

            DB::commit();
            return to_route('quiz.show', $quiz)->withSuccess('Pertanyaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal memperbarui pertanyaan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $user = Auth::user();
        $quiz = $question->quiz;

        if ($user->role === 'instructor' && $quiz->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses.');
        }

        $question->delete();

        return back()->withSuccess('Pertanyaan berhasil dihapus.');
    }
}
