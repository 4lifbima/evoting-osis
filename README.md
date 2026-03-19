# E-Voting OSIS

Sistem e-voting berbasis web untuk pemilihan pengurus OSIS dengan antarmuka modern, panel admin lengkap, dan quick count real-time.

## Daftar Isi

1. [Tentang Proyek](#tentang-proyek)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4. [Arsitektur dan Struktur Proyek](#arsitektur-dan-struktur-proyek)
5. [Skema Database](#skema-database)
6. [Alur Penggunaan Sistem](#alur-penggunaan-sistem)
7. [Instalasi dan Menjalankan Proyek](#instalasi-dan-menjalankan-proyek)
8. [Konfigurasi](#konfigurasi)
9. [Akun Default](#akun-default)
10. [Dokumentasi API](#dokumentasi-api)
11. [Keamanan dan Validasi](#keamanan-dan-validasi)
12. [Troubleshooting](#troubleshooting)
13. [Rencana Pengembangan](#rencana-pengembangan)

## Tentang Proyek

E-Voting OSIS adalah aplikasi pemilihan digital yang dirancang untuk kebutuhan sekolah, dengan fokus pada:

- Proses voting yang sederhana dan cepat untuk siswa.
- Kontrol penuh dari sisi panitia/admin.
- Monitoring hasil secara real-time.
- Pengelolaan data kandidat, pemilih, dan periode voting dalam satu sistem.

Sistem ini menerapkan model one-user-one-vote melalui status `has_voted` pada data pengguna.

## Fitur Utama

### Sisi Siswa (User)

- Login menggunakan akun siswa.
- Melihat daftar kandidat beserta foto, visi, dan misi.
- Memilih satu kandidat (pilihan tidak dapat diubah).
- Blokir otomatis jika sudah pernah memilih.
- Logout otomatis ketika mencoba memilih ulang.

### Sisi Admin

- Dashboard statistik: total pemilih, sudah memilih, belum memilih, dan partisipasi.
- CRUD kandidat (tambah, edit, hapus) termasuk upload foto.
- CRUD pemilih/siswa.
- Reset data voting (menghapus vote + log quick count, reset status pemilih).
- Pengaturan voting: nama event, periode, waktu mulai, waktu selesai, status aktif.
- Halaman hasil voting dan quick count real-time.

### Publik / Umum

- Halaman quick count publik dengan pembaruan data otomatis.
- Visualisasi grafik dan progress per kandidat.

## Teknologi yang Digunakan

### Backend

- PHP (native/procedural)
- MySQL (mysqli)

### Frontend

- Tailwind CSS (CDN)
- jQuery
- ApexCharts
- DataTables
- SweetAlert2
- Iconify

### Lingkungan Pengembangan

- Laragon (disarankan)
- Apache / Nginx + PHP 8+
- MySQL / MariaDB

## Arsitektur dan Struktur Proyek

Proyek menggunakan pola modular sederhana:

- `config/`: konfigurasi aplikasi dan database.
- `helpers/`: fungsi utilitas, auth, statistik, CSRF token, dan helper respon.
- `admin/`: halaman panel admin.
- `user/`: halaman voting siswa.
- `api/`: endpoint JSON untuk operasi AJAX.
- `uploads/candidates/`: penyimpanan foto kandidat.

Ringkas struktur utama:

```text
evoting-osis/
├── index.php
├── login.php
├── logout.php
├── quick-count.php
├── db_evoting_osis.sql
├── config/
│   ├── app.php
│   └── database.php
├── helpers/
│   ├── auth.php
│   └── functions.php
├── api/
│   ├── dashboard.php
│   ├── candidates.php
│   ├── voters.php
│   ├── settings.php
│   ├── vote.php
│   └── quick-count.php
├── admin/
│   ├── index.php
│   ├── candidates.php
│   ├── voters.php
│   ├── settings.php
│   ├── results.php
│   └── quick-count.php
├── user/
│   └── index.php
└── uploads/
		└── candidates/
```

## Skema Database

Database utama: `evoting_osis`

Tabel inti:

1. `users`
2. `candidates`
3. `votes`
4. `voting_settings`
5. `vote_logs`

Relasi penting:

- `votes.user_id` -> `users.id`
- `votes.candidate_id` -> `candidates.id`
- `vote_logs.candidate_id` -> `candidates.id`

Catatan:

- Status sudah memilih ditandai di `users.has_voted`.
- Riwayat quick count per kandidat disimpan di `vote_logs`.

## Alur Penggunaan Sistem

### 1. Admin menyiapkan voting

- Login admin.
- Atur nama voting, periode, jadwal, dan status aktif.
- Input data kandidat.
- Input data pemilih.

### 2. Siswa melakukan voting

- Login ke sistem.
- Sistem memeriksa:
	- apakah voting aktif,
	- apakah siswa sudah memilih.
- Siswa memilih satu kandidat.
- Sistem menyimpan suara, memperbarui status siswa, dan menulis log quick count.

### 3. Monitoring hasil

- Admin memantau dashboard, hasil, dan quick count internal.
- Publik dapat memantau quick count dari halaman publik.

## Instalasi dan Menjalankan Proyek

### Prasyarat

- PHP 8.0 atau lebih baru
- MySQL/MariaDB
- Web server (Apache/Nginx)
- Laragon (opsional, direkomendasikan di Windows)

### Langkah Instalasi

1. Letakkan folder proyek di direktori web server.
	 - Contoh Laragon: `D:/laragon/www/evoting-osis`
2. Buat database dan import file SQL:
	 - Buka phpMyAdmin/HeidiSQL.
	 - Import `db_evoting_osis.sql`.
3. Sesuaikan konfigurasi aplikasi:
	 - `config/database.php`
	 - `config/app.php`
4. Pastikan folder upload dapat ditulis:
	 - `uploads/candidates/`
5. Jalankan aplikasi melalui browser:
	 - `http://localhost/evoting-osis`

## Konfigurasi

### 1. `config/app.php`

Parameter utama:

- `APP_NAME`: nama aplikasi.
- `APP_VERSION`: versi aplikasi.
- `BASE_URL`: base path URL aplikasi.
- `UPLOAD_DIR`: direktori upload file.
- `SESSION_TIMEOUT`: durasi sesi.

### 2. `config/database.php`

Parameter database:

- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`

Pastikan nilai `BASE_URL` sesuai subfolder lokal Anda.

## Akun Default

Data awal disediakan dari SQL seed:

- Admin
	- Username: `admin`
	- Password: `admin123`
- Siswa contoh
	- Username: `siswa01` s/d `siswa10`
	- Password default: `admin123`

Disarankan mengganti password default setelah deployment awal.

## Dokumentasi API

Semua endpoint berada di prefix `/api`.

Format respon umum:

```json
{
	"success": true,
	"message": "...",
	"data": {}
}
```

### 1. `GET /api/dashboard.php`

- Akses: admin
- Fungsi: mengambil statistik ringkas dashboard.

### 2. `GET|POST|DELETE /api/candidates.php`

- Akses: admin
- Fungsi:
	- `GET`: daftar/detail kandidat (`?id=...`)
	- `POST`: tambah atau update kandidat
	- `DELETE`: hapus kandidat (`?id=...`)

### 3. `GET|POST|DELETE /api/voters.php`

- Akses: admin
- Fungsi:
	- `GET`: daftar/detail pemilih (`?id=...`)
	- `POST`: tambah/update pemilih
	- `POST action=reset_votes`: reset seluruh data voting
	- `DELETE`: hapus pemilih (`?id=...`)

### 4. `GET|POST /api/settings.php`

- Akses: admin
- Fungsi:
	- `GET`: ambil pengaturan voting
	- `POST`: simpan/update pengaturan voting

### 5. `POST /api/vote.php`

- Akses: user login
- Fungsi: menyimpan pilihan kandidat siswa.
- Body:
	- `candidate_id`

### 6. `GET /api/quick-count.php`

- Akses: publik
- Fungsi: mengambil perolehan suara real-time + timeline per kandidat.

## Keamanan dan Validasi

Yang sudah diterapkan di sistem:

- Autentikasi berbasis session.
- Pemisahan hak akses admin vs user.
- Sanitasi input dengan helper `sanitize()`.
- Password hashing dengan `password_hash()` + verifikasi `password_verify()`.
- Prepared statements di banyak operasi database.
- Pembatasan format upload foto kandidat (`jpg`, `jpeg`, `png`, `webp`).
- Proteksi one vote per user via `has_voted` dan cek status voting aktif.

Catatan penguatan (recommended):

- Wajibkan verifikasi CSRF token di seluruh request sensitif (POST/DELETE).
- Tambahkan rate limiting pada endpoint login.
- Tambahkan validasi ukuran file upload.
- Gunakan transaction + locking tambahan untuk skenario trafik tinggi.

## Troubleshooting

### 1. Tidak bisa konek database

- Cek host, user, password, dan nama database di konfigurasi.
- Pastikan service MySQL berjalan.

### 2. Halaman tidak sesuai path

- Cek nilai `BASE_URL` di `config/app.php`.
- Pastikan URL akses browser sesuai subfolder proyek.

### 3. Foto kandidat tidak tampil

- Pastikan file berhasil terupload ke `uploads/candidates/`.
- Cek permission folder upload.

### 4. Siswa tidak bisa voting padahal belum memilih

- Cek status waktu voting di pengaturan admin.
- Cek nilai `is_active`, `start_time`, `end_time` pada `voting_settings`.

## Rencana Pengembangan

Beberapa fitur lanjutan yang dapat ditambahkan:

1. Export laporan hasil voting ke PDF/Excel.
2. Audit log aktivitas admin.
3. Multi-event voting (lebih dari satu pemilihan per periode).
4. Verifikasi OTP/NISN sebelum voting.
5. Dashboard analitik partisipasi per kelas.

---

Jika Anda ingin, README ini juga bisa saya lanjutkan dengan:

- diagram arsitektur (Mermaid),
- flowchart alur voting,
- dan dokumentasi deployment ke hosting produksi.
