# Sistem Pendukung Keputusan (DSS) untuk Prioritas Penanganan Kejahatan Online

Sistem ini menggunakan metode AHP (Analytic Hierarchy Process) dan TOPSIS (Technique for Order Preference by Similarity to Ideal Solution) untuk menentukan prioritas penanganan kasus kejahatan online berdasarkan kriteria tertentu.

## Fitur Utama

- **Login & Register System**: Autentikasi pengguna dengan modal popup
- **Dashboard Interaktif**: Panel kontrol dengan statistik dan grafik
- **Konfigurasi AHP**: Pengaturan bobot kriteria melalui perbandingan berpasangan
- **Input Alternatif**: Memasukkan data kasus kejahatan online
- **Perhitungan TOPSIS**: Menghitung prioritas berdasarkan skor alternatif
- **Hasil Prioritas**: Menampilkan peringkat kasus kejahatan online dengan visualisasi
- **Responsive Design**: Tampilan yang optimal di berbagai perangkat

## Kriteria Penilaian

1. **Tingkat Kerugian**: Besarnya kerugian materiil yang dialami korban
2. **Jumlah Korban**: Banyaknya korban yang terdampak dari kasus
3. **Urgensi**: Tingkat kepentingan atau seberapa cepat kasus harus ditangani
4. **Potensi Penyebaran**: Kemungkinan kasus menyebar dan menimbulkan dampak lebih luas

## Struktur Direktori

```
nopal/
├── assets/
│   ├── css/
│   │   └── style.css          # Stylesheet utama
│   ├── js/
│   │   ├── script.js          # JavaScript utama
│   │   └── sidebar.js         # JavaScript sidebar
│   └── img/
│       └── background.jpg     # Gambar latar belakang
├── auth/
│   ├── login.php             # Halaman login
│   ├── register.php          # Halaman registrasi
│   ├── logout.php            # Proses logout
│   ├── handle_login.php      # Handler login
│   └── handle_register.php   # Handler registrasi
├── config/
│   └── database.php          # Konfigurasi database
├── includes/
│   ├── header.php            # Header template
│   └── footer.php            # Footer template
├── pages/
│   ├── dashboard.php         # Dashboard utama
│   ├── ahp.php              # Halaman AHP
│   ├── topsis.php           # Halaman TOPSIS
│   ├── results.php          # Halaman hasil
│   └── welcome.php          # API endpoint
├── admin/
│   └── manage_users.php     # Manajemen pengguna
├── index.php                # Halaman utama
├── database_schema.sql      # Schema database
└── README.md               # Dokumentasi
```

## Instalasi

### 1. Persiapan Server
- Pastikan Apache dan MySQL berjalan (XAMPP/WAMP/LAMP)
- PHP 7.4+ dan MySQL 5.7+
- Extension PHP: PDO, mysqli

### 2. Setup Database
```sql
-- Buat database baru
CREATE DATABASE dss_online_crime;

-- Import schema
mysql -u root -p dss_online_crime < database_schema.sql
```

### 3. Konfigurasi Database
Edit file `config/database.php` sesuai dengan pengaturan server Anda:
```php
$host = 'localhost';
$dbname = 'dss_online_crime';
$username = 'root';
$password = '';
```

### 4. Menjalankan Sistem
1. Letakkan folder proyek di direktori web server (htdocs/www)
2. Akses melalui browser: `http://localhost/nopal/`
3. Register akun baru atau gunakan akun default (jika ada)

## Penggunaan

### 1. Registrasi & Login
- Klik tombol "Register" di halaman utama
- Isi form registrasi (role otomatis: client)
- Login menggunakan akun yang telah dibuat

### 2. Konfigurasi AHP
- Masuk ke halaman AHP
- Lakukan perbandingan berpasangan antar kriteria
- Sistem akan menghitung bobot secara otomatis
- Pastikan rasio konsistensi < 0.1

### 3. Input Data Kasus (TOPSIS)
- Masuk ke halaman TOPSIS
- Input data kasus dengan skor untuk setiap kriteria
- Klik "Hitung TOPSIS" untuk mendapatkan prioritas

### 4. Melihat Hasil
- Hasil prioritas ditampilkan dalam bentuk tabel dan grafik
- Export hasil ke Excel atau print laporan
- Analisis perbandingan antar kasus

## Fitur Teknis

### Frontend
- **Bootstrap 5**: Framework CSS responsif
- **Font Awesome**: Icon library
- **Chart.js**: Visualisasi data (opsional)
- **Vanilla JavaScript**: Interaktivitas tanpa dependency

### Backend
- **PHP 7.4+**: Server-side scripting
- **MySQL**: Database management
- **PDO**: Database abstraction layer
- **Session Management**: Autentikasi pengguna

### Keamanan
- Password hashing dengan `password_hash()`
- Prepared statements untuk mencegah SQL injection
- Input validation dan sanitization
- Session-based authentication

## API Endpoints

### Authentication
- `POST /auth/handle_login.php` - Login pengguna
- `POST /auth/handle_register.php` - Registrasi pengguna
- `GET /auth/logout.php` - Logout pengguna

### System
- `GET /pages/welcome.php` - API status check
- `GET /pages/dashboard.php` - Dashboard data
- `POST /pages/ahp.php` - Perhitungan AHP
- `POST /pages/topsis.php` - Perhitungan TOPSIS

## Pengembangan

### Menambah Kriteria Baru
1. Update database schema
2. Modifikasi form input di `pages/ahp.php`
3. Update algoritma perhitungan
4. Sesuaikan tampilan hasil

### Kustomisasi Tampilan
- Edit `assets/css/style.css` untuk styling
- Modifikasi `includes/header.php` dan `includes/footer.php`
- Update `assets/js/script.js` untuk interaktivitas

### Integrasi Database Eksternal
- Modifikasi `config/database.php`
- Update query di masing-masing halaman
- Sesuaikan struktur tabel jika diperlukan

## Troubleshooting

### Error Database Connection
- Periksa kredensial di `config/database.php`
- Pastikan MySQL service berjalan
- Cek apakah database sudah dibuat

### Modal Tidak Muncul
- Pastikan Bootstrap JS ter-load
- Cek console browser untuk error JavaScript
- Verifikasi struktur HTML modal

### Perhitungan AHP/TOPSIS Error
- Validasi input data (harus numerik)
- Cek konsistensi matriks AHP
- Pastikan semua kriteria terisi

## Kontribusi

1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Sistem ini dibuat untuk tujuan edukasi dan penelitian. Dapat digunakan dan dimodifikasi sesuai kebutuhan dengan tetap mencantumkan sumber asli.

## Kontak

**Polsek Saribudolok**
- Alamat: Saribudolok, Kabupaten Simalungun, Sumatera Utara
- Email: info@polseksaribudolok.go.id
- Telepon: +62 xxx-xxxx-xxxx

---

*Dikembangkan dengan ❤️ untuk meningkatkan efektivitas penanganan kejahatan online*
