<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row g-4">
        {{-- Kolom Kiri: Thumbnail & Info Kursus --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('niceadmin/img/noprofil.png') }}"
                    alt="Thumbnail {{ $course->title }}" class="card-img-top rounded-top" style="height: 220px; object-fit: cover;">
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0 small">
                        <tr>
                            <th class="text-muted fw-normal" style="width: 90px;">Kategori</th>
                            <td class="fw-semibold">{{ $course->category->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Level</th>
                            <td>
                                <span class="badge bg-primary">{{ $course->level }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Instruktur</th>
                            <td class="fw-semibold">{{ $course->instructor->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Status</th>
                            <td>
                                @if ($course->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Dibuat</th>
                            <td>{{ $course->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'instructor')
                    <div class="card-footer bg-white d-flex gap-2">
                        <a href="{{ route('course.edit', $course) }}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class='bx bx-edit me-1'></i> Edit Kursus
                        </a>
                        <a href="{{ route('enrollment.manage', $course) }}" class="btn btn-outline-success btn-sm flex-fill">
                            <i class='bx bx-group me-1'></i> Kelola Siswa
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Deskripsi, Materi, Ulasan --}}
        <div class="col-lg-8">
            <h3 class="fw-bold mb-1">{{ $course->title }}</h3>
            <p class="text-muted mb-4">{{ $course->description ?? 'Tidak ada deskripsi.' }}</p>

            {{-- Daftar Materi --}}
            <h5 class="fw-bold mb-3"><i class='bx bx-list-ul me-2'></i>Daftar Materi</h5>
            @php 
                $lessons = $course->lessons()->orderBy('order')->get();
                $completedLessonIds = [];
                if (Auth::check() && Auth::user()->role === 'student') {
                    $completedLessonIds = Auth::user()->progressRecords()->whereIn('lesson_id', $lessons->pluck('id'))->pluck('lesson_id')->toArray();
                }
            @endphp
            @if ($lessons->isEmpty())
                <p class="text-muted fst-italic">Belum ada materi untuk kursus ini.</p>
            @else
                <div class="list-group mb-4">
                    @foreach ($lessons as $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessonIds);
                        @endphp
                        <a href="{{ route('lesson.show', $lesson) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 text-decoration-none text-dark {{ $isCompleted ? 'bg-light' : '' }}">
                            <span class="badge rounded-pill {{ $isCompleted ? 'bg-success' : 'bg-secondary' }} border fw-bold" style="min-width: 28px;">{{ $lesson->order }}</span>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold {{ $isCompleted ? 'text-success' : '' }}">
                                    {{ $lesson->title }}
                                    @if($isCompleted)
                                        <i class='bx bxs-check-circle ms-1' title="Selesai"></i>
                                    @endif
                                </p>
                                <small class="text-muted text-capitalize">
                                    <i class='bx bx-{{ $lesson->type == "video" ? "video" : ($lesson->type == "pdf" ? "file-pdf" : "file-alt") }} me-1'></i>
                                    {{ $lesson->type }}
                                    
                                    @if(Auth::check() && Auth::user()->role === 'student')
                                        <span class="mx-1">•</span>
                                        @if($isCompleted)
                                            <span class="text-success"><i class='bx bx-check me-1'></i>Telah Selesai</span>
                                        @else
                                            <span class="text-danger"><i class='bx bx-time me-1'></i>Belum Selesai</span>
                                        @endif
                                    @endif
                                </small>
                            </div>
                            <i class='bx bx-chevron-right text-muted'></i>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Daftar Kuis --}}
            <h5 class="fw-bold mb-3 mt-4"><i class='bx bx-question-mark me-2'></i>Daftar Kuis</h5>
            @php 
                $quizzes = $course->quizzes()->latest()->get();
                $quizAttempts = [];
                if (Auth::check() && Auth::user()->role === 'student') {
                    $quizAttempts = Auth::user()->quizAttempts()->whereIn('quiz_id', $quizzes->pluck('id'))->get()->keyBy('quiz_id');
                }
            @endphp
            @if ($quizzes->isEmpty())
                <p class="text-muted fst-italic">Belum ada kuis untuk kursus ini.</p>
            @else
                <div class="list-group mb-4">
                    @foreach ($quizzes as $quiz)
                        @php
                            $attempt = Auth::check() && Auth::user()->role === 'student' ? ($quizAttempts[$quiz->id] ?? null) : null;
                            $hasPassed = $attempt && $attempt->passed;
                            $href = Auth::check() && Auth::user()->role === 'student' ? route('student.quiz.show', $quiz) : '#';
                        @endphp
                        <a href="{{ $href }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 text-decoration-none text-dark {{ $hasPassed ? 'bg-light' : '' }}">
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold {{ $hasPassed ? 'text-success' : '' }}">
                                    {{ $quiz->title }}
                                    @if($hasPassed)
                                        <i class='bx bxs-check-circle ms-1' title="Lulus"></i>
                                    @endif
                                </p>
                                <small class="text-muted">
                                    <i class='bx bx-bullseye me-1'></i>Nilai Lulus: {{ $quiz->passing_score }}
                                    
                                    @if(Auth::check() && Auth::user()->role === 'student')
                                        <span class="mx-1">•</span>
                                        @if($attempt)
                                            @if($attempt->passed)
                                                <span class="text-success"><i class='bx bx-check me-1'></i>Lulus (Skor: {{ $attempt->score }})</span>
                                            @else
                                                <span class="text-danger"><i class='bx bx-x me-1'></i>Tidak Lulus (Skor: {{ $attempt->score }})</span>
                                            @endif
                                        @else
                                            <span class="text-warning"><i class='bx bx-time me-1'></i>Belum Dikerjakan</span>
                                        @endif
                                    @endif
                                </small>
                            </div>
                            @if(Auth::check() && Auth::user()->role === 'student')
                                <span class="btn btn-sm {{ $hasPassed ? 'btn-outline-success' : 'btn-primary' }}">
                                    {{ $hasPassed ? 'Lihat Hasil' : 'Kerjakan' }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Ulasan Kursus --}}
            @php
                $reviews = $course->reviews()->with('user')->latest()->get();
                $averageRating = $reviews->avg('rating');
            @endphp

            <div class="pt-3 border-top">
                <h5 class="fw-bold mb-3">
                    <i class='bx bx-star me-2'></i>
                    Ulasan Kursus
                    @if ($reviews->count() > 0)
                        <span class="text-warning fs-6">({{ number_format($averageRating, 1) }}/5.0)</span>
                    @endif
                </h5>

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
                        <p class="text-muted fst-italic">Belum ada ulasan untuk kursus ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-app>
