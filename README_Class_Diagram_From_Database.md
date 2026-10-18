# Class Diagram - Sistem Penyewaan Alat Berat (Based on Database Schema)

## 📋 Overview
Class Diagram ini dibuat berdasarkan struktur database yang terlihat di gambar phpMyAdmin Designer. Diagram ini menggambarkan arsitektur lengkap sistem penyewaan alat berat dengan struktur database yang akurat sesuai dengan implementasi yang ada.

## 📁 File Diagram
1. **`class_diagram_from_database.puml`** - Diagram lengkap berdasarkan database
2. **`class_diagram_simple_from_db.puml`** - Diagram sederhana untuk overview cepat

## 🏗️ Arsitektur Sistem

### 1. Layer Architecture
```
┌─────────────────────────────────────┐
│           Controllers               │  ← Presentation Layer
├─────────────────────────────────────┤
│             Models                  │  ← Business Logic Layer
├─────────────────────────────────────┤
│         Database Tables             │  ← Data Access Layer
└─────────────────────────────────────┘
```

### 2. MVC Pattern Implementation
- **Model**: Menangani data dan business logic
- **View**: Template HTML/PHP (tidak ditampilkan dalam diagram)
- **Controller**: Menangani request dan response

## 🎯 Komponen Utama

### 1. Controllers Package

#### BaseController (Abstract Class)
- **Fungsi**: Base class untuk semua controller
- **Fitur**:
  - Request handling
  - Helper loading
  - Common functionality
  - Session management

#### Home Controller
- **Fungsi**: Menangani halaman utama website
- **Method**:
  - `index()`: Dashboard utama dengan daftar alat berat
  - `kontak()`: Halaman kontak
  - `tentang()`: Halaman tentang

#### Auth Controller
- **Fungsi**: Menangani autentikasi dan otorisasi
- **Method**:
  - `register()` & `registerProcess()`: Registrasi user
  - `loginProcess()`: Proses login user
  - `loginAdmin()` & `loginAdminProcess()`: Login admin
  - `loginUser()` & `loginUserProcess()`: Login user
  - `logout()`: Logout user/admin
  - `lupaPassword()`: Reset password

#### Admin Controller
- **Fungsi**: Menangani fungsi administratif
- **Method**:
  - `index()`: Dashboard admin
  - `users()`: Manajemen user
  - `alatBerat()`: Manajemen alat berat
  - `tambahAlatBerat()` & `simpanAlatBerat()`: CRUD alat berat
  - `editAlatBerat()` & `updateAlatBerat()`: Update alat berat
  - `kelolaKuotasi()`: Manajemen penawaran
  - `pengaturan()`: Pengaturan sistem

#### AlatBerat Controller
- **Fungsi**: Menangani tampilan dan interaksi alat berat
- **Method**:
  - `index()`: Daftar alat berat dengan filter
  - `detail()`: Detail alat berat
  - `mintaKuotasi()`: Form permintaan penawaran
  - `riwayatKuotasi()`: Riwayat penawaran user
  - Category methods: `excavator()`, `dozer()`, `vibroRoller()`, `motorGrader()`, `wheelLoader()`, `backhoeLoader()`, `dumpTruck()`

#### Profile Controller
- **Fungsi**: Manajemen profil user
- **Method**:
  - `index()`: Tampilan profil
  - `updateProfile()`: Update data profil

#### Sewa Controller
- **Fungsi**: Form penyewaan umum
- **Method**:
  - `index()`: Form sewa
  - `process()`: Proses penyewaan

### 2. Models Package

#### UserModel
- **Table**: `users`
- **Fungsi**: Manajemen data user
- **Features**:
  - User authentication
  - Role management (user/admin)
  - Profile management
  - CRUD operations

#### AlatBeratModel
- **Table**: `alat_berat`
- **Fungsi**: Manajemen data alat berat
- **Features**:
  - Equipment CRUD
  - Advanced filtering
  - Search functionality
  - Validation rules
  - Image handling

#### AlatBeratGambarModel
- **Table**: `alat_berat_gambar`
- **Fungsi**: Manajemen gambar alat berat
- **Features**:
  - Multiple image support
  - Image upload/delete
  - Gallery management

