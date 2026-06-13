<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_jalan   = mysqli_real_escape_string($conn, $_POST['nama_jalan']);
    $status_jalan = mysqli_real_escape_string($conn, $_POST['status_jalan']);
    $panjang      = (float)$_POST['panjang_jalan'];
    $geojson      = mysqli_real_escape_string($conn, $_POST['geojson_coords']);

    $query = "INSERT INTO jalan (nama_jalan, status_jalan, panjang_jalan, geojson)
              VALUES ('$nama_jalan', '$status_jalan', '$panjang', '$geojson')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Data Jalan berhasil disimpan!';
    } else {
        $_SESSION['alert'] = 'Gagal menyimpan: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>