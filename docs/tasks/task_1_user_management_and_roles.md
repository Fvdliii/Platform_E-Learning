# Task 1: Menyesuaikan Sistem Autentikasi dan Manajemen Pengguna (User Management)

## Deskripsi
Tugas ini berfokus pada penyesuaian sistem autentikasi dan manajemen pengguna (user management) bawaan agar selaras dengan spesifikasi pada PRD. Kita perlu mengakomodasi peran (roles) yang baru yaitu `admin`, `instructor`, dan `student`.

## Kebutuhan (Requirements)
1. **Struktur Tabel**:
   - Sesuaikan struktur tabel `users` (atau tabel relasi *roles* terkait jika ada) untuk mengakomodasi role pengguna sesuai spesifikasi PRD. Pastikan format penyimpanan rolenya mendukung identifikasi 'admin', 'instructor', dan 'student'.
2. **Data Dummy (Seeder)**:
   - Buat atau perbarui `UserSeeder` (dan/atau *RoleSeeder*) untuk mengenerate pengguna dummy yang mewakili setiap peran (`admin`, `instructor`, dan `student`). Ini wajib dilakukan untuk mempermudah pengujian dan visualisasi data di dashboard.
3. **Logika CRUD (Create, Read, Update, Delete)**:
   - Sesuaikan operasi CRUD pada controller modul pengguna agar dapat membaca, memfilter, mengenali, dan memproses data berdasarkan peran pengguna (user role) yang baru.
4. **Konsistensi Kode (PENTING)**:
   - Wajib mengikuti *coding style*, pola arsitektur, dan konvensi penamaan yang sudah ada (existing) pada modul pengguna saat ini (menggunakan *Laravel NiceAdmin Bootstrap*).
   - Dilarang membuat pola, struktur controller, atau *design pattern* baru yang tidak konsisten dengan bawaan template / proyek yang ada.

## Target Validasi
- Login dapat dilakukan dengan masing-masing akun peran (admin, instructor, student).
- Data dummy berhasil divisualisasikan di tabel pengguna.
- Pembuatan/pengubahan user melalui antarmuka / CRUD sudah mendeteksi role dengan benar tanpa merusak arsitektur bawaan.
