<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama_pelapor = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $kontak_pelapor = mysqli_real_escape_string($conn, $_POST['kontak_pelapor']);
    $deskripsi_laporan = mysqli_real_escape_string($conn, $_POST['deskripsi_laporan']);

    $query = "UPDATE pengaduan_warga SET 
                nama_pelapor = '$nama_pelapor', 
                kontak_pelapor = '$kontak_pelapor', 
                deskripsi_laporan = '$deskripsi_laporan' 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Laporan pengaduan berhasil diperbarui!';
    } else {
        $_SESSION['alert'] = 'Gagal memperbarui data: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>