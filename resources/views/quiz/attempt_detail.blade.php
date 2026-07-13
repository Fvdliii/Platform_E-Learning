<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Header --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $attempt->user->name }}</h4>
                            <p class="text-muted mb-1">{{ $attempt->user->email }}</p>
                            <small class="text-muted">Dikerjakan pada: {{ $attempt->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="display-5 fw-bold {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                                {{ $attempt->score }}
                            </div>
                            <div class="text-muted small">Nilai Akhir</div>
                            <span class="badge {{ $attempt->passed ? 'bg-success' : 'bg-danger' }} mt-1">
                                {{ $attempt->passed ? '✔ Lulus' : '✘ Tidak Lulus' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center g-2">
                        <div class="col">
                            <div class="fw-bold fs-5 text-success">{{ $attempt->correct_count }}</div>
                            <div class="text-muted small">Benar</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-5 text-danger">{{ 10 - $attempt->correct_count }}</div>
                            <div class="text-muted small">Salah</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-5">10</div>
                            <div class="text-muted small">Total Soal</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-5 text-primary">{{ $attempt->quiz->passing_score }}</div>
                            <div class="text-muted small">Nilai Lulus</div>
                        </div>
                    </div>
                    @if($attempt->note)
                        <hr>
                        <div class="alert alert-warning border-0 mb-0 d-flex gap-2 align-items-start">
                            <i class='bx bx-comment-detail fs-5 mt-1'></i>
                            <div>
                                <strong>Catatan Instruktur:</strong>
                                <p class="mb-0">{{ $attempt->note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Daftar Jawaban per Soal --}}
            @php
                // Key attemptAnswers by question_id for easy lookup
                $attemptAnswerMap = $attempt->attemptAnswers->keyBy('question_id');
            @endphp

            @foreach($attempt->quiz->questions as $index => $question)
                @php $attemptAnswer = $attemptAnswerMap[$question->id] ?? null; @endphp
                <div class="card border-0 shadow-sm mb-3 border-start border-4 {{ $attemptAnswer && $attemptAnswer->is_correct ? 'border-success' : 'border-danger' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <span class="badge rounded-pill {{ $attemptAnswer && $attemptAnswer->is_correct ? 'bg-success' : 'bg-danger' }} fs-6" style="min-width:32px;">
                                {{ $index + 1 }}
                            </span>
                            <h6 class="fw-semibold mb-0">{{ $question->text }}</h6>
                        </div>

                        <div class="row g-2 ms-1">
                            @foreach($question->answers as $aIndex => $answer)
                                @php
                                    $isChosen  = $attemptAnswer && $attemptAnswer->answer_id == $answer->id;
                                    $isCorrect = $answer->is_correct;
                                @endphp
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 rounded
                                        {{ $isCorrect ? 'bg-success bg-opacity-10 border border-success' : '' }}
                                        {{ $isChosen && !$isCorrect ? 'bg-danger bg-opacity-10 border border-danger' : '' }}
                                        {{ !$isChosen && !$isCorrect ? 'bg-light' : '' }}
                                    ">
                                        <span class="badge {{ $isCorrect ? 'bg-success' : ($isChosen ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ chr(65 + $aIndex) }}
                                        </span>
                                        <span class="{{ $isChosen ? 'fw-semibold' : '' }}">{{ $answer->text }}</span>
                                        <span class="ms-auto">
                                            @if($isCorrect)
                                                <i class='bx bx-check-circle text-success' title="Jawaban Benar"></i>
                                            @endif
                                            @if($isChosen && !$isCorrect)
                                                <i class='bx bx-x-circle text-danger' title="Jawaban Siswa (Salah)"></i>
                                            @endif
                                            @if($isChosen && $isCorrect)
                                                <i class='bx bxs-check-circle text-success' title="Jawaban Siswa (Benar)"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 small">
                            @if($attemptAnswer)
                                @if($attemptAnswer->is_correct)
                                    <span class="text-success fw-semibold"><i class='bx bx-check me-1'></i>Dijawab dengan benar</span>
                                @else
                                    <span class="text-danger fw-semibold"><i class='bx bx-x me-1'></i>Dijawab salah — Jawaban benar: <strong>{{ $question->answers->where('is_correct', true)->first()?->text }}</strong></span>
                                @endif
                            @else
                                <span class="text-warning"><i class='bx bx-minus me-1'></i>Tidak dijawab</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-2 mb-4">
                <a href="{{ route('quiz.show', $attempt->quiz) }}" class="btn btn-outline-secondary">
                    <i class='bx bx-arrow-back me-1'></i>Kembali ke Detail Kuis
                </a>
            </div>

        </div>
    </div>

</x-app>
