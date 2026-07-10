<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('quiz.update', $quiz) }}" method="post" class="form">
            @csrf
            @method('put')

            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Judul Kuis</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            name="title" required value="{{ old('title', $quiz->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Kuis</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4">{{ old('description', $quiz->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="course_id" class="form-label required">Kursus</label>
                        <select class="form-select select2-default @error('course_id') is-invalid @enderror"
                            id="course_id" name="course_id" required>
                            <option value="">Pilih Kursus</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id) == $course->id)>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="passing_score" class="form-label required">Passing Score (0-100)</label>
                        <input class="form-control @error('passing_score') is-invalid @enderror" type="number"
                            id="passing_score" name="passing_score" required min="0" max="100" value="{{ old('passing_score', $quiz->passing_score) }}">
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('quiz.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>

</x-app>
