<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin melihat semua kursus, instructor hanya miliknya sendiri
        $courses = $user->role === 'admin'
            ? Course::with(['category', 'instructor'])->latest()->get()
            : Course::with(['category', 'instructor'])->where('instructor_id', $user->id)->latest()->get();

        return view('course.index', [
            'title'   => 'Kursus',
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course.create', [
            'title'       => 'Tambah Kursus',
            'categories'  => Category::all(),
            'instructors' => User::where('role', 'instructor')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'title'         => 'required',
            'description'   => 'nullable',
            'thumbnail'     => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'level'         => 'required|in:N5,N4,N3,N2,N1,Umum',
            'status'        => 'required|in:draft,published',
        ], [
            'category_id.required'   => 'Kategori wajib dipilih',
            'category_id.exists'     => 'Kategori tidak valid',
            'instructor_id.required' => 'Instruktur wajib dipilih',
            'instructor_id.exists'   => 'Instruktur tidak valid',
            'title.required'         => 'Judul kursus wajib diisi',
            'level.required'         => 'Level wajib dipilih',
            'status.required'        => 'Status wajib dipilih',
            'thumbnail.image'        => 'Thumbnail harus berupa gambar',
            'thumbnail.mimes'        => 'Format thumbnail harus png, jpg, atau jpeg',
            'thumbnail.max'          => 'Ukuran thumbnail tidak boleh lebih dari 1 MB',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('thumbnail')) {
                $validate['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // Jika instructor, paksa instructor_id ke dirinya sendiri
            if (Auth::user()->role === 'instructor') {
                $validate['instructor_id'] = Auth::id();
            }

            Course::create($validate);

            DB::commit();
            return to_route('course.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('course.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Course $course)
    {
        $course->load(['category', 'instructor', 'lessons']);

        // Jika request dari modal admin/instruktur (?modal=1), kembalikan partial view
        if ($request->has('modal')) {
            return view('course._detail', [
                'course' => $course,
            ]);
        }

        // Halaman penuh untuk student dan direct access
        return view('course.show', [
            'title'  => 'Detail Kursus: ' . $course->title,
            'course' => $course,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('course.edit', [
            'title'       => 'Edit Kursus',
            'course'      => $course,
            'categories'  => Category::all(),
            'instructors' => User::where('role', 'instructor')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validate = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'title'         => 'required',
            'description'   => 'nullable',
            'thumbnail'     => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'level'         => 'required|in:N5,N4,N3,N2,N1,Umum',
            'status'        => 'required|in:draft,published',
        ], [
            'category_id.required'   => 'Kategori wajib dipilih',
            'category_id.exists'     => 'Kategori tidak valid',
            'instructor_id.required' => 'Instruktur wajib dipilih',
            'instructor_id.exists'   => 'Instruktur tidak valid',
            'title.required'         => 'Judul kursus wajib diisi',
            'level.required'         => 'Level wajib dipilih',
            'status.required'        => 'Status wajib dipilih',
            'thumbnail.image'        => 'Thumbnail harus berupa gambar',
            'thumbnail.mimes'        => 'Format thumbnail harus png, jpg, atau jpeg',
            'thumbnail.max'          => 'Ukuran thumbnail tidak boleh lebih dari 1 MB',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('thumbnail')) {
                $validate['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
                if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
            }

            // Jika instructor, paksa instructor_id ke dirinya sendiri
            if (Auth::user()->role === 'instructor') {
                $validate['instructor_id'] = Auth::id();
            }

            $course->update($validate);

            DB::commit();
            return to_route('course.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('course.edit', $course)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        DB::beginTransaction();

        try {
            $thumbnail = $course->thumbnail;

            $course->delete();

            if ($thumbnail && Storage::disk('public')->exists($thumbnail)) {
                Storage::disk('public')->delete($thumbnail);
            }

            DB::commit();
            return to_route('course.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('course.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
