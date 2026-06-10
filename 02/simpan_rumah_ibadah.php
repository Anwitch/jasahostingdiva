<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_masjid = mysqli_real_escape_string($conn, $_POST['nama_masjid']);
    $nama_pic    = mysqli_real_escape_string($conn, $_POST['nama_pic']);
    $lat         = (float)$_POST['latitude'];
    $lng         = (float)$_POST['longitude'];

    $query = "INSERT INTO masjid (nama_masjid, nama_pic, latitude, longitude)
              VALUES ('$nama_masjid', '$nama_pic', '$lat', '$lng')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Masjid berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>