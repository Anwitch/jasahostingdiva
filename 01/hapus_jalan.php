<?php
session_start();
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    if (mysqli_query($conn, "DELETE FROM jalan WHERE id='$id'")) {
        $_SESSION['alert'] = 'Data Jalan Berhasil Dihapus!';
    } else {
        $_SESSION['alert'] = 'Gagal menghapus data: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>