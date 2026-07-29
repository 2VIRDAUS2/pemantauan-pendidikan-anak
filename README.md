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

### 1. Use Case Diagram

```mermaid
graph LR
    subgraph Sistem["Sistem Pemantauan Pendidikan Anak"]
        UC1[Login]
        UC2[Kelola Absensi]
        UC3[Verifikasi Izin]
        UC4[Input Nilai]
        UC5[Ajukan Izin]
        UC6[Lihat Nilai & Presensi]
        UC7[Lihat Jadwal]
        UC8[Kelola Pengumuman]
        UC9[Kelola Data Anak]
        UC10[Lihat Dashboard]
    end

    Guru((Guru)) --> UC1 & UC10 & UC2 & UC3 & UC4 & UC7 & UC8
    Ortu((Orang Tua)) --> UC1 & UC10 & UC5 & UC6 & UC7 & UC8
    Admin((Admin)) --> UC9
```

### 2. Class Diagram

```mermaid
classDiagram
    class User {
        -id: int
        -name: string
        -email: string
        -password: string
        -role: Role
        +isGuru() bool
        +isOrangTua() bool
    }
    class Siswa {
        -id: int
        -orang_tua_id: int
        -nisn: string
        -nama_siswa: string
        -kelas: string
    }
    class Presensi {
        -id: int
        -siswa_id: int
        -tanggal: date
        -status: enum
    }
    class Nilai {
        -id: int
        -siswa_id: int
        -mata_pelajaran: string
        -skor: float
    }
    class PengajuanIzin {
        -id: int
        -verifikator_id: ?int
        -tanggal_izin: date
        -alasan: string
        -status: StatusPengajuan
    }
    class JadwalPelajaran {
        -id: int
        -kelas: string
        -hari: string
        -jam_mulai: string
        -jam_selesai: string
        -mata_pelajaran: string
        -guru_id: int
    }
    class Pengumuman {
        -id: int
        -pengumum_by: int
        -judul: string
        -isi: text
        -target_kelas: ?string
        -is_aktif: bool
    }

    User "1" --> "0..*" Siswa : orang_tua
    User "1" --> "0..*" PengajuanIzin : verifikator
    User "1" --> "0..*" JadwalPelajaran : guru
    User "1" --> "0..*" Pengumuman : pembuat
    Siswa "1" --> "0..*" Presensi
    Siswa "1" --> "0..*" Nilai
    Siswa "1" --> "0..*" PengajuanIzin
```

### 3. Activity Diagram

```mermaid
flowchart TD
    Start([Mulai]) --> LoginCheck{Sudah login?}
    LoginCheck -- Ya --> RoleCheck{Pilih Role?}
    LoginCheck -- Tidak --> LoginPage[Halaman Login]
    LoginPage --> InputKredensial[Input email & password]
    InputKredensial --> Valid{Kredensial valid?}
    Valid -- Ya --> Dashboard[Dashboard sesuai role]
    Valid -- Tidak --> Error[Tampilkan error] --> Stop([Selesai])

    RoleCheck -- Guru --> GuruMenu{Pilih fitur}
    RoleCheck -- Orang Tua --> OrtuMenu{Pilih fitur}

    GuruMenu -- Absensi --> Absen[Pilih kelas & tanggal]
    Absen --> Catat[Catat kehadiran] --> SimpanPresensi[Simpan presensi]

    GuruMenu -- Verifikasi Izin --> LihatIzin[Lihat daftar pending]
    LihatIzin --> Verif[Setujui / Tolak]

    GuruMenu -- Input Nilai --> PilihSiswa[Pilih siswa & mapel]
    PilihSiswa --> InputSkor[Input skor] --> SimpanNilai[Simpan nilai]

    OrtuMenu -- Ajukan Izin --> FormIzin[Pilih anak, isi tanggal & alasan]
    FormIzin --> Upload[Upload bukti] --> Kirim[Kirim pengajuan]

    OrtuMenu -- Lihat Nilai & Presensi --> DaftarAnak[Lihat daftar anak]
    DaftarAnak --> DetailAnak[Pilih anak untuk detail]

    SimpanPresensi --> Stop
    Verif --> Stop
    SimpanNilai --> Stop
    Kirim --> Stop
    DetailAnak --> Stop
```

### 4. Sequence Diagram

```mermaid
sequenceDiagram
    participant Ortu as Orang Tua
    participant App as Laravel App
    participant DB as Database

    rect rgb(200, 220, 250)
        Note over Ortu,DB: Pengajuan Izin
        Ortu->>App: POST /pengajuan-izin (form + file)
        App->>App: Validasi request
        App->>App: Simpan file (base64)
        App->>DB: INSERT pengajuan_izins (pending)
        DB-->>App: OK
        App-->>Ortu: Redirect + sukses
    end

    participant Guru as Guru

    rect rgb(220, 250, 200)
        Note over Guru,DB: Verifikasi Izin
        Guru->>App: PATCH /verifikasi-izin/{id} (approved)
        App->>DB: UPDATE status=approved + verifikator_id
        App->>DB: INSERT/UPDATE presensi (izin)
        App->>Ortu: Notifikasi (mail + database)
        App-->>Guru: Redirect + sukses
    end
```

### 5. State Diagram

```mermaid
stateDiagram-v2
    state "Pengajuan Izin" as Pengajuan {
        [*] --> PENDING : Orang Tua mengajukan
        PENDING --> APPROVED : Guru menyetujui
        PENDING --> REJECTED : Guru menolak
        APPROVED --> [*]
        REJECTED --> [*]
    }

    state "Presensi Siswa" as Presensi {
        [*] --> ALPHA : Default
        ALPHA --> HADIR : Guru absen
        ALPHA --> IZIN : Izin disetujui
        ALPHA --> SAKIT : Guru absen
    }

    state "Pengumuman" as Pengumuman {
        [*] --> AKTIF : Publikasi
        AKTIF --> NONAKTIF : Nonaktifkan
        NONAKTIF --> AKTIF : Aktifkan kembali
    }
```

### 6. Component Diagram

```mermaid
flowchart TB
    subgraph Frontend["Frontend (Blade + Tailwind)"]
        LoginView[Login View]
        GuruDashboard[Guru Dashboard]
        PresensiView[Presensi View]
        VerifikasiIzinView[Verifikasi Izin View]
        NilaiView[Nilai View]
        JadwalView[Jadwal View]
        PengumumanView[Pengumuman View]
        OrtuDashboard[Orang Tua Dashboard]
        PengajuanIzinView[Pengajuan Izin View]
    end

    subgraph Backend["Backend (Laravel)"]
        Controllers[Controllers]
        Services[Services]
        Models[Models]
        Middleware[Middleware]
        Requests[Requests]
    end

    subgraph DatabaseLayer["Database"]
        DB[(MySQL / SQLite)]
    end

    Frontend -->|HTTP| Backend
    Backend -->|Eloquent ORM| DB
```

### 7. Deployment Diagram

```mermaid
flowchart LR
    User([User Guru/Orang Tua]) -->|HTTPS| Browser[Browser]
    Browser -->|HTTP| Server

    subgraph Server["Web Server (Laragon)"]
        direction TB
        subgraph PHP["PHP 8.3+"]
            Laravel[Laravel 13 App]
        end
        subgraph DBLayer["Database"]
            Database[(MySQL / SQLite)]
        end
        subgraph Queue["Queue"]
            Rekap[RekapAbsensiHarian]
        end
    end
```


## Lisensi

MIT
