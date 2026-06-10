<?php
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    if (mysqli_query($conn, "DELETE FROM jalan WHERE id='$id'")) {
        echo "<script>alert('Data Jalan Berhasil Dihapus!'); window.location='index.php';</script>";
    }
}
?>