<?php
// Cek apakah web sedang berjalan di server online (Coolify) atau di lokal (Laragon)
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    // KONEKSI UNTUK LARAGON LOKAL KAMU
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "db_gis_spbu";
} else {
    // KONEKSI UNTUK SERVER ONLINE COOLIFY
    // (Silakan ganti sesuai dengan info database dari dashboard Coolify kamu)
    // KONEKSI UNTUK SERVER ONLINE COOLIFY (SUDAH DIPERBAIKI)
    $host = "so0ww048o44c0o0k8gg0kocg"; // Menggunakan ID service internal Coolify kamu
    $user = "mysql";                    // Sesuai dengan Normal User di gambar
    $pass = "BzGKKh22m8l320uQhLLa4cxstNdyDXGHPO1Qsv83UGNJ2Hy0PyCfrEKf46eD2hCD"; // Gunakan password user dari URL connection string kamu
    $db   = "default";                  // Sesuai dengan Initial Database di gambar Coolify
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>