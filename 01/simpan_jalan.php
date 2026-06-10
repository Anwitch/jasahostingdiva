<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_jalan   = mysqli_real_escape_string($conn, $_POST['nama_jalan']);
    $status_jalan = mysqli_real_escape_string($conn, $_POST['status_jalan']);
    $panjang      = (float)$_POST['panjang_jalan'];
    $geojson      = mysqli_real_escape_string($conn, $_POST['geojson_coords']);

    $query = "INSERT INTO jalan (nama_jalan, status_jalan, panjang_jalan, geojson)
              VALUES ('$nama_jalan', '$status_jalan', '$panjang', '$geojson')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Jalan berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>