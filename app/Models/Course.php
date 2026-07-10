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
}
