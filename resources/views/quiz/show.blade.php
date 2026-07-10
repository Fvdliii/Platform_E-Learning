<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">{{ $quiz->title }}</h5>
                <span class="text-muted">Kursus: {{ $quiz->course->title }}</span>
            </div>
            <div>
                <span class="badge bg-primary fs-6">Passing Score: {{ $quiz->passing_score }}</span>
            </div>
        </div>
        @if ($quiz->description)
            <p class="text-muted">{{ $quiz->description }}</p>
        @endif
        <div class="text-end">
            <a href="{{ route('question.create', ['quiz_id' => $quiz->id]) }}" class="btn btn-success">
                <i class='bx bx-plus'></i> Tambah Soal
            </a>
            <a href="{{ route('quiz.edit', $quiz) }}" class="btn btn-warning ms-1">
                <i class='bx bx-edit-alt'></i> Edit Kuis
            </a>
            <a href="{{ route('quiz.index') }}" class="btn btn-secondary ms-1">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <h6 class="fw-bold mb-3">Daftar Soal ({{ $quiz->questions->count() }} soal)</h6>

        @forelse ($quiz->questions as $qIndex => $question)
            <div class="card mb-3 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="fw-semibold mb-2">{{ $qIndex + 1 }}. {{ $question->text }}</p>
                        <div class="ms-3 text-nowrap">
                            <a href="{{ route('question.edit', $question) }}" class="btn btn-warning btn-sm">
                                <i class='bx bx-edit-alt'></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-delete-question"
                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-route="{{ route('question.destroy', $question) }}">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        @foreach ($question->answers as $aIndex => $answer)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded {{ $answer->is_correct ? 'bg-success bg-opacity-10 border border-success' : 'bg-light' }}">
                                    <span class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-secondary' }} me-2">
                                        {{ chr(65 + $aIndex) }}
                                    </span>
                                    <span>{{ $answer->text }}</span>
                                    @if ($answer->is_correct)
                                        <i class='bx bx-check-circle text-success ms-auto'></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class='bx bx-question-mark' style="font-size: 3rem;"></i>
                <p class="mt-2">Belum ada soal. Klik <strong>Tambah Soal</strong> untuk memulai.</p>
            </div>
        @endforelse
    </div>

    @push('scripts')
        <script>
            $('.btn-delete-question').on('click', function() {
                $('#form-delete').attr('action', $(this).data('route'));
            });
        </script>
    @endpush

</x-app>
