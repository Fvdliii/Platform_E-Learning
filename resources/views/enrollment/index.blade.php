<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row g-4">
        @forelse ($enrollments as $enrollment)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <img src="{{ $enrollment->course->thumbnail ? asset('storage/' . $enrollment->course->thumbnail) : asset('niceadmin/img/noprofil.png') }}"
                        class="card-img-top" alt="Course Thumbnail" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-truncate" title="{{ $enrollment->course->title }}">
                            {{ $enrollment->course->title }}
                        </h5>
                        <p class="text-muted small mb-3">Terdaftar: {{ $enrollment->enrolled_at->format('d M Y') }}</p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold">Progres Belajar</span>
                                <span class="small fw-bold {{ $enrollment->progress_percentage == 100 ? 'text-success' : 'text-primary' }}">
                                    {{ $enrollment->progress_percentage }}%
                                </span>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar {{ $enrollment->progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                                    role="progressbar" style="width: {{ $enrollment->progress_percentage }}%;"
                                    aria-valuenow="{{ $enrollment->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    {{ $enrollment->completed_lessons }} / {{ $enrollment->total_lessons }} Materi Selesai
                                </span>
                                
                                <form action="{{ route('enrollment.destroy', $enrollment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin berhenti dari kursus ini? Progres belajar mungkin tidak dapat dikembalikan.')">
                                        Berhenti
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                        <a href="{{ route('course.show', $enrollment->course) }}" class="btn btn-primary w-100">
                            {{ $enrollment->progress_percentage > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class='bx bx-book-reader text-muted' style="font-size: 5rem;"></i>
                    <h5 class="mt-3 text-muted">Belum ada kursus yang Anda ikuti.</h5>
                    <a href="{{ route('course.index') }}" class="btn btn-primary mt-3">Jelajahi Kursus</a>
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
