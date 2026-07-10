<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row g-4">
        @forelse ($certificates as $certificate)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 hover-shadow text-center p-3">
                    <div class="mb-3 mt-2 text-warning">
                        <i class='bx bxs-certification' style="font-size: 5rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate" title="{{ $certificate->course->title }}">
                            {{ $certificate->course->title }}
                        </h5>
                        <p class="text-muted small mb-1">
                            Nomor: <span class="font-monospace fw-bold">{{ $certificate->certificate_number }}</span>
                        </p>
                        <p class="text-muted small mb-3">Diterbitkan: {{ $certificate->issued_at->format('d M Y') }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0">
                        <a href="{{ route('certificate.show', $certificate) }}" class="btn btn-primary w-100" target="_blank">
                            Lihat Sertifikat
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class='bx bx-award text-muted' style="font-size: 5rem;"></i>
                    <h5 class="mt-3 text-muted">Anda belum memiliki sertifikat.</h5>
                    <p class="text-muted">Selesaikan kursus Anda dan raih kelulusan untuk mendapatkan sertifikat.</p>
                </div>
            </div>
        @endforelse
    </div>

    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease-in-out;
        }
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>

</x-app>
