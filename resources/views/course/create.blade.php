<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('course.store') }}" method="post" enctype="multipart/form-data" class="form">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input class="form-control @error('thumbnail') is-invalid @enderror" type="file" id="upload"
                        name="thumbnail">
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <img src="{{ asset('niceadmin/img/noprofil.png') }}" alt="Thumbnail" class="w-100 rounded mt-2"
                        id="preview">
                </div>

                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Judul Kursus</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            name="title" required value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label required">Kategori</label>
                                <select class="form-select select2-default @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="level" class="form-label required">Level</label>
                                <select class="form-select select2-default @error('level') is-invalid @enderror"
                                    id="level" name="level" required>
                                    <option value="">Pilih Level</option>
                                    @foreach (['N5', 'N4', 'N3', 'N2', 'N1', 'Umum'] as $lvl)
                                        <option value="{{ $lvl }}" @selected(old('level') == $lvl)>{{ $lvl }}</option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if (Auth::user()->role === 'admin')
                        <div class="mb-3">
                            <label for="instructor_id" class="form-label required">Instruktur</label>
                            <select class="form-select select2-default @error('instructor_id') is-invalid @enderror"
                                id="instructor_id" name="instructor_id" required>
                                <option value="">Pilih Instruktur</option>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        @selected(old('instructor_id') == $instructor->id)>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="instructor_id" value="{{ Auth::id() }}">
                    @endif

                    <div class="mb-3">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select select2-default @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                            <option value="published" @selected(old('status') == 'published')>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('course.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>

</x-app>
