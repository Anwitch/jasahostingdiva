<?php
$host = "so0ww048o44c0o0k8gg0kocg"; // Menggunakan ID service internal Coolify kamu
$user = "mysql";                    // Sesuai dengan Normal User di gambar
$pass = "BzGKKh22m8l320uQhLLa4cxstNdyDXGHPO1Qsv83UGNJ2Hy0PyCfrEKf46eD2hCD"; // Gunakan password user dari URL connection string kamu
$db   = "default";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>