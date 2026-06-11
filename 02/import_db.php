<?php
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#fafaf9; border-radius:8px;'>";
echo "<h2 style='color:#0f766e;'>🏛 Database Sync & Repair — Folder 02 (UAS)</h2>";
echo "<p>Sedang memperbaiki relasi data intervensi krisis...</p><hr>";

$queries = [];

// 1. Tabel Users
$queries[] = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "INSERT IGNORE INTO users (id, username, password, nama_lengkap, role) 
              VALUES (1, 'admin', 'admin', 'Diva Schenka (Admin)', 'admin');";

// 2. Tabel Penduduk Miskin (Ditambahkan kolom 'umur' sesuai error simpan_miskin.php)
$queries[] = "CREATE TABLE IF NOT EXISTS penduduk_miskin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kk VARCHAR(100) NOT NULL,
    tanggal_lahir DATE DEFAULT NULL,
    umur INT(11) DEFAULT NULL,
    alamat TEXT NOT NULL,
    anggota_keluarga INT(11) DEFAULT 0,
    pendidikan_terakhir VARCHAR(50) DEFAULT NULL,
    riwayat_penyakit VARCHAR(255) DEFAULT 'Tidak Ada',
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    status_verifikasi VARCHAR(30) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Tabel Histori Bantuan (Kolom relasi disesuaikan menjadi id_warga agar JOIN h.id_warga sukses)
$queries[] = "CREATE TABLE IF NOT EXISTS histori_bantuan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_warga INT(11) NOT NULL,
    nama_bantuan VARCHAR(100) NOT NULL,
    tanggal_penyaluran DATE NOT NULL,
    sumber_dana VARCHAR(50) NOT NULL,
    keterangan TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 4. Tabel laporan_cepat yang dilaporkan hilang (Solusi Error kelola_laporan.php)
$queries[] = "CREATE TABLE IF NOT EXISTS laporan_cepat (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pelapor VARCHAR(100) NOT NULL,
    perihal VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Eksekusi semua skema baru
$success = 0;
foreach ($queries as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        $success++;
    } else {
        echo "<p style='color:red;'>❌ Gagal pada query ke-" . ($index + 1) . ": " . mysqli_error($conn) . "</p>";
    }
}

// Trik Aman: Tambah kolom mendadak jika tabel lama masih mengunci di server
mysqli_query($conn, "ALTER TABLE penduduk_miskin ADD COLUMN IF NOT EXISTS umur INT(11) DEFAULT NULL;");
mysqli_query($conn, "ALTER TABLE histori_bantuan ADD COLUMN IF NOT EXISTS id_warga INT(11) NOT NULL;");

echo "<br><b style='color:teal;'>✔ Selesai! Seluruh struktur tabel UAS telah diselaraskan.</b>";
echo "<br><br><a href='index.php'>← Kembali ke Peta Utama</a>";
echo "</div>";
?>