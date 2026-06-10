<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pemilik_tanah = mysqli_real_escape_string($conn, $_POST['pemilik_tanah']);
    $status_shm    = mysqli_real_escape_string($conn, $_POST['status_shm']);
    $luas_tanah    = (float)$_POST['luas_tanah'];
    $geojson       = mysqli_real_escape_string($conn, $_POST['geojson_coords']);

    // Coba insert dengan nama kolom nama_pemilik dan status_kepemilikan
    $query = "INSERT INTO parsil (nama_pemilik, status_kepemilikan, luas_tanah, geojson)
              VALUES ('$pemilik_tanah', '$status_shm', '$luas_tanah', '$geojson')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Parsil berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>