<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'description',
        'thumbnail',
        'level',
        'status',
    ];

    /**
     * A course belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A course belongs to an instructor (User).
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * A course has many lessons.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * A course has many quizzes.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * A course has many enrollments.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * A course has many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * A course has many certificates.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
