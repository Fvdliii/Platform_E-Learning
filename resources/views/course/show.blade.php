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
</div>

@php
    $reviews = $course->reviews()->with('user')->latest()->get();
    $averageRating = $reviews->avg('rating');
@endphp

<div class="mt-4 pt-4 border-top">
    <h5 class="fw-bold mb-3">Ulasan Kursus ({{ number_format($averageRating, 1) }} / 5.0)</h5>
    
    @auth
        @php
            $isEnrolled = Auth::user()->role === 'student' && Auth::user()->enrollments()->where('course_id', $course->id)->exists();
            $myReview = $isEnrolled ? $course->reviews()->where('user_id', Auth::id())->first() : null;
        @endphp
        
        @if ($isEnrolled)
            <div class="card bg-light mb-4 border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">{{ $myReview ? 'Edit Ulasan Anda' : 'Berikan Ulasan' }}</h6>
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select w-auto" required>
                                <option value="5" @selected(old('rating', $myReview->rating ?? 5) == 5)>⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4" @selected(old('rating', $myReview->rating ?? 5) == 4)>⭐⭐⭐⭐ (4/5)</option>
                                <option value="3" @selected(old('rating', $myReview->rating ?? 5) == 3)>⭐⭐⭐ (3/5)</option>
                                <option value="2" @selected(old('rating', $myReview->rating ?? 5) == 2)>⭐⭐ (2/5)</option>
                                <option value="1" @selected(old('rating', $myReview->rating ?? 5) == 1)>⭐ (1/5)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Komentar (Opsional)</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Tuliskan pengalaman Anda mengikuti kursus ini...">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ $myReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endauth
    
    <div class="list-group list-group-flush">
        @forelse ($reviews as $review)
            <div class="list-group-item px-0 py-3">
                <div class="d-flex w-100 justify-content-between mb-1">
                    <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
                <div class="text-warning mb-2">
                    @for ($i = 0; $i < $review->rating; $i++)
                        <i class='bx bxs-star'></i>
                    @endfor
                    @for ($i = $review->rating; $i < 5; $i++)
                        <i class='bx bx-star'></i>
                    @endfor
                </div>
                <p class="mb-1 text-secondary">{{ $review->comment ?? 'Tidak ada komentar.' }}</p>
            </div>
        @empty
            <div class="text-muted fst-italic">Belum ada ulasan untuk kursus ini.</div>
        @endforelse
    </div>
</div>
