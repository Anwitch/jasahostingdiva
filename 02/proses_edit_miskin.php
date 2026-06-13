<?php
session_start();
include 'koneksi.php';

$id = $_POST['id'];
$nama_kk = $_POST['nama_kk'];
$alamat = $_POST['alamat'];
$umur = (int)$_POST['umur'];
$pendidikan_terakhir = $_POST['pendidikan_terakhir'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

if (isset($_POST['anggota_keluarga'])) {
    $jumlah_keluarga = (int)$_POST['anggota_keluarga'];
} elseif (isset($_POST['jumlah_keluarga'])) {
    $jumlah_keluarga = (int)$_POST['jumlah_keluarga'];
} else {
    $jumlah_keluarga = 0;
}

$query = "UPDATE penduduk_miskin SET 
          nama_kk = '$nama_kk', 
          alamat = '$alamat', 
          jumlah_keluarga = '$jumlah_keluarga', 
          umur = '$umur', 
          pendidikan_terakhir = '$pendidikan_terakhir', 
          latitude = '$latitude', 
          longitude = '$longitude' 
          WHERE id = '$id'";

$n = mysqli_query($conn, $query);

if ($n) {
    $_SESSION['alert'] = 'Data warga miskin berhasil diperbarui!';
} else {
    $_SESSION['alert'] = 'Gagal memperbarui data: ' . mysqli_error($conn);
}
header('Location: index.php');
exit;
?>