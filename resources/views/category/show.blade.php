<div class="p-2">
    <table class="table table-borderless">
        <tr>
            <th>Nama Kategori</th>
            <td>: {{ $category->name }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>: {{ $category->description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah Kursus</th>
            <td>: {{ $category->courses->count() }} kursus</td>
        </tr>
        <tr>
            <th>Dibuat</th>
            <td>: {{ $category->created_at->format('d M Y, H:i') }}</td>
        </tr>
    </table>
</div>
