<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id']) && $_GET['id'] != "") {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "DELETE FROM spbu WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Data SPBU Berhasil Dihapus!';
    } else {
        $_SESSION['alert'] = 'Gagal menghapus data: ' . mysqli_error($conn);
    }
}
header("Location: index.php");
exit;
?>