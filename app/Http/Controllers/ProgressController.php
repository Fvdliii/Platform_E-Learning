<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        // Check if already completed
        $exists = Progress::where('user_id', $user->id)
            ->where('lesson_id', $validate['lesson_id'])
            ->exists();

        if ($exists) {
            return back()->withError('Anda sudah menyelesaikan materi ini sebelumnya.');
        }

        $progress = Progress::create([
            'user_id'      => $user->id,
            'lesson_id'    => $validate['lesson_id'],
            'completed_at' => now(),
        ]);

        $lesson = \App\Models\Lesson::find($validate['lesson_id']);
        $isGenerated = \App\Models\Certificate::checkAndGenerate($user->id, $lesson->course_id);

        $msg = 'Selamat! Anda telah menyelesaikan materi ini.';
        if ($isGenerated) {
            $msg .= ' Dan Anda telah mendapatkan Sertifikat Kelulusan Kursus!';
        }

        return back()->withSuccess($msg);
    }
}
