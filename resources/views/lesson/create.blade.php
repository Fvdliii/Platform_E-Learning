<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('lesson.store') }}" method="post" enctype="multipart/form-data" class="form">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Judul Materi</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            name="title" required value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Konten Teks</label>
                        <textarea class="form-control @error('content') is-invalid @enderror"
                            id="content" name="content" rows="6">{{ old('content') }}</textarea>
                        @error('content')
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
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label required">Tipe Materi</label>
                        <select class="form-select select2-default @error('type') is-invalid @enderror"
                            id="type" name="type" required onchange="toggleFileField()">
                            <option value="text" @selected(old('type') == 'text')>Teks</option>
                            <option value="video" @selected(old('type') == 'video')>Video</option>
                            <option value="pdf" @selected(old('type') == 'pdf')>PDF</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="file_upload_group" style="display: none;">
                        <label for="file" class="form-label">Upload File (PDF/Video)</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="file_link_group" style="display: none;">
                        <label for="file_link" class="form-label">Atau Link Video (YouTube embed)</label>
                        <input class="form-control @error('file_link') is-invalid @enderror" type="text" id="file_link" name="file_link" value="{{ old('file_link') }}">
                        @error('file_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label required">Urutan</label>
                        <input class="form-control @error('order') is-invalid @enderror" type="number" id="order"
                            name="order" required min="1" value="{{ old('order', 1) }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('lesson.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            function toggleFileField() {
                var type = $('#type').val();
                if (type === 'pdf') {
                    $('#file_upload_group').show();
                    $('#file_link_group').hide();
                } else if (type === 'video') {
                    $('#file_upload_group').show();
                    $('#file_link_group').show();
                } else {
                    $('#file_upload_group').hide();
                    $('#file_link_group').hide();
                }
            }

            $(document).ready(function() {
                toggleFileField();
            });
        </script>
    @endpush

</x-app>
