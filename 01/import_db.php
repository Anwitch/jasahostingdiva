<?php
// Menyertakan file koneksi yang sudah kamu perbaiki sebelumnya
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#f8fafc; border-radius:8px;'>";
echo "<h2 style='color:#1e3a8a;'>🚀 Database Auto-Importer & Repair — Folder 01</h2>";
echo "<p>Sedang mengeksekusi migrasi tabel dan perbaikan kolom...</p><hr>";

// Kumpulan query SQL untuk menyusun tabel dan memperbaiki error 'radius_warga' kemarin
$queries = [];

$queries[] = "CREATE TABLE IF NOT EXISTS jalan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_jalan VARCHAR(100) NOT NULL,
    status_jalan VARCHAR(50) NOT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS parsil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pemilik VARCHAR(100) DEFAULT NULL,
    pemilik_tanah VARCHAR(100) DEFAULT NULL,
    status_kepemilikan VARCHAR(50) DEFAULT NULL,
    status_shm VARCHAR(50) DEFAULT NULL,
    geojson TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Trik Aman: Cek dan tambah kolom radius_warga jika belum ada di tabel penduduk/titik krisis kamu
// Ganti 'penduduk_miskin' dengan nama tabel titik utama kamu di folder 01 jika berbeda
$queries[] = "ALTER TABLE parsil ADD COLUMN IF NOT EXISTS radius_warga INT(11) DEFAULT 0;"; 

// Eksekusi satu per satu agar jika ada yang salah, query lain tetap jalan
$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

echo "<br><b style='color:green;'>✔ Selesai! Berhasil mengeksekusi $success perintah SQL di database target.</b>";
echo "<br><br><a href='index.php'>← Kembali ke Peta Utama</a>";
echo "</div>";
?>