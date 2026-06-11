<?php
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#fafaf9; border-radius:8px;'>";
echo "<h2 style='color:#0f766e;'>🏛 Database Importer Intervensi Walikota Pontianak — Folder 02 (Updated)</h2>";
echo "<p>Sedang membangun skema tabel relasional dan hak akses pengguna...</p><hr>";

$queries = [];

// 1. Tabel Otentikasi Pengguna (Users) untuk Login
$queries[] = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Isi Akun Default untuk Login (Username: admin, Password: admin)
// Menggunakan INSERT IGNORE agar jika di-refresh tidak menduplikat data
$queries[] = "INSERT IGNORE INTO users (id, username, password, nama_lengkap, role) 
              VALUES (1, 'admin', 'admin', 'Diva Schenka (Admin)', 'admin'),
                     (2, 'walikota', 'walikota', 'Walikota Pontianak', 'walikota');";

// 3. Tabel utama penduduk miskin krisis kesehatan Pontianak
$queries[] = "CREATE TABLE IF NOT EXISTS penduduk_miskin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kk VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    anggota_keluarga INT(11) NOT NULL,
    pendidikan_terakhir VARCHAR(50) NOT NULL,
    riwayat_penyakit VARCHAR(255) DEFAULT 'Tidak Ada',
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    status_verifikasi VARCHAR(30) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 4. Tabel histori bantuan yang berelasi dengan penduduk_miskin (One to Many)
$queries[] = "CREATE TABLE IF NOT EXISTS histori_bantuan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_miskin INT(11) NOT NULL,
    nama_bantuan VARCHAR(100) NOT NULL,
    tanggal_penyaluran DATE NOT NULL,
    sumber_dana VARCHAR(50) NOT NULL,
    keterangan TEXT,
    FOREIGN KEY (id_miskin) REFERENCES penduduk_miskin(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

echo "<br><b style='color:teal;'>✔ Selesai! Berhasil memperbarui $success skema tabel termasuk hak akses login.</b>";
echo "<br><p>Silakan coba login kembali menggunakan akun:</p>";
echo "<ul><li>Username: <b>admin</b> | Password: <b>admin</b></li><li>Username: <b>walikota</b> | Password: <b>walikota</b></li></ul>";
echo "<br><a href='login.php'>👉 Menuju Halaman Login</a>";
echo "</div>";
?>