<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row g-4">
        {{-- Form Terbitkan Sertifikat --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class='bx bx-certification me-2'></i>Terbitkan Sertifikat Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('certificate.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="course_id" class="form-label fw-semibold">Pilih Kursus</label>
                            <select name="course_id" id="course_id" class="form-select" required onchange="updateStudents()">
                                <option value="">-- Pilih kursus --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" data-students='@json($course->enrollments->map(function($e) { return ["id" => $e->user->id, "name" => $e->user->name, "email" => $e->user->email]; }))'>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">Pilih Siswa</label>
                            <select name="user_id" id="user_id" class="form-select" required disabled>
                                <option value="">-- Pilih siswa --</option>
                            </select>
                            <small class="text-muted mt-1 d-block">Hanya menampilkan siswa yang terdaftar di kursus yang dipilih.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class='bx bx-send me-1'></i>Terbitkan Sertifikat
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Sertifikat --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold border-bottom d-flex justify-content-between align-items-center">
                    <div><i class='bx bx-list-ul me-2'></i>Daftar Sertifikat Terbit</div>
                    <span class="badge bg-primary">{{ $certificates->count() }} Total</span>
                </div>
                <div class="card-body p-0">
                    @if ($certificates->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class='bx bx-certification' style="font-size: 4rem;"></i>
                            <p class="mt-2">Belum ada sertifikat yang diterbitkan.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Sertifikat</th>
                                        <th>Siswa</th>
                                        <th>Kursus</th>
                                        <th>Tanggal Terbit</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($certificates as $cert)
                                        <tr>
                                            <td class="font-monospace small">{{ $cert->certificate_number }}</td>
                                            <td class="fw-semibold">
                                                {{ $cert->user->name }}<br>
                                                <small class="text-muted fw-normal">{{ $cert->user->email }}</small>
                                            </td>
                                            <td>{{ $cert->course->title }}</td>
                                            <td>{{ $cert->issued_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('certificate.show', $cert) }}" target="_blank" class="btn btn-info btn-sm" title="Lihat / Cetak">
                                                        <i class='bx bx-printer'></i>
                                                    </a>
                                                    <form action="{{ route('certificate.destroy', $cert) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Cabut Sertifikat"
                                                            onclick="return confirm('Apakah Anda yakin ingin mencabut sertifikat ini?')">
                                                            <i class='bx bx-trash'></i>
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
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateStudents() {
            const courseSelect = document.getElementById('course_id');
            const userSelect = document.getElementById('user_id');
            
            // Clear current options
            userSelect.innerHTML = '<option value="">-- Pilih siswa --</option>';
            
            if (courseSelect.selectedIndex > 0) {
                const selectedOption = courseSelect.options[courseSelect.selectedIndex];
                const students = JSON.parse(selectedOption.getAttribute('data-students') || '[]');
                
                if (students.length > 0) {
                    userSelect.disabled = false;
                    students.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.name} (${student.email})`;
                        userSelect.appendChild(option);
                    });
                } else {
                    userSelect.disabled = true;
                    userSelect.innerHTML = '<option value="">-- Tidak ada siswa di kursus ini --</option>';
                }
            } else {
                userSelect.disabled = true;
            }
        }
    </script>
    @endpush

</x-app>
