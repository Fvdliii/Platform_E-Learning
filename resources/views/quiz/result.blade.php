<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-5">

                    @if($attempt->passed)
                        <div class="mb-4">
                            <i class='bx bxs-check-circle text-success' style="font-size: 6rem;"></i>
                        </div>
                        <h2 class="fw-bold text-success mb-2">Selamat! Anda Lulus. 🎉</h2>
                        <p class="text-muted fs-5 mb-4">Anda berhasil menyelesaikan kuis <strong>{{ $attempt->quiz->title }}</strong>.</p>
                    @else
                        <div class="mb-4">
                            <i class='bx bxs-x-circle text-danger' style="font-size: 6rem;"></i>
                        </div>
                        <h2 class="fw-bold text-danger mb-2">Belum Lulus</h2>
                        <p class="text-muted fs-5 mb-4">Anda belum mencapai nilai minimum untuk kuis <strong>{{ $attempt->quiz->title }}</strong>. Jangan menyerah!</p>
                    @endif

                    {{-- Score Card --}}
                    <div class="card bg-light border-0 shadow-sm mb-4 mx-auto" style="max-width: 360px;">
                        <div class="card-body p-4">
                            <div class="text-muted text-uppercase fw-bold small mb-2 letter-spacing-1">Nilai Anda</div>
                            <div class="display-1 fw-bold {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                                {{ $attempt->score }}
                            </div>
                            <div class="text-muted small mt-1">dari nilai maksimum <strong>100</strong></div>
                            
                            <hr>

                            {{-- Breakdown soal --}}
                            <div class="row text-center g-2 mt-1">
                                <div class="col-4">
                                    <div class="fw-bold fs-4 text-success">{{ $attempt->correct_count }}</div>
                                    <div class="text-muted small">Benar</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-4 text-danger">{{ 10 - $attempt->correct_count }}</div>
                                    <div class="text-muted small">Salah</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-4">10</div>
                                    <div class="text-muted small">Total Soal</div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between small text-muted">
                                <span>Nilai minimum lulus:</span>
                                <strong class="text-dark">{{ $attempt->quiz->passing_score }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Progress bar nilai --}}
                    <div class="mb-4 text-start">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progres Nilai</span>
                            <span>{{ $attempt->score }}/100</span>
                        </div>
                        <div class="progress" style="height: 12px; border-radius: 10px;">
                            <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}" 
                                role="progressbar" 
                                style="width: {{ $attempt->score }}%; border-radius: 10px;"
                                aria-valuenow="{{ $attempt->score }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        @if(!$attempt->passed)
                            <div class="text-end small text-muted mt-1">Butuh {{ $attempt->quiz->passing_score - $attempt->score }} poin lagi untuk lulus</div>
                        @endif
                    </div>

                    {{-- Catatan dari instruktur --}}
                    @if($attempt->note)
                        <div class="alert alert-warning border-0 shadow-sm text-start mb-4 d-flex gap-3 align-items-start">
                            <i class='bx bx-comment-detail fs-4 mt-1'></i>
                            <div>
                                <div class="fw-bold mb-1">📝 Catatan dari Instruktur</div>
                                <p class="mb-0">{{ $attempt->note }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('course.show', $attempt->quiz->course) }}" class="btn btn-primary btn-lg px-4 shadow-sm">
                            <i class='bx bx-arrow-back me-2'></i>Kembali ke Kursus
                        </a>
                        @if(!$attempt->passed)
                            <a href="{{ route('student.quiz.show', $attempt->quiz) }}" class="btn btn-outline-danger btn-lg px-4 shadow-sm">
                                <i class='bx bx-refresh me-2'></i>Ulangi Kuis
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app>
