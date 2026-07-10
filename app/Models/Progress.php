<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * A progress record belongs to a user (student).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A progress record belongs to a lesson.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
