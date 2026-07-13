<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    /**
     * A certificate belongs to a user (student).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A certificate belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Otomatis membuat sertifikat jika kriteria terpenuhi:
     * - Progress 100% (selesai semua lesson)
     * - Kuis Lulus
     */
    public static function checkAndGenerate($user_id, $course_id)
    {
        // 1. Cek apakah sudah punya sertifikat
        if (self::where('user_id', $user_id)->where('course_id', $course_id)->exists()) {
            return false;
        }

        $course = Course::withCount('lessons')->find($course_id);
        if (!$course) return false;

        // 2. Cek Progress (apakah menyelesaikan semua lesson?)
        $progressCount = Progress::where('user_id', $user_id)
            ->whereHas('lesson', function ($q) use ($course_id) {
                $q->where('course_id', $course_id);
            })->count();

        if ($progressCount < $course->lessons_count || $course->lessons_count == 0) {
            return false; // Belum selesai semua lesson
        }

        // 3. Cek Kuis (apakah lulus?)
        $quiz = Quiz::where('course_id', $course_id)->first();
        if ($quiz) {
            $passedQuiz = QuizAttempt::where('user_id', $user_id)
                ->where('quiz_id', $quiz->id)
                ->where('passed', true)
                ->exists();
            
            if (!$passedQuiz) {
                return false; // Belum lulus kuis
            }
        }

        // 4. Terbitkan Sertifikat
        self::create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'certificate_number' => 'CERT-' . strtoupper(uniqid()),
            'issued_at' => now(),
        ]);

        return true;
    }
}