#### RiwayatPenawaranModel
- **Table**: `riwayat_penawaran`
- **Fungsi**: Manajemen riwayat penawaran
- **Features**:
  - Quotation tracking
  - Status management
  - Resubmission tracking
  - Admin message handling

#### NotifikasiModel
- **Table**: `notifikasi`
- **Fungsi**: Manajemen notifikasi sistem
- **Features**:
  - User notifications
  - Read/unread status
  - Link management
  - Type categorization

### 3. Database Tables (Based on Actual Schema)

#### users Table
```sql
- id: int(11) unsigned (PK, AI)
- name: varchar(100)
- email: varchar(100)
- password: varchar(255)
- role: enum('user','admin')
- phone: varchar(20)
- address: text
- created_at: datetime
- updated_at: datetime
```

#### alat_berat Table
```sql
- id: int(11) unsigned (PK, AI)
- kategori: varchar(50)
- nama: varchar(100)
- deskripsi: text
- gambar: varchar(255)
- tipe: varchar(100)
- jenis: varchar(50)
- merek: varchar(100)
- kapasitas: int(11)
- harga: varchar(50)
- minimum_sewa: varchar(20)
- operator: varchar(50)
- berat_operasional: varchar(50)
- kapasitas_bucket: varchar(50)
- kedalaman_gali_maksimum: varchar(50)
- tinggi_transportasi: varchar(50)
- panjang_transportasi: varchar(50)
- lebar_transportasi: varchar(50)
- status: enum('tersedia','dipinjam','rusak')
- harga_sewa: varchar(50)
- foto: varchar(255)
- created_at: datetime
- updated_at: datetime
```

#### alat_berat_gambar Table
```sql
- id: int(11) unsigned (PK, AI)
- alat_berat_id: int(11) unsigned (FK)
- filename: varchar(255)
- created_at: datetime
- updated_at: datetime
```

#### riwayat_penawaran Table
```sql
- id: int(11) unsigned (PK, AI)
- user_id: int(11) unsigned (FK)
- nama_pengunjung: varchar(100)
- email_pengunjung: varchar(100)
- telepon_pengunjung: varchar(30)
- alat_berat_id: int(11) unsigned (FK)
- tanggal_minta: datetime
- status: enum('pending','ditawarkan','ditolak','diterima')
- harga_ditawarkan: int(11)
- catatan: text
- telepon: varchar(20)
- alamat_pengiriman: text
- pesan_admin: text
- jumlah_ajukan_ulang: int(11)
- created_at: datetime
- updated_at: datetime
```

#### notifikasi Table
```sql
- id: int(11) unsigned (PK, AI)
- user_id: int(11) unsigned (FK)
- pesan: text
- link: varchar(255)
- tipe: enum('kuotasi','pesan')
- is_read: tinyint(1)
- created_at: datetime
- updated_at: datetime
```

#### migrations Table
```sql
- id: bigint(20) unsigned (PK, AI)
- version: varchar(255)
- class: varchar(255)
- group: varchar(255)
- namespace: varchar(255)
- time: int(11)
- batch: int(11) unsigned
```

## 🔗 Relasi Antar Kelas

### 1. Inheritance Relationships
```
BaseController (Abstract)
├── Home
├── Auth
├── Admin
├── AlatBerat
├── Profile
└── Sewa
```

### 2. Model Inheritance
```
CodeIgniter\Model
├── UserModel
├── AlatBeratModel
├── AlatBeratGambarModel
├── RiwayatPenawaranModel
└── NotifikasiModel
```

### 3. Associations
- **Home** → **AlatBeratModel**: Menggunakan model untuk menampilkan data
- **Admin** → **UserModel**: Mengelola data user
- **Admin** → **AlatBeratModel**: Mengelola data alat berat
- **Admin** → **RiwayatPenawaranModel**: Mengelola penawaran
- **AlatBerat** → **AlatBeratModel**: Menggunakan model untuk operasi CRUD
- **AlatBerat** → **AlatBeratGambarModel**: Mengelola gambar
- **AlatBerat** → **RiwayatPenawaranModel**: Mengelola penawaran
- **Auth** → **UserModel**: Autentikasi user

