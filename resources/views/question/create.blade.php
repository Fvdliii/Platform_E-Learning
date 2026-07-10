<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <div class="alert alert-info">
            Menambahkan soal untuk kuis: <strong>{{ $quiz->title }}</strong>
        </div>

        <form action="{{ route('question.store') }}" method="post" class="form">
            @csrf
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

            <div class="mb-4">
                <label for="text" class="form-label required fw-bold">Pertanyaan</label>
                <textarea class="form-control @error('text') is-invalid @enderror"
                    id="text" name="text" rows="3" required>{{ old('text') }}</textarea>
                @error('text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required fw-bold">Opsi Jawaban</label>
                <p class="text-muted small">Pilih satu jawaban yang benar dengan mengklik tombol radio di sebelah kiri.</p>
                @error('correct_answer')
                    <div class="alert alert-danger py-1">{{ $message }}</div>
                @enderror

                @for ($i = 0; $i < 4; $i++)
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name="correct_answer"
                                value="{{ $i }}" @checked(old('correct_answer') == $i)
                                id="correct_{{ $i }}" required>
                        </div>
                        <label class="input-group-text" for="correct_{{ $i }}">
                            {{ chr(65 + $i) }}
                        </label>
                        <input type="text" class="form-control @error('answers.' . $i . '.text') is-invalid @enderror"
                            name="answers[{{ $i }}][text]"
                            placeholder="Opsi {{ chr(65 + $i) }}"
                            value="{{ old('answers.' . $i . '.text') }}"
                            required>
                        @error('answers.' . $i . '.text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endfor
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Soal</button>
            </div>

        </form>

    </div>

</x-app>
