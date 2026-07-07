# Task 3: Manajemen Materi Pembelajaran (Lessons)

## Deskripsi
Setelah struktur kursus selesai, tugas selanjutnya adalah mengembangkan fitur untuk mengelola materi (lesson) spesifik di dalam masing-masing kursus tersebut.

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Pembuatan migration untuk tabel `lessons` yang meliputi informasi judul materi, urutan, tipe konten (video, teks, audio, dokumen), serta lokasi konten (URL/Path).
2. **Relasi Data**:
   - Setiap entri di `Lessons` berelasi dengan sebuah `Courses` (`course_id`).
3. **Data Dummy (Seeder)**:
   - Buat seeder untuk mengenerate setidaknya 3-5 materi pelajaran dummy yang bervariasi (video/teks) untuk setiap kursus dummy yang telah dibuat di Task 2.
4. **Logika CRUD**:
   - Fitur CRUD lengkap untuk materi (lesson) di dalam halaman manajemen suatu kursus.
   - Manajemen *ordering* (urutan) dari lesson tersebut agar terstruktur saat diakses oleh siswa.
   - Menjaga konsistensi arsitektur kode dengan *style* saat ini.
