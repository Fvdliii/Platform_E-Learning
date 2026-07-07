# Task 6: Sistem Ulasan dan Sertifikat (Reviews & Certificates)

## Deskripsi
Tahap akhir yaitu menambahkan fitur interaksi berupa pemberian ulasan dari siswa (review) serta penerbitan sertifikat digital otomatis sebagai gamifikasi dan validasi kelulusan.

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Pembuatan tabel `reviews` untuk menyimpan *rating* dan komentar.
   - Pembuatan tabel `certificates` untuk menyimpan riwayat sertifikat yang diraih.
2. **Relasi Data**:
   - `Reviews` menghubungkan `Users` (siswa) dan `Courses`.
   - `Certificates` menghubungkan `Users` (siswa) dan `Courses`.
3. **Data Dummy (Seeder)**:
   - Buat seeder yang menghasilkan ulasan acak (rating 1-5 dengan komentar) dari siswa terdaftar di suatu kursus.
   - Buat seeder sertifikat dummy bagi siswa yang dalam *progress* di-set seolah-olah sudah 100% lulus.
4. **Logika Fitur**:
   - Form dan validasi pemberian ulasan (hanya siswa yang terdaftar di kursus yang bisa memberikan).
   - Logika penentuan kelulusan: jika progres 100% dan nilai kuis lulus, sistem otomatis men-generate data di tabel sertifikat.
