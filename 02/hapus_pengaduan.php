<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "DELETE FROM pengaduan_warga WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Laporan pengaduan berhasil dihapus!';
    } else {
        $_SESSION['alert'] = 'Gagal menghapus data: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>