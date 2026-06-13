<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_masjid = mysqli_real_escape_string($conn, $_POST['nama_masjid'] ?? '');
    $nama_pic    = mysqli_real_escape_string($conn, $_POST['nama_pic'] ?? '');
    $lat         = (float)($_POST['latitude'] ?? 0);
    $lng         = (float)($_POST['longitude'] ?? 0);

    $query = "INSERT INTO masjid (nama_masjid, nama_pic, latitude, longitude)
              VALUES ('$nama_masjid', '$nama_pic', $lat, $lng)";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Data Masjid berhasil disimpan!';
    } else {
        $_SESSION['alert'] = 'Gagal menyimpan: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>
