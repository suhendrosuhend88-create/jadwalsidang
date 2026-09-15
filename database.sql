CREATE DATABASE IF NOT EXISTS jadwal_sidang_ikh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jadwal_sidang_ikh;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS schedules (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(150) NOT NULL,
 nim VARCHAR(50) NOT NULL,
 prodi VARCHAR(150) NOT NULL,
 judul TEXT NOT NULL,
 tanggal DATE NOT NULL,
 waktu TIME NOT NULL,
 ruangan VARCHAR(100) NOT NULL,
 ketua_penguji VARCHAR(150) NOT NULL,
 penguji_2 VARCHAR(150) DEFAULT '',
 pembimbing VARCHAR(150) DEFAULT '',
 jenis_sidang VARCHAR(100) NOT NULL DEFAULT 'Sidang Skripsi',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_tanggal (tanggal),
 INDEX idx_prodi (prodi)
);

-- Password awal: admin123
INSERT INTO users (username,password)
SELECT 'admin', '$2y$10$7V8YvQ9H1r7Yh8xWQ1yY6O9e0h4G8Jm5vK6u9g6q8y8W5x4g6s1eS'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username='admin');

INSERT INTO schedules (nama,nim,prodi,judul,tanggal,waktu,ruangan,ketua_penguji,penguji_2,pembimbing,jenis_sidang)
VALUES
('Contoh Mahasiswa','000000000','S1 Kedokteran','Contoh Judul Karya Ilmiah','2026-10-01','09:00:00','Ruang Sidang 1','Dr. Contoh, M.Kes.','Ns. Contoh, M.Kep.','Dr. Pembimbing, M.Kes.','Sidang Skripsi');
