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
        if ($certificate->user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        $certificate->load(['course', 'user']);

        return view('certificate.show', [
            'title'       => 'Sertifikat: ' . $certificate->course->title,
            'certificate' => $certificate,
        ]);
    }
}
