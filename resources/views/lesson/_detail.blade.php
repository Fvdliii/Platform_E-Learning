<div class="p-2">
    <table class="table table-borderless">
        <tr>
            <th width="25%">Judul Materi</th>
            <td>: {{ $lesson->title }}</td>
        </tr>
        <tr>
            <th>Kursus</th>
            <td>: {{ $lesson->course->title }}</td>
        </tr>
        <tr>
            <th>Tipe</th>
            <td>:
                @if ($lesson->type === 'text')
                    <span class="badge bg-primary">Teks</span>
                @elseif($lesson->type === 'video')
                    <span class="badge bg-danger">Video</span>
                @else
                    <span class="badge bg-warning text-dark">PDF</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Urutan</th>
            <td>: {{ $lesson->order }}</td>
        </tr>
    </table>

    <hr>
    
    <div class="mt-3">
        <h5 class="fw-bold">Konten:</h5>
        @if ($lesson->type === 'text')
            <div class="p-3 bg-light border rounded">
                {!! nl2br(e($lesson->content)) !!}
            </div>
        @elseif($lesson->type === 'video')
        <div class="ratio ratio-16x9 my-3">
            @php
                $videoUrl = $lesson->file_path;

                if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                    // Konversi semua format URL YouTube ke format embed
                    $youtubePatterns = [
                        // https://www.youtube.com/watch?v=VIDEO_ID
                        '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
                        // https://youtu.be/VIDEO_ID
                        '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                        // https://www.youtube.com/shorts/VIDEO_ID
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
                <video controls class="w-100">
                    <source src="{{ asset('storage/' . $lesson->file_path) }}" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>
            @endif
        </div>
        <p>{{ $lesson->content }}</p>
        @elseif($lesson->type === 'pdf')
            <div class="p-3 bg-light border rounded mb-3 text-center">
                <i class='bx bxs-file-pdf text-danger' style="font-size: 3rem;"></i><br>
                <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                    <i class='bx bx-download'></i> Download PDF
                </a>
            </div>
            <p>{{ $lesson->content }}</p>
        @endif
    </div>
</div>
