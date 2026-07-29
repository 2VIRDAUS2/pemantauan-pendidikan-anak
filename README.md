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

- **Backend**: Laravel 13, PHP 8.3
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
├── Services/        → Logika bisnis (DashboardService, PresensiService, NilaiService, PengajuanIzinService, JadwalService, PengumumanService, FileUploadService)
├── Http/
│   ├── Controllers/ → Auth, Guru/* (Dashboard, Nilai, Presensi, VerifikasiIzin), OrangTua/* (Dashboard, Nilai, PengajuanIzin), JadwalController, PengumumanController
│   ├── Requests/    → PengajuanIzinRequest, VerifikasiIzinRequest, StoreNilaiRequest
│   └── Middleware/   → RoleMiddleware
├── Notifications/   → PengajuanIzinStatusChanged
└── Jobs/            → RekapAbsensiHarian
```

## Role & Akun Demo

Setelah `db:seed`, tersedia 4 akun:

| Role | Email | Password |
|------|-------|----------|
| Guru | guru@example.com | password |
| Guru | guru2@example.com | password |
| Orang Tua | ortu@example.com | password |
| Orang Tua | ortu2@example.com | password |

## Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/2VIRDAUS2/pemantauan-pendidikan-anak.git
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

Diagram UML tersedia sebagai **PlantUML** di `docs/uml/`. Render file `.puml` menggunakan [PlantUML](https://plantuml.com/) atau ekstensi VS Code.

### 1. Use Case Diagram

```puml
@startuml
left to right direction
actor "Guru" as guru
actor "Orang Tua" as ortu
actor "Admin" as admin

rectangle "Sistem Pemantauan Pendidikan Anak" {
  usecase "UC1 - Login" as UC1
  usecase "UC2 - Kelola Absensi" as UC2
  usecase "UC3 - Verifikasi Izin" as UC3
  usecase "UC4 - Input Nilai" as UC4
  usecase "UC5 - Ajukan Izin" as UC5
  usecase "UC6 - Lihat Nilai & Presensi" as UC6
  usecase "UC7 - Lihat Jadwal" as UC7
  usecase "UC8 - Kelola Pengumuman" as UC8
  usecase "UC9 - Kelola Data Anak" as UC9
  usecase "UC10 - Lihat Dashboard" as UC10
}

guru --> UC1
guru --> UC10
guru --> UC2
guru --> UC3
guru --> UC4
guru --> UC7
guru --> UC8

ortu --> UC1
ortu --> UC10
ortu --> UC5
ortu --> UC6
ortu --> UC7
ortu --> UC8

admin --> UC9
@enduml
```

### 2. Class Diagram

```puml
@startuml
namespace App\Models {

class User {
  -id: int
  -name: string
  -email: string
  -password: string
  -role: Role
  +isGuru(): bool
  +isOrangTua(): bool
  +siswas(): HasMany
  +verifikasiIzins(): HasMany
}

class Siswa {
  -id: int
  -orang_tua_id: int
  -nisn: string
  -nama_siswa: string
  -kelas: string
  +orangTua(): BelongsTo
  +pengajuanIzins(): HasMany
  +nilais(): HasMany
  +presensis(): HasMany
}

class Presensi {
  -id: int
  -siswa_id: int
  -tanggal: date
  -status: enum
  +siswa(): BelongsTo
}

class Nilai {
  -id: int
  -siswa_id: int
  -mata_pelajaran: string
  -skor: float
  +siswa(): BelongsTo
}

class PengajuanIzin {
  -id: int
  -verifikator_id: ?int
  -tanggal_izin: date
  -alasan: string
  -status: StatusPengajuan
  +siswa(): BelongsTo
  +verifikator(): BelongsTo
}

class JadwalPelajaran {
  -id: int
  -kelas: string
  -hari: string
  -jam_mulai: string
  -jam_selesai: string
  -mata_pelajaran: string
  -guru_id: int
  +guru(): BelongsTo
}

class Pengumuman {
  -id: int
  -pengumum_by: int
  -judul: string
  -isi: text
  -target_kelas: ?string
  -is_aktif: bool
  +pembuat(): BelongsTo
}
}

User "1" --o "0..*" Siswa : orang_tua
User "1" --o "0..*" PengajuanIzin : verifikator
User "1" --o "0..*" JadwalPelajaran : guru
User "1" --o "0..*" Pengumuman : pembuat
Siswa "1" --o "0..*" Presensi
Siswa "1" --o "0..*" Nilai
Siswa "1" --o "0..*" PengajuanIzin
@enduml
```

### 3. Activity Diagram

```puml
@startuml
start
:User mengakses sistem;

if (Sudah login?) then (ya)
  :Tampilkan dashboard sesuai role;
else (tidak)
  :Tampilkan halaman login;
  :User memasukkan email & password;
  if (Kredensial valid?) then (ya)
    :Redirect ke dashboard;
  else (tidak)
    :Tampilkan error login;
    stop
  endif
endif

:User memilih menu;
if (Role?) as role_switch

elseif (Guru) then
  if (Pilih fitur?) as guru_menu
  elseif (Absensi) then
    :Pilih kelas & tanggal;
    :Catat kehadiran siswa;
    :Simpan presensi;
  elseif (Verifikasi Izin) then
    :Lihat daftar izin pending;
    :Setujui / Tolak izin;
  elseif (Input Nilai) then
    :Pilih siswa & mapel;
    :Input skor;
    :Simpan nilai;
  endif

elseif (Orang Tua) then
  if (Pilih fitur?) as ortu_menu
  elseif (Ajukan Izin) then
    :Pilih anak, isi tanggal & alasan;
    :Upload bukti dokumen;
    :Kirim pengajuan;
  elseif (Nilai & Presensi) then
    :Lihat daftar anak;
    :Pilih anak untuk detail;
  endif
endif

stop
@enduml
```

### 4. Sequence Diagram (Pengajuan & Verifikasi Izin)

```puml
@startuml
actor "Orang Tua" as ortu
participant "Laravel App" as app
database "Database" as db

== Pengajuan Izin ==
ortu -> app: POST /pengajuan-izin (form + file)
app -> app: Validasi request
app -> app: Simpan file (base64)
app -> db: INSERT pengajuan_izins (pending)
db --> app: OK
app --> ortu: Redirect + sukses

== Verifikasi Izin ==
actor "Guru" as guru
guru -> app: PATCH /verifikasi-izin/{id} (approved)
app -> db: UPDATE status=approved + verifikator_id
app -> db: INSERT/UPDATE presensi (izin)
app -> ortu: Notifikasi (mail + database)
app --> guru: Redirect + sukses
@enduml
```

### 5. State Diagram

```puml
@startuml
state "Pengajuan Izin" {
  [*] --> PENDING : Orang Tua mengajukan
  PENDING --> APPROVED : Guru menyetujui
  PENDING --> REJECTED : Guru menolak
  APPROVED --> [*]
  REJECTED --> [*]
}

state "Presensi Siswa" {
  [*] --> ALPHA : Default
  ALPHA --> HADIR : Guru absen
  ALPHA --> IZIN : Izin disetujui
  ALPHA --> SAKIT : Guru absen
}

state "Pengumuman" {
  [*] --> AKTIF : Publikasi
  AKTIF --> NONAKTIF : Nonaktifkan
  NONAKTIF --> AKTIF : Aktifkan kembali
}
@enduml
```

### 6. Component Diagram

```puml
@startuml
package "Frontend (Blade + Tailwind)" {
  [Login View]
  [Guru Dashboard]
  [Presensi View]
  [Verifikasi Izin View]
  [Nilai View]
  [Jadwal View]
  [Pengumuman View]
  [Orang Tua Dashboard]
  [Pengajuan Izin View]
}

package "Backend (Laravel)" {
  [Controllers]
  [Services]
  [Models]
  [Middleware]
  [Requests]
}

package "Database" {
  [MySQL / SQLite]
}

Frontend --> Backend : HTTP
Backend --> Database : Eloquent ORM
@enduml
```

### 7. Deployment Diagram

```puml
@startuml
actor "User (Guru/Orang Tua)" as user
node "Browser" as browser

node "Web Server (Laragon)" as server {
  node "PHP 8.3+" {
    [Laravel 13 App]
  }
  node "Database" {
    database "MySQL / SQLite"
  }
  node "Queue" {
    [RekapAbsensiHarian]
  }
}

user --> browser : HTTPS
browser --> server : HTTP
@enduml
```

File-file ini juga tersedia dalam format PlantUML di direktori `docs/uml/`.


## Lisensi

MIT
