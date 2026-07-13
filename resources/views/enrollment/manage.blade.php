<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row g-4">

        {{-- Form Tambah Siswa --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class='bx bx-user-plus me-2'></i>Daftarkan Siswa
                </div>
                <div class="card-body">
                    @if ($availableStudents->isEmpty())
                        <p class="text-muted fst-italic mb-0">Semua siswa sudah terdaftar di kursus ini.</p>
                    @else
                        <form action="{{ route('enrollment.admin.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="mb-3">
                                <label for="user_id" class="form-label fw-semibold">Pilih Siswa</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="">-- Pilih siswa --</option>
                                    @foreach ($availableStudents as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class='bx bx-plus me-1'></i>Daftarkan
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-footer text-muted small bg-light">
                    Kursus: <strong>{{ $course->title }}</strong>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('course.show', $course) }}" class="btn btn-outline-secondary w-100">
                    <i class='bx bx-arrow-back me-1'></i>Kembali ke Detail Kursus
                </a>
            </div>
        </div>

        {{-- Tabel Siswa Terdaftar --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold border-bottom">
                    <i class='bx bx-group me-2'></i>Siswa Terdaftar
                    <span class="badge bg-primary ms-2">{{ $enrollments->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($enrollments->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class='bx bx-user-x' style="font-size: 3rem;"></i>
                            <p class="mt-2">Belum ada siswa yang terdaftar di kursus ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Siswa</th>
                                        <th>Email</th>
                                        <th>Tgl Daftar</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $enrollment->user->name }}</td>
                                            <td class="text-muted small">{{ $enrollment->user->email }}</td>
                                            <td class="text-muted small">{{ $enrollment->enrolled_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('enrollment.admin.destroy', $enrollment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Hapus {{ $enrollment->user->name }} dari kursus ini?')">
                                                        <i class='bx bx-user-minus'></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</x-app>
