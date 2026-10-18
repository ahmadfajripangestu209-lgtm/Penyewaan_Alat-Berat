# Class Diagram - Sistem Penyewaan Alat Berat

## Deskripsi
Class Diagram ini menggambarkan struktur kelas, relasi, dan arsitektur sistem penyewaan alat berat yang dibangun menggunakan CodeIgniter 4. Diagram ini mencakup semua komponen utama termasuk Controllers, Models, dan Database Tables.

## File Diagram
1. `class_diagram_system.puml` - Diagram dasar dengan struktur utama
2. `class_diagram_detailed.puml` - Diagram detail dengan method dan relasi lengkap

## Arsitektur Sistem

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

## Komponen Utama

### 1. Controllers Package

#### BaseController (Abstract Class)
- **Fungsi**: Base class untuk semua controller
- **Fitur**:
  - Request handling
  - Helper loading
  - Common functionality

#### Home Controller
- **Fungsi**: Menangani halaman utama website
- **Method**:
  - `index()`: Dashboard utama
  - `kontak()`: Halaman kontak
  - `tentang()`: Halaman tentang

#### Auth Controller
- **Fungsi**: Menangani autentikasi dan otorisasi
- **Method**:
  - `register()` & `registerProcess()`: Registrasi user
  - `login()` & `loginProcess()`: Login umum
  - `loginAdmin()` & `loginAdminProcess()`: Login admin
  - `loginUser()` & `loginUserProcess()`: Login user
  - `logout()`: Logout
  - `lupaPassword()`: Reset password

#### Admin Controller
- **Fungsi**: Menangani semua operasi admin
- **Method**:
  - **User Management**: CRUD operations untuk user
  - **Equipment Management**: CRUD operations untuk alat berat
  - **Quotation Management**: Kelola kuotasi
  - **Settings Management**: Konfigurasi sistem
  - **File Management**: Upload dan hapus gambar

#### AlatBerat Controller
- **Fungsi**: Menangani tampilan dan operasi alat berat
- **Method**:
  - **Display**: `index()`, `detail()`, category methods
  - **Quotation**: `mintaKuotasi()`, `riwayatKuotasi()`
  - **Management**: `create()`, `store()`, `ajukanUlangKuotasi()`

#### Profile Controller
- **Fungsi**: Menangani profil user dan notifikasi
- **Method**:
  - `index()` & `update()`: Manajemen profil
  - `notifikasi()`: Lihat notifikasi
  - `bacaNotifikasi()` & `hapusNotifikasi()`: Operasi notifikasi

#### Sewa Controller
- **Fungsi**: Menangani proses penyewaan
- **Method**:
  - `index()`: Form penyewaan
  - `proses()`: Proses penyewaan

### 2. Models Package

#### UserModel
- **Table**: `users`
- **Fungsi**: Manajemen data user
- **Features**:
  - CRUD operations
  - Password hashing
  - Role management
  - Timestamp tracking

#### AlatBeratModel
- **Table**: `alat_berat`
- **Fungsi**: Manajemen data alat berat
- **Features**:
  - CRUD operations
  - Validation rules
  - Search and filtering
  - Status management
  - File upload handling

#### AlatBeratGambarModel
- **Table**: `alat_berat_gambar`
- **Fungsi**: Manajemen gambar alat berat
- **Features**:
  - Multiple image support
  - `getByAlatBerat()`: Custom method untuk galeri

#### RiwayatKuotasiModel
- **Table**: `riwayat_kuotasi`
- **Fungsi**: Manajemen riwayat kuotasi
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

### 3. Database Tables Package

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

