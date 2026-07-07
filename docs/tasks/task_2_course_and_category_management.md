# Task 2: Manajemen Kategori dan Kursus (Courses & Categories)

## Deskripsi
Tugas ini bertujuan untuk membangun struktur dasar manajemen pembelajaran dengan membuat fitur pengelolaan `Categories` (Kategori Kursus) dan `Courses` (Kursus).

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Pembuatan skema migration untuk tabel `categories` dan `courses` sesuai dengan spesifikasi PRD.
2. **Relasi Data**:
   - `Courses` berelasi *many-to-one* (BelongsTo) dengan `Categories` (`category_id`).
   - `Courses` berelasi *many-to-one* (BelongsTo) dengan `Users` melalui `instructor_id`.
3. **Data Dummy (Seeder)**:
   - Buat seeder untuk data `categories` (contoh: N5, N4, Kanji, Grammar).
   - Buat seeder untuk beberapa kursus dummy yang ditautkan ke kategori tersebut dan ditugaskan kepada user yang memiliki *role* `instructor`.
4. **Logika CRUD**:
   - Backend controller dan antarmuka untuk CRUD Kategori (hanya admin).
   - Backend controller dan antarmuka untuk CRUD Kursus (admin dan instructor terkait).
   - Pastikan mengikuti konvensi penamaan (routes, controllers, models) yang sudah ada di proyek.
