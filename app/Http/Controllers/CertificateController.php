<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $certificates = Certificate::with('course')
            ->where('user_id', $user->id)
            ->latest('issued_at')
            ->get();

        return view('certificate.index', [
            'title'        => 'Sertifikat Saya',
            'certificates' => $certificates,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $user = Auth::user();

        // Verifikasi kepemilikan sertifikat
        if ($certificate->user_id !== $user->id && $user->role !== 'admin' && $user->role !== 'instructor') {
            abort(403);
        }

        $certificate->load(['course', 'user']);

        return view('certificate.show', [
            'title'       => 'Sertifikat: ' . $certificate->course->title,
            'certificate' => $certificate,
        ]);
    }

    /**
     * Admin / Instructor: Manage certificates
     */
    public function manage()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $certificates = Certificate::with(['course', 'user'])->latest('issued_at')->get();
            $courses = \App\Models\Course::with('enrollments.user')->get();
        } else {
            // Instructor only sees certificates for their courses
            $certificates = Certificate::with(['course', 'user'])
                ->whereHas('course', function ($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                })->latest('issued_at')->get();
            $courses = \App\Models\Course::with('enrollments.user')->where('instructor_id', $user->id)->get();
        }

        return view('certificate.manage', [
            'title'        => 'Kelola Sertifikat',
            'certificates' => $certificates,
            'courses'      => $courses,
        ]);
    }

    /**
     * Admin / Instructor: Issue a new certificate
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        $validate = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        if ($user->role === 'instructor') {
            $course = \App\Models\Course::findOrFail($validate['course_id']);
            if ($course->instructor_id !== $user->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        // Check if certificate already exists
        $exists = Certificate::where('user_id', $validate['user_id'])
            ->where('course_id', $validate['course_id'])
            ->exists();

        if ($exists) {
            return back()->withError('Siswa sudah memiliki sertifikat untuk kursus ini.');
        }

        // Generate unique certificate number
        $certNumber = 'CERT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        Certificate::create([
            'user_id'            => $validate['user_id'],
            'course_id'          => $validate['course_id'],
            'certificate_number' => $certNumber,
            'issued_at'          => now(),
        ]);

        return back()->withSuccess('Sertifikat berhasil diterbitkan.');
    }

    /**
     * Admin / Instructor: Revoke certificate
     */
    public function destroy(Certificate $certificate)
    {
        $user = Auth::user();

        if ($user->role === 'instructor' && $certificate->course->instructor_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $certificate->delete();

        return back()->withSuccess('Sertifikat berhasil dihapus/dicabut.');
    }
}
