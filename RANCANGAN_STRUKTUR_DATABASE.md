# RANCANGAN STRUKTUR DATABASE SISTEM PENYEWAAN ALAT BERAT

## Tabel 1: users

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | int(11) | Primary Key, Auto Increment |
| name | varchar(100) | Nama lengkap user |
| email | varchar(100) | Email user (unique) |
| password | varchar(255) | Password terenkripsi |
| role | enum('user','admin') | Role user (default: user) |
| phone | varchar(20) | Nomor telepon (nullable) |
| address | text | Alamat lengkap (nullable) |
| created_at | datetime | Waktu pembuatan record |
| updated_at | datetime | Waktu update terakhir |

---

## Tabel 2: alat_berat

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | int(11) | Primary Key, Auto Increment |
| nama | varchar(100) | Nama alat berat |
| tipe | varchar(100) | Tipe/model alat berat (nullable) |
| deskripsi | text | Deskripsi detail alat berat (nullable) |
| gambar | varchar(255) | Nama file gambar utama (nullable) |
| jenis | varchar(50) | Kategori alat berat |
| kapasitas | int(11) | Kapasitas dalam satuan yang sesuai |
| harga | int(11) | Harga per unit (nullable) |
| minimum_sewa | varchar(20) | Minimum waktu sewa (nullable) |
| operator | varchar(50) | Jumlah operator yang dibutuhkan (nullable) |
| berat_operasional | varchar(50) | Berat operasional alat (nullable) |
| kapasitas_bucket | varchar(50) | Kapasitas bucket (nullable) |
| kedalaman_gali_maksimum | varchar(50) | Kedalaman gali maksimum (nullable) |
| tinggi_transportasi | varchar(50) | Tinggi saat transportasi (nullable) |
| panjang_transportasi | varchar(50) | Panjang saat transportasi (nullable) |
| lebar_transportasi | varchar(50) | Lebar saat transportasi (nullable) |
| status | enum('tersedia','dipinjam','rusak') | Status alat berat (default: tersedia) |
| harga_sewa | decimal(10,2) | Harga sewa per hari (default: 0.00) |
| created_at | datetime | Waktu pembuatan record |
| updated_at | datetime | Waktu update terakhir |

---

## Tabel 3: alat_berat_gambar

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | int(11) | Primary Key, Auto Increment |
| alat_berat_id | int(11) | Foreign Key ke tabel alat_berat |
| filename | varchar(255) | Nama file gambar |
| created_at | datetime | Waktu pembuatan record |
| updated_at | datetime | Waktu update terakhir |

---

## Tabel 4: riwayat_kuotasi

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | int(11) | Primary Key, Auto Increment |
| user_id | int(11) | Foreign Key ke tabel users (nullable) |
| nama_pengunjung | varchar(100) | Nama pengunjung non-user (nullable) |
| email_pengunjung | varchar(100) | Email pengunjung non-user (nullable) |
| alat_berat_id | int(11) | Foreign Key ke tabel alat_berat |
| tanggal_minta | datetime | Tanggal permintaan kuotasi |
| status | enum('pending','ditawarkan','ditolak') | Status kuotasi (default: pending) |
| harga_ditawarkan | int(11) | Harga yang ditawarkan admin (nullable) |
| catatan | text | Catatan tambahan (nullable) |
| telepon | varchar(20) | Nomor telepon pemohon (nullable) |
| alamat_pengiriman | text | Alamat pengiriman alat (nullable) |
| pesan_admin | text | Pesan dari admin (nullable) |
| jumlah_ajukan_ulang | int(11) | Jumlah pengajuan ulang (nullable) |
| created_at | datetime | Waktu pembuatan record |
| updated_at | datetime | Waktu update terakhir |

---

## Tabel 5: notifikasi

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | int(11) | Primary Key, Auto Increment |
| user_id | int(11) | Foreign Key ke tabel users |
| pesan | text | Isi pesan notifikasi |
| link | varchar(255) | Link terkait notifikasi (nullable) |
| tipe | enum('kuotasi','pesan') | Tipe notifikasi (default: kuotasi) |
| is_read | tinyint(1) | Status dibaca (0=belum, 1=sudah) |
| created_at | datetime | Waktu pembuatan record |
| updated_at | datetime | Waktu update terakhir |

---

## RELASI ANTAR TABEL

### 1. Relasi users → riwayat_kuotasi
- **Foreign Key**: `riwayat_kuotasi.user_id` → `users.id`
- **Tipe**: One-to-Many (1 user bisa punya banyak riwayat kuotasi)
- **Constraint**: CASCADE

### 2. Relasi users → notifikasi
- **Foreign Key**: `notifikasi.user_id` → `users.id`
- **Tipe**: One-to-Many (1 user bisa punya banyak notifikasi)
- **Constraint**: CASCADE

### 3. Relasi alat_berat → alat_berat_gambar
- **Foreign Key**: `alat_berat_gambar.alat_berat_id` → `alat_berat.id`
- **Tipe**: One-to-Many (1 alat berat bisa punya banyak gambar)
- **Constraint**: CASCADE

### 4. Relasi alat_berat → riwayat_kuotasi
- **Foreign Key**: `riwayat_kuotasi.alat_berat_id` → `alat_berat.id`
- **Tipe**: One-to-Many (1 alat berat bisa punya banyak riwayat kuotasi)
- **Constraint**: CASCADE

---

## INDEKS DAN CONSTRAINT

### Primary Keys
- `users.id`
- `alat_berat.id`
- `alat_berat_gambar.id`
- `riwayat_kuotasi.id`
- `notifikasi.id`

### Unique Constraints
- `users.email` (unique)

### Foreign Key Constraints
- `alat_berat_gambar.alat_berat_id` → `alat_berat.id` (CASCADE)
- `riwayat_kuotasi.user_id` → `users.id` (CASCADE)
- `riwayat_kuotasi.alat_berat_id` → `alat_berat.id` (CASCADE)
- `notifikasi.user_id` → `users.id` (CASCADE)

### Indexes
- Primary keys (auto-indexed)
- `users.email` (unique index)
- Foreign key columns (auto-indexed)

---

## CATATAN PENTING

1. **Enkripsi Password**: Password di tabel `users` menggunakan `password_hash()` dengan `PASSWORD_DEFAULT`
2. **Timestamps**: Semua tabel menggunakan `created_at` dan `updated_at` untuk tracking waktu
3. **Soft Delete**: Tidak ada soft delete, menggunakan hard delete
4. **File Upload**: Gambar disimpan sebagai filename di database, file fisik disimpan di folder uploads
5. **Status Tracking**: Menggunakan ENUM untuk status yang terbatas pilihannya
6. **Nullable Fields**: Banyak field yang nullable untuk fleksibilitas data entry 