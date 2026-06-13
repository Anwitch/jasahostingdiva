<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "DELETE FROM parsil WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Data Parsil Berhasil Dihapus!';
    } else {
        $_SESSION['alert'] = 'Gagal menghapus data: ' . mysqli_error($conn);
    }
}
header("Location: index.php");
exit;
?>