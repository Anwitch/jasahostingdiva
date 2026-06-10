<?php
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
        echo "<script>alert('Laporan Pengaduan berhasil dikirim!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>