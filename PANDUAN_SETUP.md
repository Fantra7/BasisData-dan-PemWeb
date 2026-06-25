# SIAKAD STT-NF — Panduan Setup & Catatan Perbaikan

Sistem Informasi Akademik berbasis **Laravel 12 + Filament 4** dengan database
`siakad_kelompok3.sql`. Dokumen ini menjelaskan cara menjalankan project dan
rangkuman perbaikan yang dilakukan agar sesuai ketentuan Final Project serta
lebih realistis (real-world).

## 1. Kebutuhan
- PHP >= 8.3, Composer
- MySQL / MariaDB
- Node.js + NPM (untuk build aset Filament/Vite)

## 2. Langkah Menjalankan
```bash
# 1) Install dependency
composer install
npm install

# 2) Siapkan environment
cp .env.example .env        # jika belum ada .env
php artisan key:generate

# 3) Buat database lalu IMPOR data
#    Database default: siakad_kelompok3 (lihat .env -> DB_DATABASE)
#    Impor file siakad_kelompok3.sql via phpMyAdmin / mysql CLI:
mysql -u root siakad_kelompok3 < siakad_kelompok3.sql

# 4) Jalankan migrasi INFRASTRUKTUR (hanya tabel Laravel + izin Spatie)
#    Tabel akademik TIDAK dibuat lewat migrate karena sudah ada di SQL.
php artisan migrate

# 5) Sinkronkan role Spatie + hash password (WAJIB sekali jalan)
php artisan db:seed --class=Database\\Seeders\\SyncRoleSeeder

# 6) Symlink storage (agar foto tampil) + build aset
php artisan storage:link
npm run build

# 7) Jalankan
php artisan serve
```

## 3. Akun Login (tidak ditambah / dihapus)
Login memakai **email** + password. Setelah `SyncRoleSeeder` dijalankan, semua
password lama otomatis di-hash. Password default semua akun lama: **`123456`**.

| Role      | Contoh Email                | Password |
|-----------|-----------------------------|----------|
| Admin     | admin1@kampus.ac.id         | 123456   |
| Dosen     | ahmad@kampus.ac.id          | 123456   |
| Mahasiswa | (lihat tabel user, mhs01..) | 123456   |

> Akun baru yang dibuat admin (mahasiswa/dosen) otomatis berpassword `123456`
> dan email `username@sttnf.ac.id`.

## 4. Ringkasan Perbaikan

### A. Database (`siakad_kelompok3.sql`) — tanpa menambah tabel baru
- `dosen`: ditambah kolom **`id_prodi`** (+ FK ke `prodi`) dan **`foto`** —
  sebelumnya dipakai di kode tapi tidak ada di tabel (penyebab error).
- `mahasiswa`: ditambah kolom **`foto`**.
- Trigger baru **`trg_huruf_nilai_update`** (BEFORE UPDATE pada `nilai`) agar
  saat dosen mengedit nilai, `nilai_huruf` ikut dihitung ulang + validasi 0–100.
- Semua user (admin, dosen, mahasiswa) dan datanya **dipertahankan**.

### B. Autentikasi & Sinkronisasi
- `SyncRoleSeeder`: menautkan role Spatie ke tiap user **dan** meng-hash ulang
  password teks-polos legacy menjadi bcrypt (login Filament jadi berfungsi).
- Observer `MahasiswaObserver` & `DosenObserver`: saat admin menghapus data
  mahasiswa/dosen, akun login terkait ikut terhapus (akun admin dilindungi).
  Relasi FK `ON DELETE CASCADE` memastikan perubahan admin langsung mengikuti.
- Model `Nilai`: `nilai_huruf` dihitung otomatis di level aplikasi (A/B/C/D/E)
  baik saat tambah maupun edit, konsisten dengan trigger DB.

### C. Hak Akses (RBAC) — disesuaikan dunia nyata
- **Admin**: CRUD penuh semua data.
- **Dosen**: melihat & mengedit profil dosennya sendiri, input/ubah nilai,
  melihat jadwal & mata kuliah (read-only).
- **Mahasiswa**: hanya boleh mengubah data kontak pribadi (alamat, no HP, foto);
  identitas akademik (NIM, nama, prodi, angkatan, JK) dikunci; melihat jadwal,
  mata kuliah, dan KRS sesuai haknya.
- `JadwalKuliahPolicy` baru: jadwal hanya disusun admin; dosen & mahasiswa
  read-only.
- Mata kuliah dibuka read-only untuk dosen & mahasiswa.

### D. UI/UX
- Panel di-branding **SIAKAD STT-NF**, palet warna kustom (Indigo/Slate),
  font Plus Jakarta Sans, mode terang, sidebar bisa diciutkan.
- Navigasi dikelompokkan: **Data Master**, **Perkuliahan**, **Penilaian**.
- Dashboard menampilkan statistik kampus + grafik untuk semua role.

### E. Migrasi
- Migrasi akademik yang menduplikasi isi SQL (create tabel, FK, view,
  procedure, add_foto) **dihapus** agar `php artisan migrate` tidak bentrok
  dengan database yang diimpor. Hanya tersisa migrasi infrastruktur Laravel
  (users/cache/jobs) dan tabel izin Spatie.
