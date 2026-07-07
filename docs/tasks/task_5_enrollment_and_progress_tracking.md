# Task 5: Pendaftaran Kursus dan Pelacakan Progres (Enrollments & Progress)

## Deskripsi
Mengembangkan fitur inti bagi pengguna berstatus siswa (student) untuk dapat mendaftar kursus dan melacak kemajuan belajarnya di dalam kursus tersebut.

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Pembuatan tabel pivot `enrollments` (pendaftaran kursus).
   - Pembuatan tabel `progress` (riwayat materi yang sudah dipelajari).
2. **Relasi Data**:
   - `Enrollments` menghubungkan tabel `Users` (student) dengan `Courses`.
   - `Progress` menghubungkan tabel `Users` (student) dengan `Lessons`.
3. **Data Dummy (Seeder)**:
   - Buat seeder yang mendaftarkan siswa dummy ke dalam beberapa kursus secara acak.
   - Tandai beberapa *lessons* sebagai selesai (completed) pada tabel `progress` untuk siswa tersebut sebagai data awal visualisasi progres.
4. **Logika Fitur**:
   - API / endpoint untuk melakukan *enroll* (daftar kursus).
   - Mekanisme (misalnya melalui endpoint khusus) untuk menandai suatu *lesson* berstatus "selesai".
   - Perhitungan persentase progres yang dikembalikan ke tampilan (*views*) untuk memvisualisasikan bilah progres siswa.
