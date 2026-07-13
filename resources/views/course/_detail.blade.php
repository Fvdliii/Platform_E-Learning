<div class="p-2">
    <div class="row">
        <div class="col-md-3">
            <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('niceadmin/img/noprofil.png') }}"
                alt="Thumbnail" class="w-100 rounded">
        </div>
        <div class="col-md-9">
            <table class="table table-borderless">
                <tr>
                    <th>Judul</th>
                    <td>: {{ $course->title }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>: {{ $course->category->name }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>: {{ $course->level }}</td>
                </tr>
                <tr>
                    <th>Instruktur</th>
                    <td>: {{ $course->instructor->name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>:
                        @if ($course->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>: {{ $course->description ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>: {{ $course->created_at->format('d M Y, H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Ulasan Kursus --}}
    @php
        $reviews = $course->reviews()->with('user')->latest()->get();
        $averageRating = $reviews->avg('rating');
    @endphp

    <div class="mt-4 pt-3 border-top">
        <h6 class="fw-bold mb-3">
            <i class='bx bx-star me-2'></i>
            Ulasan Siswa
            @if ($reviews->count() > 0)
                <span class="text-warning">({{ number_format($averageRating, 1) }}/5.0)</span>
            @endif
        </h6>

        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
            @forelse ($reviews as $review)
                <div class="list-group-item px-0 py-2">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <span class="mb-0 fw-semibold">{{ $review->user->name }}</span>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-warning mb-1 small">
                        @for ($i = 0; $i < $review->rating; $i++)
                            <i class='bx bxs-star'></i>
                        @endfor
                        @for ($i = $review->rating; $i < 5; $i++)
                            <i class='bx bx-star'></i>
                        @endfor
                    </div>
                    <p class="mb-0 text-secondary small">{{ $review->comment ?? 'Tidak ada komentar.' }}</p>
                </div>
            @empty
                <p class="text-muted fst-italic small">Belum ada ulasan untuk kursus ini.</p>
            @endforelse
        </div>
    </div>
</div>
