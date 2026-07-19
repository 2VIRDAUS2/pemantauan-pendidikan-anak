# Pemantauan Pendidikan Anak

Sistem monitoring siswa SD dengan pengajuan izin daring, absensi harian, nilai, jadwal pelajaran, dan notifikasi otomatis. Dibangun dengan **Laravel** dan **Tailwind CSS**.

## Fitur

| Fitur | Guru | Orang Tua |
|-------|------|-----------|
| Dashboard ringkasan | ✅ | ✅ |
| Absensi siswa per kelas | ✅ | — |
| Verifikasi izin | ✅ | — |
| Input & kelola nilai | ✅ | — |
| Lihat jadwal pelajaran | ✅ | ✅ |
| Kelola pengumuman | ✅ | ✅ |
| Pengajuan izin daring | — | ✅ |
| Lihat nilai & presensi anak | — | ✅ |
| Status kehadiran real-time | — | ✅ |

## Teknologi

- **Backend**: Laravel 12, PHP 8.4
- **Database**: MySQL (`db_anak`)
- **Frontend**: Tailwind CSS (CDN), Google Fonts (Inter)
- **Auth**: Session-based + database session driver
- **Notifikasi**: Mail + Database
- **File Storage**: MySQL base64 (file bukti izin)

## Struktur Arsitektur

```
app/
├── Enums/           → Role, StatusPengajuan
├── Models/          → User, Siswa, Presensi, Nilai, PengajuanIzin, JadwalPelajaran, Pengumuman
├── Services/        → Logika bisnis (Dashboard, Presensi, Nilai, Izin, Jadwal, Pengumuman, FileUpload)
├── Http/
│   ├── Controllers/ → Auth, Guru/*, OrangTua/*, JadwalController, PengumumanController
│   ├── Requests/    → PengajuanIzinRequest, VerifikasiIzinRequest, StoreNilaiRequest
│   └── Middleware/   → RoleMiddleware
├── Notifications/   → PengajuanIzinStatusChanged
└── Jobs/            → RekapAbsensiHarian
```

## Role & Akun Demo

Setelah `db:seed`, tersedia 4 akun:

| Role | Email | Password |
|------|-------|----------|
| Guru | guru@sd.test | password |
| Orang Tua | ortu1@sd.test | password |
| Orang Tua | ortu2@sd.test | password |
| Orang Tua | ortu3@sd.test | password |

## Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/[user]/pemantauan-pendidikan-anak.git
cd pemantauan-pendidikan-anak

# 2. Install dependency PHP
composer install

# 3. Copy & sesuaikan konfigurasi database
cp .env.example .env
# edit .env: atur DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai MySQL Anda

# 4. Generate app key
php artisan key:generate

# 5. Buat database MySQL (sesuai nama di .env)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_anak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Optimasi
php artisan optimize:clear

# 8. Jalankan dev server
php artisan serve
```

## Menjalankan Queue Notifikasi

Notifikasi email & database berjalan via queue. Jalankan di terminal terpisah:

```bash
php artisan queue:work
```

## Menjalankan Scheduled Task

Rekap absensi harian berjalan otomatis via Laravel Scheduler. Di Windows/Laragon:

```bash
# Jalankan scheduler secara manual setiap menit
php artisan schedule:run
```

Di production (Linux), tambahkan cron:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Testing

```bash
php artisan test
```

Atau spesifik:

```bash
php artisan test --filter=PengajuanIzinTest
```

## UML
-Use Case Diagram
<img width="724" height="573" alt="image" src="https://github.com/user-attachments/assets/c6b41976-0f94-4159-bb8c-a5f494837e81" />

-Class Diagram
<img width="711" height="674" alt="image" src="https://github.com/user-attachments/assets/6acc9acf-7263-4da4-8d06-c187478e1a5a" />

-Sequence Diagram
<img width="940" height="426" alt="image" src="https://github.com/user-attachments/assets/3d808733-80cb-4722-86eb-a52a83b10628" />

-Sequence Diagram (Alur Kedua)
<img width="940" height="426" alt="image" src="https://github.com/user-attachments/assets/8d169f89-59ab-4fe5-be9a-564cbe91c7a6" />

-Activity Diagram
<img width="760" height="1029" alt="image" src="https://github.com/user-attachments/assets/3fa3bcf8-376b-430b-933c-0af618d0f8c7" />

-Architecture Diagram
<img width="940" height="614" alt="image" src="https://github.com/user-attachments/assets/5a92d186-33e3-4433-9abc-8960139d47d0" />

-ERD
<img width="824" height="742" alt="image" src="https://github.com/user-attachments/assets/ed4ae545-a4ed-42be-936a-48f8fb69a5a5" />


## Lisensi

MIT
