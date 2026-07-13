<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin melihat semua materi, instructor hanya materi dari kursusnya sendiri
        if ($user->role === 'admin') {
            $lessons = Lesson::with('course')->latest()->get();
        } else {
            $lessons = Lesson::whereHas('course', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->with('course')->latest()->get();
        }

        return view('lesson.index', [
            'title'   => 'Materi Pelajaran',
            'lessons' => $lessons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::all()
            : Course::where('instructor_id', $user->id)->get();

        return view('lesson.create', [
            'title'   => 'Tambah Materi Pelajaran',
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required',
            'content'   => 'nullable',
            'type'      => 'required|in:text,video,pdf',
            'file_path' => 'nullable',
            'order'     => 'required|integer|min:1',
        ], [
            'course_id.required' => 'Kursus wajib dipilih',
            'course_id.exists'   => 'Kursus tidak valid',
            'title.required'     => 'Judul materi wajib diisi',
            'type.required'      => 'Tipe materi wajib dipilih',
            'order.required'     => 'Urutan wajib diisi',
            'order.integer'      => 'Urutan harus berupa angka',
        ]);

        // Verifikasi kepemilikan kursus untuk instructor
        if ($user->role === 'instructor') {
            $course = Course::findOrFail($validate['course_id']);
            if ($course->instructor_id !== $user->id) {
                return back()->withError('Anda tidak memiliki akses untuk menambah materi ke kursus ini.');
            }
        }

        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                $validate['file_path'] = $request->file('file')->store('lessons', 'public');
            } else if ($request->filled('file_link')) {
                $validate['file_path'] = $request->file_link;
            }

            Lesson::create($validate);

            DB::commit();
            return to_route('lesson.index')->withSuccess('Materi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menambahkan materi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        // Admin dan instructor pengecekan akses
        if ($user->role === 'instructor' && $lesson->course->instructor_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Student pengecekan pendaftaran
        if ($user->role === 'student') {
            $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
                ->where('course_id', $lesson->course_id)
                ->exists();
            
            if (!$isEnrolled) {
                return redirect()->route('course.show', $lesson->course)
                    ->withError('Anda harus mendaftar ke kursus ini untuk melihat materi.');
            }
        }

        // Jika request dari modal admin/instruktur (?modal=1), kembalikan partial view
        if ($request->has('modal')) {
            return view('lesson._detail', [
                'lesson' => $lesson,
            ]);
        }

        return view('lesson.show', [
            'title'  => 'Materi: ' . $lesson->title,
            'lesson' => $lesson->load('course'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $lesson->course->instructor_id !== $user->id) {
            abort(403);
        }

        $courses = $user->role === 'admin'
            ? Course::all()
            : Course::where('instructor_id', $user->id)->get();

        return view('lesson.edit', [
            'title'   => 'Edit Materi Pelajaran',
            'lesson'  => $lesson,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $lesson->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses untuk mengubah materi ini.');
        }

        $validate = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required',
            'content'   => 'nullable',
            'type'      => 'required|in:text,video,pdf',
            'file_path' => 'nullable',
            'order'     => 'required|integer|min:1',
        ], [
            'course_id.required' => 'Kursus wajib dipilih',
            'course_id.exists'   => 'Kursus tidak valid',
            'title.required'     => 'Judul materi wajib diisi',
            'type.required'      => 'Tipe materi wajib dipilih',
            'order.required'     => 'Urutan wajib diisi',
            'order.integer'      => 'Urutan harus berupa angka',
        ]);

        if ($user->role === 'instructor') {
            $course = Course::findOrFail($validate['course_id']);
            if ($course->instructor_id !== $user->id) {
                return back()->withError('Anda tidak memiliki akses ke kursus yang dipilih.');
            }
        }

        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                if ($lesson->file_path && Storage::disk('public')->exists($lesson->file_path) && !filter_var($lesson->file_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($lesson->file_path);
                }
                $validate['file_path'] = $request->file('file')->store('lessons', 'public');
            } else if ($request->filled('file_link')) {
                if ($lesson->file_path && Storage::disk('public')->exists($lesson->file_path) && !filter_var($lesson->file_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($lesson->file_path);
                }
                $validate['file_path'] = $request->file_link;
            }

            $lesson->update($validate);

            DB::commit();
            return to_route('lesson.index')->withSuccess('Materi berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal mengubah materi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $lesson->course->instructor_id !== $user->id) {
            return back()->withError('Anda tidak memiliki akses untuk menghapus materi ini.');
        }

        DB::beginTransaction();

        try {
            $filePath = $lesson->file_path;

            $lesson->delete();

            if ($filePath && Storage::disk('public')->exists($filePath) && !filter_var($filePath, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();
            return to_route('lesson.index')->withSuccess('Materi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus materi: ' . $e->getMessage());
        }
    }
}
