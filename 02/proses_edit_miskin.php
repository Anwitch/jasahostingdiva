<?php
include 'koneksi.php';

// Menangkap data dari form edit
$id = $_POST['id'];
$nama_kk = $_POST['nama_kk'];
$alamat = $_POST['alamat'];
$umur = (int)$_POST['umur'];
$pendidikan_terakhir = $_POST['pendidikan_terakhir'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Solusi pengaman: Cek jika form mengirimkan 'anggota_keluarga' atau 'jumlah_keluarga'
if (isset($_POST['anggota_keluarga'])) {
    $jumlah_keluarga = (int)$_POST['anggota_keluarga'];
} elseif (isset($_POST['jumlah_keluarga'])) {
    $jumlah_keluarga = (int)$_POST['jumlah_keluarga'];
} else {
    $jumlah_keluarga = 0; // Nilai default berupa angka agar tidak memicu fatal error database
}

// Query Update Data ke Database
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
    echo "<script>alert('Data warga miskin berhasil diperbarui!'); window.location='index.php';</script>";
} else {
    echo "Gagal memperbarui data: " . mysqli_error($conn);
}
?>