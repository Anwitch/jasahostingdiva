<?php
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#f8fafc; border-radius:8px;'>";
echo "<h2 style='color:#1e3a8a;'>🚀 Database Sync & Repair — Folder 01</h2>";
echo "<p>Sedang memperbaiki struktur tabel Tugas...</p><hr>";

$queries = [];

// 1. Buat/Perbaiki tabel jalan
$queries[] = "CREATE TABLE IF NOT EXISTS jalan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_jalan VARCHAR(100) NOT NULL,
    status_jalan VARCHAR(50) NOT NULL,
    panjang_jalan DOUBLE DEFAULT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Buat/Perbaiki tabel parsil (Kolom luas_tanah sudah aman di sini)
$queries[] = "CREATE TABLE IF NOT EXISTS parsil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pemilik VARCHAR(100) DEFAULT NULL,
    pemilik_tanah VARCHAR(100) DEFAULT NULL,
    status_kepemilikan VARCHAR(50) DEFAULT NULL,
    status_shm VARCHAR(50) DEFAULT NULL,
    luas_tanah VARCHAR(50) DEFAULT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Buat tabel SPBU
$queries[] = "CREATE TABLE IF NOT EXISTS spbu (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_spbu VARCHAR(100) NOT NULL,
    no_whatsapp VARCHAR(50) DEFAULT NULL,
    status_24jam VARCHAR(10) DEFAULT 'Tidak',
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    geojson TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 4. Buat tabel users (untuk login)
$queries[] = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 5. Masukkan akun default jika belum ada
$queries[] = "INSERT IGNORE INTO users (id, username, password, nama_lengkap, role) VALUES
(1, 'admin', 'admin123', 'Diva Schenka (Admin)', 'admin'),
(2, 'walikota', 'walikota123', 'Bapak Walikota', 'walikota');";

// Eksekusi pembaruan
$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

echo "<br><b style='color:green;'>✔ Selesai! Folder 01 siap digunakan kembali.</b>";
echo "<br><br><a href='index.php'>← Kembali ke Peta</a>";
echo "</div>";
?>