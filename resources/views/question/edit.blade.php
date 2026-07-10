<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <div class="alert alert-info">
            Mengedit soal dalam kuis: <strong>{{ $quiz->title }}</strong>
        </div>

        <form action="{{ route('question.update', $question) }}" method="post" class="form">
            @csrf
            @method('put')

            <div class="mb-4">
                <label for="text" class="form-label required fw-bold">Pertanyaan</label>
                <textarea class="form-control @error('text') is-invalid @enderror"
                    id="text" name="text" rows="3" required>{{ old('text', $question->text) }}</textarea>
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

                @foreach ($question->answers as $i => $answer)
                    <input type="hidden" name="answers[{{ $i }}][id]" value="{{ $answer->id }}">
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name="correct_answer"
                                value="{{ $i }}"
                                @checked(old('correct_answer', $answer->is_correct ? $i : null) == $i)
                                id="correct_{{ $i }}" required>
                        </div>
                        <label class="input-group-text" for="correct_{{ $i }}">
                            {{ chr(65 + $i) }}
                        </label>
                        <input type="text" class="form-control @error('answers.' . $i . '.text') is-invalid @enderror"
                            name="answers[{{ $i }}][text]"
                            placeholder="Opsi {{ chr(65 + $i) }}"
                            value="{{ old('answers.' . $i . '.text', $answer->text) }}"
                            required>
                        @error('answers.' . $i . '.text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>

    </div>

</x-app>
