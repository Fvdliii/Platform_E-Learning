<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'rating',
        'comment',
    ];

    /**
     * A review belongs to a user (student).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A review belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
