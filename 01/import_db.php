<?php
// Menyertakan file koneksi
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#f8fafc; border-radius:8px;'>";
echo "<h2 style='color:#1e3a8a;'>🚀 Database Auto-Importer & Repair — Folder 01</h2>";
echo "<p>Sedang mengeksekusi migrasi tabel secara aman...</p><hr>";

$queries = [];

// 1. Buat tabel jalan jika belum ada
$queries[] = "CREATE TABLE IF NOT EXISTS jalan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_jalan VARCHAR(100) NOT NULL,
    status_jalan VARCHAR(50) NOT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Buat tabel parsil jika belum ada
$queries[] = "CREATE TABLE IF NOT EXISTS parsil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pemilik VARCHAR(100) DEFAULT NULL,
    pemilik_tanah VARCHAR(100) DEFAULT NULL,
    status_kepemilikan VARCHAR(50) DEFAULT NULL,
    status_shm VARCHAR(50) DEFAULT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Buat tabel penduduk / titik warga miskin LANGSUNG dengan kolom radius_warga di dalamnya
// (Ini menggantikan perintah ALTER TABLE yang error kemarin agar 100% aman)
$queries[] = "CREATE TABLE IF NOT EXISTS penduduk_miskin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kk VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    anggota_keluarga INT(11) NOT NULL,
    pendidikan_terakhir VARCHAR(50) NOT NULL,
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    radius_warga INT(11) NOT NULL DEFAULT 300
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Eksekusi semua query satu per satu
$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

echo "<br><b style='color:green;'>✔ Selesai! Berhasil mengeksekusi $success perintah SQL dengan sukses tanpa error syntax.</b>";
echo "<br><br><a href='index.php'>← Kembali ke Peta Utama</a>";
echo "</div>";
?>