### 4. Database Relationships
- **users** ||--o{ **riwayat_penawaran**: Satu user bisa memiliki banyak penawaran
- **users** ||--o{ **notifikasi**: Satu user bisa memiliki banyak notifikasi
- **alat_berat** ||--o{ **alat_berat_gambar**: Satu alat berat bisa memiliki banyak gambar
- **alat_berat** ||--o{ **riwayat_penawaran**: Satu alat berat bisa memiliki banyak penawaran

## 🎨 Design Patterns

### 1. MVC Pattern
- **Model**: Business logic dan data access
- **View**: Presentation layer (HTML templates)
- **Controller**: Request handling dan response

### 2. Repository Pattern
- Models sebagai repository untuk database operations
- Encapsulation database logic

### 3. Dependency Injection
- Controllers menggunakan models melalui constructor injection
- Loose coupling antara components

## 🔧 Teknologi yang Digunakan

### Backend
- **PHP 8.2.12**: Programming language
- **CodeIgniter 4.6.1**: Web framework
- **MySQL**: Database management system

### Frontend
- **Bootstrap 5**: CSS framework
- **Font Awesome**: Icon library
- **jQuery**: JavaScript library

### Development Tools
- **PlantUML**: Diagram generation
- **Composer**: Dependency management
- **XAMPP**: Local development environment

## 📊 Metrik Sistem

### Controllers: 6 classes
- BaseController (abstract)
- Home, Auth, Admin, AlatBerat, Profile, Sewa

### Models: 5 classes
- UserModel, AlatBeratModel, AlatBeratGambarModel
- RiwayatPenawaranModel, NotifikasiModel

### Database Tables: 6 tables
- users, alat_berat, alat_berat_gambar
- riwayat_penawaran, notifikasi, migrations

### Total Methods: ~50+ methods
- CRUD operations
- Authentication & authorization
- File upload handling
- Search & filtering

## 🚀 Cara Menggunakan Diagram

### 1. Generate Diagram
```bash
# Menggunakan PlantUML
plantuml class_diagram_from_database.puml
plantuml class_diagram_simple_from_db.puml
```

### 2. Online Viewer
- Copy kode PlantUML ke [PlantUML Online Server](http://www.plantuml.com/plantuml/uml/)
- Atau gunakan VS Code extension PlantUML

### 3. Documentation
- Diagram ini dapat digunakan untuk:
  - System documentation
  - Code review
  - Architecture planning
  - Team collaboration

## 📝 Catatan Penting

1. **Naming Convention**: Mengikuti PSR-4 autoloading standard
2. **Database**: Menggunakan timestamps untuk audit trail
3. **Security**: Password hashing dan role-based access control
4. **Validation**: Input validation di level model dan controller
5. **File Upload**: Image handling dengan security measures

## 🔄 Maintenance

### Update Diagram
1. Modifikasi file `.puml`
2. Regenerate diagram
3. Update dokumentasi jika diperlukan

### Version Control
- Simpan file `.puml` di repository
- Update diagram saat ada perubahan arsitektur
- Dokumentasikan perubahan di commit message

## 💡 Perbedaan dengan Diagram Sebelumnya

### ✅ Yang Diperbaiki:
1. **Struktur database akurat** - Sesuai dengan schema yang ada
2. **Field types yang benar** - Menggunakan tipe data yang tepat
3. **Enum values yang akurat** - Status dan role sesuai implementasi
4. **Foreign key relationships** - Relasi yang benar antar tabel
5. **Controller methods** - Method yang sesuai dengan implementasi

### 📋 Field yang Diperbaiki:
- `kapasitas`: `decimal(10,2)` → `int(11)`
- `harga_sewa`: `decimal(15,2)` → `varchar(50)`
- `status`: Enum values yang benar
- `tipe`: Enum values yang benar
- Technical specs: Menggunakan `varchar(50)` bukan `decimal`

---

**Dibuat oleh**: AI Assistant  
**Tanggal**: 5 Agustus 2025  
**Versi**: 2.0  
**Framework**: CodeIgniter 4.6.1  
**Basis**: Database Schema Analysis 