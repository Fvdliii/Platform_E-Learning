# Task 4: Sistem Kuis dan Evaluasi (Quizzes, Questions, Answers)

## Deskripsi
Membangun sistem evaluasi berupa kuis pilihan ganda berbasis JLPT untuk mengevaluasi pemahaman siswa di setiap kursus.

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Pembuatan migration untuk tabel `quizzes` (data kuis dan passing score).
   - Pembuatan migration untuk tabel `questions` (data pertanyaan).
   - Pembuatan migration untuk tabel `answers` (opsi jawaban dengan penanda jawaban benar `is_correct`).
2. **Relasi Data**:
   - `Quizzes` berelasi dengan `Courses`.
   - `Questions` berelasi dengan `Quizzes`.
   - `Answers` berelasi dengan `Questions`.
3. **Data Dummy (Seeder)**:
   - Generate seeder untuk kuis evaluasi.
   - Setiap kuis dummy harus diisi dengan set pertanyaan (minimal 5 pertanyaan) dan tiap pertanyaan memiliki 4 opsi jawaban (salah satunya berstatus benar).
4. **Logika CRUD**:
   - Antarmuka manajemen kuis (CRUD kuis).
   - Antarmuka spesifik untuk menambah, mengedit, dan menghapus soal beserta jawaban di dalam sebuah kuis.
