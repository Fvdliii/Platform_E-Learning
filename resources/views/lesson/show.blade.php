<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="mb-3">
                <a href="{{ route('course.show', $lesson->course) }}" class="btn btn-outline-secondary btn-sm">
                    <i class='bx bx-arrow-back me-1'></i> Kembali ke Kursus
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <span class="badge bg-primary mb-2">Materi {{ $lesson->order }}</span>
                        <h2 class="fw-bold">{{ $lesson->title }}</h2>
                    </div>

                    <div class="lesson-content">
                        @if ($lesson->type === 'text')
                            <div class="fs-5" style="line-height: 1.8;">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        @elseif($lesson->type === 'video')
                            <div class="ratio ratio-16x9 my-4 shadow-sm rounded overflow-hidden">
                                @php
                                    $videoUrl = $lesson->file_path;
                                    if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                                        $youtubePatterns = [
                                            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
                                            '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                                            '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
                                        ];
                                        foreach ($youtubePatterns as $pattern) {
                                            if (preg_match($pattern, $videoUrl, $matches)) {
                                                $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                                break;
                                            }
                                        }
                                    }
                                @endphp

                                @if(filter_var($lesson->file_path, FILTER_VALIDATE_URL))
                                    <iframe src="{{ $videoUrl }}" title="Video Materi"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <video controls class="w-100 bg-black">
                                        <source src="{{ asset('storage/' . $lesson->file_path) }}" type="video/mp4">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @endif
                            </div>
                            <div class="fs-5 text-muted" style="line-height: 1.6;">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        @elseif($lesson->type === 'pdf')
                            <div class="p-5 bg-light border rounded mb-4 text-center">
                                <i class='bx bxs-file-pdf text-danger mb-3' style="font-size: 5rem;"></i><br>
                                <h5>Materi PDF Tersedia</h5>
                                <p class="text-muted mb-4">Klik tombol di bawah ini untuk mengunduh atau membuka materi PDF.</p>
                                <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank" class="btn btn-primary btn-lg px-5 shadow-sm">
                                    <i class='bx bx-download me-2'></i> Download / Buka PDF
                                </a>
                            </div>
                            <div class="fs-5" style="line-height: 1.6;">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        @endif
                    </div>

                    {{-- Form Tandai Selesai / Progress --}}
                    @auth
                        @if(Auth::user()->role === 'student' && Auth::user()->enrollments()->where('course_id', $lesson->course_id)->exists())
                            @php
                                $isCompleted = Auth::user()->progressRecords()->where('lesson_id', $lesson->id)->exists();
                            @endphp
                            
                            <div class="mt-5 pt-4 border-top text-center">
                                @if($isCompleted)
                                    <div class="alert alert-success d-inline-block px-4 py-3 border-0 shadow-sm">
                                        <i class='bx bxs-check-circle fs-4 align-middle me-2'></i>
                                        <span class="fw-bold align-middle">Anda telah menyelesaikan materi ini!</span>
                                    </div>
                                @else
                                    <form action="{{ route('progress.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                        <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                            <i class='bx bx-check-double me-2'></i>Tandai Telah Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth

                </div>
            </div>

            {{-- Navigasi Materi --}}
            @php
                $previousLesson = $lesson->course->lessons()->where('order', '<', $lesson->order)->orderBy('order', 'desc')->first();
                $nextLesson = $lesson->course->lessons()->where('order', '>', $lesson->order)->orderBy('order', 'asc')->first();
            @endphp
            <div class="d-flex justify-content-between mt-4">
                @if($previousLesson)
                    <a href="{{ route('lesson.show', $previousLesson) }}" class="btn btn-primary">
                        <i class='bx bx-chevron-left me-1'></i> Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif
                
                @if($nextLesson)
                    <a href="{{ route('lesson.show', $nextLesson) }}" class="btn btn-primary">
                        Selanjutnya <i class='bx bx-chevron-right ms-1'></i>
                    </a>
                @else
                    @if(Auth::user()->role === 'student')
                        <a href="{{ route('course.show', $lesson->course) }}" class="btn btn-success">
                            Selesai <i class='bx bx-flag ms-1'></i>
                        </a>
                    @endif
                @endif
            </div>

        </div>
    </div>

</x-app>