#### riwayat_kuotasi Table
```sql
- id (PK, AI)
- user_id (FK, nullable)
- nama_pengunjung, email_pengunjung (varchar)
- telepon_pengunjung (varchar 20)
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

## Relasi Antar Kelas

### 1. Inheritance Relationships
```
BaseController
├── Home
├── Auth
├── Admin
├── AlatBerat
├── Profile
└── Sewa
```

### 2. Model-Database Mapping
- `UserModel` ↔ `users` table
- `AlatBeratModel` ↔ `alat_berat` table
- `AlatBeratGambarModel` ↔ `alat_berat_gambar` table
- `RiwayatKuotasiModel` ↔ `riwayat_kuotasi` table
- `NotifikasiModel` ↔ `notifikasi` table

### 3. Database Relationships
```
users (1) ──── (N) riwayat_kuotasi
users (1) ──── (N) notifikasi
alat_berat (1) ──── (N) alat_berat_gambar
alat_berat (1) ──── (N) riwayat_kuotasi
```

### 4. Controller Dependencies
```
Home ────> AlatBeratModel
Auth ────> UserModel
Admin ────> UserModel, AlatBeratModel, RiwayatKuotasiModel, AlatBeratGambarModel, NotifikasiModel
AlatBerat ────> AlatBeratModel, AlatBeratGambarModel, RiwayatKuotasiModel, UserModel, NotifikasiModel
Profile ────> UserModel, NotifikasiModel
```

## Fitur Utama Sistem

### 1. User Management
- **Registration**: User dapat mendaftar dengan email unik
- **Authentication**: Login dengan role-based access
- **Profile Management**: Update profil dan lihat riwayat

### 2. Equipment Management
- **CRUD Operations**: Full management alat berat
- **Image Gallery**: Multiple image support
- **Search & Filter**: Advanced filtering capabilities
- **Category Views**: Equipment by category

### 3. Quotation System
- **Request Quotation**: User dapat minta penawaran
- **Status Tracking**: Pending/Diterima/Ditolak
- **Resubmission**: Max 3 times resubmission
- **Admin Messages**: Communication between admin and user

### 4. Notification System
- **Real-time Notifications**: Automatic notifications
- **Read/Unread Status**: Track notification status
- **Link Integration**: Direct links to relevant pages

### 5. Admin Panel
- **Dashboard**: Overview statistics
- **User Management**: Full user CRUD
- **Equipment Management**: Full equipment CRUD
- **Quotation Management**: Approve/reject quotations
- **System Settings**: Website configuration

## Security Features

### 1. Authentication & Authorization
- Password hashing dengan PASSWORD_DEFAULT
- Role-based access control
- Session management
- CSRF protection

### 2. Data Validation
- Input validation rules
- File upload validation
- SQL injection prevention
- XSS protection

### 3. File Security
- File type validation
- Secure file naming
- File size limits
- Directory traversal prevention

## Database Design Principles

### 1. Normalization
- First Normal Form (1NF): Atomic values
- Second Normal Form (2NF): No partial dependencies
- Third Normal Form (3NF): No transitive dependencies

### 2. Referential Integrity
- Foreign key constraints
- Cascade operations
- Nullable foreign keys for optional relationships

### 3. Data Types
- Appropriate data types for each field
- Enum for status fields
- Decimal for monetary values
- Timestamp for audit trails

## Performance Considerations

### 1. Database Optimization
- Indexed primary keys
- Foreign key indexes
- Query optimization
- Connection pooling

### 2. File Management
- Efficient file storage
- Image optimization
- Cleanup procedures
- Backup strategies

### 3. Caching
- Session caching
- Query result caching
- Static file caching
- Database query optimization

## Maintenance & Scalability

### 1. Code Organization
- MVC pattern adherence
- Separation of concerns
- Reusable components
- Consistent naming conventions

### 2. Extensibility
- Modular design
- Plugin architecture
- API ready structure
- Configuration management

### 3. Monitoring
- Error logging
- Performance monitoring
- User activity tracking
- System health checks

## Cara Menggunakan Diagram

### 1. Generate Diagram
```bash
# Install PlantUML
plantuml class_diagram_system.puml
plantuml class_diagram_detailed.puml
```

### 2. Online Viewer
- Copy kode PlantUML ke [PlantUML Online Server](http://www.plantuml.com/plantuml/uml/)
- Atau gunakan extension PlantUML di VS Code

### 3. Documentation
- Diagram ini dapat digunakan untuk:
  - System documentation
  - Development planning
  - Code review
  - Team training
  - Architecture discussions

## Version Control

### 1. Diagram Versioning
- Tag diagram sesuai release
- Update diagram saat ada perubahan arsitektur
- Backup diagram secara berkala

### 2. Code Synchronization
- Keep diagram in sync with actual code
- Review diagram during code reviews
- Update diagram for new features

---

**Dibuat oleh**: AI Assistant  
**Tanggal**: 2024  
**Versi**: 1.0  
**Framework**: CodeIgniter 4  
**Sistem**: Penyewaan Alat Berat  
**Pattern**: MVC (Model-View-Controller) 