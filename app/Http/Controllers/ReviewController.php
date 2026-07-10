<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string',
        ]);

        // Pastikan user sudah enroll ke course ini
        $isEnrolled = $user->enrollments()->where('course_id', $validate['course_id'])->exists();
        if (!$isEnrolled) {
            return back()->withError('Anda harus mendaftar ke kursus ini untuk memberikan ulasan.');
        }

        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', $user->id)
            ->where('course_id', $validate['course_id'])
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating'  => $validate['rating'],
                'comment' => $validate['comment'],
            ]);
            return back()->withSuccess('Ulasan Anda berhasil diperbarui.');
        }

        Review::create([
            'user_id'   => $user->id,
            'course_id' => $validate['course_id'],
            'rating'    => $validate['rating'],
            'comment'   => $validate['comment'],
        ]);

        return back()->withSuccess('Terima kasih! Ulasan Anda berhasil ditambahkan.');
    }
}
