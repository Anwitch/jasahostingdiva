<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelapor      = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $kontak_pelapor    = mysqli_real_escape_string($conn, $_POST['kontak_pelapor']);
    $deskripsi_laporan = mysqli_real_escape_string($conn, $_POST['deskripsi_laporan']);
    $latitude          = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude         = mysqli_real_escape_string($conn, $_POST['longitude']);

    $query = "INSERT INTO pengaduan_warga (nama_pelapor, kontak_pelapor, deskripsi_laporan, latitude, longitude) 
              VALUES ('$nama_pelapor', '$kontak_pelapor', '$deskripsi_laporan', '$latitude', '$longitude')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Laporan Pengaduan berhasil dikirim!';
    } else {
        $_SESSION['alert'] = 'Error: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>