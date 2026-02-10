# 🎓 Sistem Program Coaching Siswa

Sistem berbasis Laravel untuk mengelola program coaching siswa di sekolah, mencakup perencanaan, pelaksanaan, jurnal pencatatan, refleksi, rencana tindak lanjut, dan pelaporan kepada siswa serta orang tua.

---

# 📌 Fitur Utama

## Sistem Program Coaching

### a. Visi dan Misi

Menjadi sistem terstruktur untuk mendukung perkembangan siswa melalui coaching berbasis data dan refleksi.

### b. SOP Coaching

Alur kerja sistem:

1. Perencanaan Coaching
2. Pelaksanaan Sesi
3. Pencatatan Jurnal
4. Pemberian Materi
5. Refleksi
6. Rencana Tindak Lanjut
7. Pelaporan

### c. Perencanaan

- Pembuatan coaching oleh guru
- Menentukan murid
- Menentukan tujuan
- Status: draft / ongoing / completed

### d. Pelaksanaan

- Coaching stages (tahapan)
- Coaching sessions
- Tracking progress (% otomatis)

### e. Jurnal Pencatatan

- Input jurnal per sesi
- Filter berdasarkan tanggal
- Relasi ke coaching

### f. Bahan Ajar

- Upload materi
- Relasi ke coaching
- Disimpan per sesi

### g. Refleksi

Terdiri dari:

- reflection
- hasil_perkembangan
- kendala
- rencana_perbaikan

### h. Rencana Tindak Lanjut

- judul
- rencana_tindak_lanjut
- target_tanggal

### i. Pelaporan

- Rekap jurnal
- Statistik bulanan
- Summary coaching
- Siap untuk export PDF (opsional)

---

# 👥 Role Sistem

| Role  | Hak Akses                |
| ----- | ------------------------ |
| Admin | Full akses               |
| Guru  | Kelola coaching & jurnal |
| Murid | Melihat coaching sendiri |
| Ortu  | Melihat laporan anak     |

---

# 🧱 Struktur Database

## Tabel Utama

- users
- coachings
- coaching_stages
- coaching_sessions
- journals
- materials
- reflections
- follow_ups
- parent_student (pivot)

---

# 🔗 Relasi Sistem

## Guru → Murid

1 guru bisa memiliki banyak coaching.

## Murid → Ortu

Relasi many-to-many melalui:
parent_student

## Coaching memiliki:

- Many Journals
- Many Materials
- Many Sessions
- One Reflection
- Many FollowUps

---

# 🚀 Cara Install

```bash
1️⃣ Clone Project
git clone <repository-url>
cd nama-project

2️⃣ Install Dependency
composer install

3️⃣ Setup Environment
cp .env.example .env
php artisan key:generate

4️⃣ Setup Database

Edit file .env

DB_DATABASE=coaching_siswa
DB_USERNAME=root
DB_PASSWORD=

5️⃣ Migrasi + Seeder Demo
php artisan migrate:fresh --seed
```

🧪 Akun Demo
👨‍🏫 Guru
email: guru1@example.com
password: password

👨‍🎓 Murid
email: murid1@example.com
password: password

👨‍👩‍👧 Ortu
email: ortu1@example.com
password: password

📊 Data Demo yang Dibuat Seeder

Seeder akan membuat:
- 2 Guru
- 3 Murid
 -2 Orang Tua
- Relasi Murid ↔ Ortu
- 1 Coaching aktif
- 5 Jurnal (beda bulan)
- 3 Materi
- 1 Refleksi lengkap
- 2 Rencana Tindak Lanjut
Sehingga halaman laporan langsung bisa diuji.

📁 Struktur Folder Penting
app/
 ├── Models/
 ├── Http/Controllers/
database/
 ├── migrations/
 ├── seeders/
resources/
 ├── views/

📈 Laporan Coaching (C.19)

Fitur:
- Filter by tanggal
- Rekap jumlah jurnal per bulan
- Summary perkembangan
- Data siap untuk export

Query laporan sudah kompatibel dengan:
ONLY_FULL_GROUP_BY

⚙️ Status Enum Coaching
draft
ongoing
completed

🛠 Teknologi
- Laravel 12
- MySQL
- Blade Template
- Eloquent ORM

🧠 Catatan Teknis

✔ Database strict mode aktif
✔ Seeder idempotent
✔ Relasi sudah sinkron
✔ Foreign key cascade aktif

📌 Roadmap Pengembangan
- Export PDF laporan
- Grafik statistik
- Notifikasi ke orang tua
- Approval sistem
- Dashboard analytics

👨‍💻 Developer



📄 Lisensi

Untuk kebutuhan pembelajaran & pengembangan sistem sekolah.
