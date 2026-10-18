# Activity Diagram Admin - Sistem Penyewaan Alat Berat

## Deskripsi
Activity Diagram ini menggambarkan alur kerja dan aktivitas yang dilakukan oleh Admin dalam sistem penyewaan alat berat. Diagram ini mencakup semua fitur utama yang tersedia untuk admin berdasarkan analisis kode Admin Controller.

## File Diagram
1. `activity_diagram_admin.puml` - Diagram sederhana tanpa swimlanes
2. `activity_diagram_admin_detailed.puml` - Diagram detail dengan swimlanes

## Komponen Utama

### 1. Authentication (Autentikasi)
- **Admin Login**: Proses login admin dengan validasi kredensial
- **Verifikasi Role**: Memastikan user memiliki role admin
- **Session Management**: Pengelolaan sesi admin

### 2. Dashboard
- **Statistik Overview**: Menampilkan ringkasan data sistem
  - Total User
  - Total Alat Berat
  - Total Kuotasi

### 3. User Management (Manajemen User)
- **CRUD Operations**:
  - **Create**: Tambah user baru dengan validasi
  - **Read**: Lihat daftar semua user
  - **Update**: Edit data user (termasuk password)
  - **Delete**: Hapus user dengan konfirmasi

### 4. Alat Berat Management (Manajemen Alat Berat)
- **CRUD Operations**:
  - **Create**: Tambah alat berat baru dengan upload gambar
  - **Read**: Lihat daftar alat berat (diurutkan berdasarkan jenis)
  - **Update**: Edit data alat berat dan gambar
  - **Delete**: Hapus alat berat dan file gambar terkait

- **Additional Features**:
  - Upload multiple gambar
  - Hapus gambar individual
  - Hapus semua data alat berat
  - Normalisasi jenis alat berat

### 5. Kuotasi Management (Kelola Kuotasi)
- **Status Management**:
  - Update status kuotasi (Pending/Diterima/Ditolak)
  - Input pesan admin opsional
  - Notifikasi otomatis ke user

- **Data Management**:
  - Lihat semua permintaan kuotasi
  - Hapus kuotasi individual
  - Hapus semua data kuotasi

### 6. Settings Management (Pengaturan)
- **Website Configuration**:
  - Update hero section (title, subtitle, description)
  - Upload foto profil perusahaan
  - Upload foto header homepage

## Alur Kerja Utama

### 1. Login Process
```
Start → Admin Login → Validasi Kredensial → Verifikasi Role → Dashboard
```

### 2. User Management Flow
```
Dashboard → Pilih Menu User → Lihat Daftar → Pilih Aksi (CRUD) → Proses → Feedback
```

### 3. Alat Berat Management Flow
```
Dashboard → Pilih Menu Alat Berat → Lihat Daftar → Pilih Aksi (CRUD) → Validasi → Upload File → Simpan → Feedback
```

### 4. Kuotasi Management Flow
```
Dashboard → Pilih Menu Kuotasi → Lihat Daftar → Update Status → Input Pesan → Update Database → Notifikasi User → Feedback
```

### 5. Settings Management Flow
```
Dashboard → Pilih Menu Pengaturan → Load Config → Pilih Aksi → Update/Upload → Simpan Config → Feedback
```

## Fitur Keamanan

### 1. Authentication & Authorization
- Validasi session admin di setiap method
- Redirect ke login jika tidak terautentikasi
- Verifikasi role admin sebelum akses

### 2. Data Validation
- Validasi input form
- Validasi file upload
- Sanitasi data sebelum disimpan

### 3. File Management
- Validasi tipe file gambar
- Penamaan file yang aman (random name)
- Pembersihan file lama saat update/delete

## Error Handling

### 1. Login Errors
- Kredensial tidak valid
- Role tidak sesuai
- Session expired

### 2. Validation Errors
- Input tidak lengkap
- Format data tidak sesuai
- File upload gagal

### 3. Database Errors
- Data tidak ditemukan
- Constraint violation
- Connection error

## Notifikasi System

### 1. Success Messages
- "Data berhasil ditambahkan/diupdate/dihapus"
- "File berhasil diupload"
- "Status berhasil diupdate"

### 2. Error Messages
- "Akses hanya untuk admin!"
- "Data tidak ditemukan"
- "Gagal upload file"

### 3. User Notifications
- Notifikasi otomatis saat status kuotasi berubah
- Link langsung ke halaman riwayat kuotasi

## Database Operations

### 1. User Management
- `users` table: CRUD operations
- Password hashing dengan PASSWORD_DEFAULT

### 2. Alat Berat Management
- `alat_berat` table: CRUD operations
- `alat_berat_gambar` table: Multiple images
- File system: Image storage

### 3. Kuotasi Management
- `riwayat_kuotasi` table: CRUD operations
- `notifikasi` table: User notifications

### 4. Settings Management
- File-based configuration: `profil_perusahaan.php`
- File system: Image storage

## Teknologi yang Digunakan

### 1. Backend
- **Framework**: CodeIgniter 4
- **Language**: PHP 8+
- **Database**: MySQL/MariaDB

### 2. Frontend
- **CSS Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **JavaScript**: Vanilla JS

### 3. File Management
- **Upload**: CodeIgniter File Upload Class
- **Storage**: Local file system
- **Validation**: File type and size validation

## Cara Menggunakan Diagram

### 1. Menggunakan PlantUML
```bash
# Install PlantUML
# Generate PNG
plantuml activity_diagram_admin.puml
plantuml activity_diagram_admin_detailed.puml
```

### 2. Online Viewer
- Copy kode PlantUML ke [PlantUML Online Server](http://www.plantuml.com/plantuml/uml/)
- Atau gunakan extension PlantUML di VS Code

### 3. Documentation
- Diagram ini dapat digunakan untuk:
  - Dokumentasi sistem
  - Training admin baru
  - Analisis alur kerja
  - Pengembangan fitur baru

## Maintenance & Updates

### 1. Menambah Fitur Baru
- Update diagram dengan swimlane baru
- Tambahkan decision points untuk fitur baru
- Update dokumentasi

### 2. Modifikasi Alur
- Sesuaikan diagram dengan perubahan kode
- Update dokumentasi terkait
- Review dengan tim development

### 3. Version Control
- Simpan diagram dalam repository
- Tag versi diagram sesuai release
- Backup diagram secara berkala

---

**Dibuat oleh**: AI Assistant  
**Tanggal**: 2024  
**Versi**: 1.0  
**Framework**: CodeIgniter 4  
**Sistem**: Penyewaan Alat Berat 