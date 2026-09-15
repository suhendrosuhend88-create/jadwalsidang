APLIKASI JADWAL SIDANG - INSTITUT KESEHATAN HELVETIA

Teknologi:
- PHP 8+
- MySQL/MariaDB
- Tailwind CSS via CDN
- Responsive untuk komputer, laptop, tablet, dan HP

FITUR:
1. Tampilan jadwal publik.
2. Login admin.
3. Tambah jadwal.
4. Hapus jadwal.
5. Pencarian nama/NIM/judul.
6. Filter program studi.
7. Filter tanggal.
8. Fullscreen tabel jadwal.
9. Database MySQL.

INSTALASI DI HOSTING:
1. Buat database MySQL bernama: jadwal_sidang_ikh
2. Import file database.sql melalui phpMyAdmin.
3. Upload seluruh file PHP ke public_html atau folder domain.
4. Edit config.php:
   $host = "localhost";
   $db = "jadwal_sidang_ikh";
   $user = "USERNAME_DATABASE";
   $pass = "PASSWORD_DATABASE";
5. Buka domain Anda.

LOGIN AWAL:
Username: admin
Password: admin123

PENTING:
Segera ganti password admin setelah instalasi untuk keamanan.
Untuk penggunaan banyak admin, dapat ditambahkan tabel role/user dan halaman manajemen pengguna.
