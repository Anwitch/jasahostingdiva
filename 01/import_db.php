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
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Buat/Perbaiki tabel parsil (Ditambahkan kolom luas_tanah)
$queries[] = "CREATE TABLE IF NOT EXISTS parsil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pemilik VARCHAR(100) DEFAULT NULL,
    pemilik_tanah VARCHAR(100) DEFAULT NULL,
    status_kepemilikan VARCHAR(50) DEFAULT NULL,
    status_shm VARCHAR(50) DEFAULT NULL,
    luas_tanah VARCHAR(50) DEFAULT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Buat tabel SPBU yang hilang (Solusi Error simpan_spbu.php)
$queries[] = "CREATE TABLE IF NOT EXISTS spbu (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_spbu VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    geojson TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Eksekusi pembaruan
$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

// Fitur Tambahan: Suntik paksa kolom luas_tanah jika tabel parsil lama sudah terlanjur ada
mysqli_query($conn, "ALTER TABLE parsil ADD COLUMN IF NOT EXISTS luas_tanah VARCHAR(50) DEFAULT NULL;");

echo "<br><b style='color:green;'>✔ Selesai! Folder 01 siap digunakan kembali.</b>";
echo "<br><br><a href='index.php'>← Kembali ke Peta</a>";
echo "</div>";
?>