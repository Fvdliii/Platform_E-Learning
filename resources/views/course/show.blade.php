<div class="p-2">
    <div class="row">
        <div class="col-md-3">
            <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('niceadmin/img/noprofil.png') }}"
                alt="Thumbnail" class="w-100 rounded">
        </div>
        <div class="col-md-9">
            <table class="table table-borderless">
                <tr>
                    <th>Judul</th>
                    <td>: {{ $course->title }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>: {{ $course->category->name }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>: {{ $course->level }}</td>
                </tr>
                <tr>
                    <th>Instruktur</th>
                    <td>: {{ $course->instructor->name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>:
                        @if ($course->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>: {{ $course->description ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>: {{ $course->created_at->format('d M Y, H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
