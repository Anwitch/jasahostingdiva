<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pemilik_tanah = mysqli_real_escape_string($conn, $_POST['pemilik_tanah']);
    $status_shm    = mysqli_real_escape_string($conn, $_POST['status_shm']);
    $luas_tanah    = (float)$_POST['luas_tanah'];
    $geojson       = mysqli_real_escape_string($conn, $_POST['geojson_coords']);

    $query = "INSERT INTO parsil (nama_pemilik, status_kepemilikan, luas_tanah, geojson)
              VALUES ('$pemilik_tanah', '$status_shm', '$luas_tanah', '$geojson')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = 'Data Parsil berhasil disimpan!';
    } else {
        $_SESSION['alert'] = 'Gagal menyimpan: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>