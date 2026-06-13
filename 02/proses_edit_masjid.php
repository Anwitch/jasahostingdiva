<?php
session_start();
include 'koneksi.php';

$id           = $_POST['id'];
$nama_masjid  = $_POST['nama_masjid'];
$nama_pic     = $_POST['nama_pic'];
$radius_meter = $_POST['radius_meter'];

$id           = mysqli_real_escape_string($conn, $id);
$nama_masjid  = mysqli_real_escape_string($conn, $nama_masjid);
$nama_pic     = mysqli_real_escape_string($conn, $nama_pic);
$radius_meter = (int)$radius_meter;

$query = "UPDATE masjid SET 
          nama_masjid='$nama_masjid', 
          nama_pic='$nama_pic', 
          radius_meter='$radius_meter' 
          WHERE id='$id'";

$update = mysqli_query($conn, $query);

if ($update) {
    $_SESSION['alert'] = 'Data Masjid Berhasil Diperbarui!';
} else {
    $_SESSION['alert'] = 'Gagal memperbarui data ke database: ' . mysqli_error($conn);
}
header('Location: index.php');
exit;
?>