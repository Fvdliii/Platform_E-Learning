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
}
