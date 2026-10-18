# Class Diagram - Sistem Penyewaan Alat Berat Edi Jaya Jambi

## 📋 Overview
Class Diagram ini menggambarkan arsitektur lengkap sistem penyewaan alat berat yang dibangun menggunakan **CodeIgniter 4** dengan pola **MVC (Model-View-Controller)**. Diagram ini mencakup semua komponen utama termasuk Controllers, Models, dan Database Tables.

## 📁 File Diagram
1. **`class_diagram_complete.puml`** - Diagram lengkap dengan semua method dan detail
2. **`class_diagram_simple.puml`** - Diagram sederhana untuk overview cepat

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
  - Category methods: `excavator()`, `dozer()`, `vibroRoller()`, dll.

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
  - `success()`: Halaman sukses

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

### 3. Database Tables

#### users Table
```sql
- id (PK, AI)
- name (varchar 255)
- email (varchar 255, UNIQUE)
- password (varchar 255)
- role (enum: 'user', 'admin')
- phone (varchar 20)
- address (text)
- created_at, updated_at (timestamp)
```

#### alat_berat Table
```sql
- id (PK, AI)
- nama, tipe, jenis (varchar)
- kapasitas (decimal 10,2)
- status (enum: 'tersedia', 'dipinjam', 'rusak')
- deskripsi (text)
- gambar (varchar 255)
- harga_sewa (decimal 15,2)
- operator (varchar 100)
- Technical specs (decimal fields)
- merek, kategori (varchar 100)
- created_at, updated_at (timestamp)
```

#### alat_berat_gambar Table
```sql
- id (PK, AI)
- alat_berat_id (FK)
- filename (varchar 255)
- created_at, updated_at (timestamp)
```

#### riwayat_penawaran Table
```sql
- id (PK, AI)
- user_id (FK, nullable)
- nama_pengunjung, email_pengunjung (varchar)
- telepon_pengunjung (varchar 30)
- alamat_pengiriman (text)
- alat_berat_id (FK)
- tanggal_minta (datetime)
- status (enum: 'pending', 'diterima', 'ditolak')
- harga_ditawarkan (decimal 15,2)
- catatan, pesan_admin (text)
- jumlah_ajukan_ulang (int, default 0)
- created_at, updated_at (timestamp)
```

#### notifikasi Table
```sql
- id (PK, AI)
- user_id (FK)
- pesan (text)
- link (varchar 255)
- tipe (varchar 50)
- is_read (tinyint 1, default 0)
- created_at, updated_at (timestamp)
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
- **alat_berat** ||--o{ **alat_berat_gambar**: Satu alat berat bisa memiliki banyak gambar
- **alat_berat** ||--o{ **riwayat_penawaran**: Satu alat berat bisa memiliki banyak penawaran
- **users** ||--o{ **notifikasi**: Satu user bisa memiliki banyak notifikasi

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

### Database Tables: 5 tables
- users, alat_berat, alat_berat_gambar
- riwayat_penawaran, notifikasi

### Total Methods: ~50+ methods
- CRUD operations
- Authentication & authorization
- File upload handling
- Search & filtering

## 🚀 Cara Menggunakan Diagram

### 1. Generate Diagram
```bash
# Menggunakan PlantUML
plantuml class_diagram_complete.puml
plantuml class_diagram_simple.puml
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

---

**Dibuat oleh**: AI Assistant  
**Tanggal**: 5 Agustus 2025  
**Versi**: 1.0  
**Framework**: CodeIgniter 4.6.1 