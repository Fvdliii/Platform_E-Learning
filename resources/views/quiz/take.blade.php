<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold text-white">{{ $quiz->title }}</h4>
                            <p class="mb-0 text-white-50">{{ $quiz->course->title }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-primary fs-6">Soal: {{ $quiz->questions->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    @if($quiz->description)
                        <div class="alert alert-info mb-4 border-0 shadow-sm">
                            <i class='bx bx-info-circle me-2'></i>{{ $quiz->description }}
                        </div>
                    @endif

                    @if($quiz->questions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class='bx bx-question-mark' style="font-size: 4rem;"></i>
                            <p class="mt-3">Belum ada soal untuk kuis ini. Silakan hubungi instruktur Anda.</p>
                            <a href="{{ route('course.show', $quiz->course) }}" class="btn btn-outline-secondary mt-2">Kembali ke Kursus</a>
                        </div>
                    @else
                        <form action="{{ route('student.quiz.submit', $quiz) }}" method="POST" id="quizForm">
                            @csrf
                            
                            @foreach($quiz->questions as $index => $question)
                                <div class="mb-5 question-block">
                                    <h5 class="fw-bold mb-3">
                                        <span class="badge bg-secondary me-2">{{ $index + 1 }}</span> 
                                        {{ $question->text }}
                                    </h5>
                                    
                                    <div class="list-group">
                                        @foreach($question->answers as $answer)
                                            <label class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer border-0 shadow-sm mb-2 rounded bg-light" style="cursor: pointer;">
                                                <input class="form-check-input me-3 mt-0" type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" required>
                                                <span>{{ $answer->text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('course.show', $quiz->course) }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-success btn-lg px-5 shadow" onclick="return confirm('Apakah Anda yakin sudah selesai mengerjakan kuis ini?')">
                                    <i class='bx bx-check-double me-2'></i>Kumpulkan Kuis
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Tambahkan efek visual pada radio button pilihan ganda
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Reset warna di grup soal yang sama
                    const groupName = this.getAttribute('name');
                    const groupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
                    groupRadios.forEach(r => {
                        const parentLabel = r.closest('label');
                        parentLabel.classList.remove('bg-primary', 'text-white');
                        parentLabel.classList.add('bg-light');
                    });
                    
                    // Set warna untuk yang dipilih
                    if(this.checked) {
                        const parentLabel = this.closest('label');
                        parentLabel.classList.remove('bg-light');
                        parentLabel.classList.add('bg-primary', 'text-white');
                    }
                });
            });
        });
    </script>
    @endpush

</x-app>
