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

        {{-- Informasi ketentuan 10 soal --}}
        @php $questionCount = $quiz->questions->count(); @endphp
        @if($questionCount < 10)
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-3">
                <i class='bx bx-info-circle fs-4'></i>
                <div>
                    <strong>Ketentuan Kuis:</strong> Kuis wajib memiliki tepat <strong>10 soal</strong> agar dapat dikerjakan oleh siswa.
                    Saat ini kuis ini baru memiliki <strong>{{ $questionCount }} soal</strong>. Tambahkan <strong>{{ 10 - $questionCount }} soal lagi</strong> untuk melengkapinya.
                </div>
            </div>
        @else
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-3 mb-3">
                <i class='bx bxs-check-circle fs-4'></i>
                <div>
                    <strong>Kuis Siap!</strong> Kuis ini sudah memiliki <strong>10 soal</strong> dan dapat dikerjakan oleh siswa.
                </div>
            </div>
        @endif

        <div class="text-end">
            @if($questionCount < 10)
                <a href="{{ route('question.create', ['quiz_id' => $quiz->id]) }}" class="btn btn-success">
                    <i class='bx bx-plus'></i> Tambah Soal ({{ $questionCount }}/10)
                </a>
            @else
                <button class="btn btn-success" disabled title="Sudah mencapai batas 10 soal">
                    <i class='bx bx-check'></i> Soal Lengkap (10/10)
                </button>
            @endif
            <a href="{{ route('quiz.edit', $quiz) }}" class="btn btn-warning ms-1">
                <i class='bx bx-edit-alt'></i> Edit Kuis
            </a>
            <a href="{{ route('quiz.index') }}" class="btn btn-secondary ms-1">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <h6 class="fw-bold mb-3">Daftar Soal 
            <span class="badge {{ $questionCount >= 10 ? 'bg-success' : 'bg-warning text-dark' }} ms-1">{{ $questionCount }}/10 soal</span>
        </h6>

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

    {{-- ===== REKAP PENGERJAAN SISWA ===== --}}
    <div class="card shadow-lg p-3 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class='bx bx-list-check me-2'></i>Rekap Pengerjaan Siswa
            </h6>
            <span class="badge bg-secondary">{{ $attempts->count() }} Percobaan</span>
        </div>

        @if($attempts->isEmpty())
            <div class="text-center text-muted py-4">
                <i class='bx bx-user-x' style="font-size: 3rem;"></i>
                <p class="mt-2">Belum ada siswa yang mengerjakan kuis ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Siswa</th>
                            <th class="text-center">Soal Benar</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Status</th>
                            <th>Catatan Instruktur</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $attempt->user->name }}</div>
                                    <small class="text-muted">{{ $attempt->user->email }}</small><br>
                                    <small class="text-muted">{{ $attempt->created_at->format('d M Y, H:i') }}</small>
                                </td>
                                <td class="text-center fw-bold">{{ $attempt->correct_count }}/10</td>
                                <td class="text-center">
                                    <span class="badge fs-6 {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}">
                                        {{ $attempt->score }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($attempt->passed)
                                        <span class="badge bg-success"><i class='bx bx-check me-1'></i>Lulus</span>
                                    @else
                                        <span class="badge bg-danger"><i class='bx bx-x me-1'></i>Tidak Lulus</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tampilkan catatan yang ada --}}
                                    @if($attempt->note)
                                        <p class="mb-1 text-muted small fst-italic border-start border-warning ps-2">{{ $attempt->note }}</p>
                                    @else
                                        <p class="mb-1 text-muted small fst-italic">Belum ada catatan.</p>
                                    @endif

                                    {{-- Form tambah/edit catatan --}}
                                    <form action="{{ route('quiz.attempt.note', $attempt) }}" method="POST" class="mt-1 d-flex gap-1">
                                        @csrf
                                        <input type="text" name="note" value="{{ $attempt->note }}" class="form-control form-control-sm" placeholder="Tulis catatan...">
                                        <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap">
                                            <i class='bx bx-save'></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('quiz.attempt.answers', $attempt) }}" class="btn btn-sm btn-outline-info" title="Lihat Jawaban Siswa">
                                            <i class='bx bx-show me-1'></i>Jawaban
                                        </a>
                                        <form action="{{ route('quiz.attempt.reset', $attempt) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset — siswa dapat mengerjakan ulang"
                                                onclick="return confirm('Reset percobaan {{ $attempt->user->name }}? Siswa akan bisa mengerjakan ulang kuis ini.')">
                                                <i class='bx bx-refresh me-1'></i>Reset
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app>
