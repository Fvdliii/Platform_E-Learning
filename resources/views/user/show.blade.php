<div class="row g-3 mb-4">
    <div class="col-md-4 text-center">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('niceadmin/img/noprofil.png') }}"
            alt="Avatar" class="img-fluid rounded-circle border border-3 border-primary" style="max-width: 200px;">
    </div>
    <div class="col-md-8">
        <h4 class="fw-bold mb-3">{{ $user->name }}</h4>
        <div class="mb-3">
            <span class="badge bg-primary fs-6">{{ $user->role }}</span>
        </div>
        <div class="list-group list-group-flush">
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-envelope me-2'></i>Email
                    </div>
                    <div class="col-8 fw-semibold">
                        {{ $user->email }}
                    </div>
                </div>
            </div>
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-calendar-plus me-2'></i>Dibuat
                    </div>
                    <div class="col-8">
                        {{ $user->created_at->diffForHumans() }}
                        <small class="text-muted d-block">{{ $user->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-calendar-edit me-2'></i>Diubah
                    </div>
                    <div class="col-8">
                        {{ $user->updated_at->diffForHumans() }}
                        <small class="text-muted d-block">{{ $user->updated_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($user->role === 'student' && isset($enrollments) && isset($availableCourses))
    <hr>
    <div class="row mt-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-3"><i class='bx bx-book-reader me-2'></i>Kursus yang Diikuti</h5>
            <div class="list-group">
                @forelse ($enrollments as $enrollment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $enrollment->course->title }}</span><br>
                            <small class="text-muted">Terdaftar pada: {{ $enrollment->enrolled_at->format('d M Y') }}</small>
                        </div>
                        <form action="{{ route('enrollment.admin.destroy', $enrollment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus dari kursus"
                                onclick="return confirm('Hapus siswa dari kursus ini?')">
                                <i class='bx bx-x'></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="list-group-item text-muted fst-italic">Belum mengikuti kursus apapun.</div>
                @endforelse
            </div>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold mb-3"><i class='bx bx-plus-circle me-2'></i>Daftarkan ke Kursus</h5>
            <div class="card bg-light border-0">
                <div class="card-body">
                    @if ($availableCourses->isEmpty())
                        <p class="text-muted fst-italic mb-0">Siswa ini sudah terdaftar di semua kursus.</p>
                    @else
                        <form action="{{ route('enrollment.admin.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="mb-3">
                                <label class="form-label">Pilih Kursus</label>
                                <select name="course_id" class="form-select" required>
                                    <option value="">-- Pilih Kursus --</option>
                                    @foreach ($availableCourses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class='bx bx-plus me-1'></i>Daftarkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif