<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kk           = mysqli_real_escape_string($conn, $_POST['nama_kk'] ?? '');
    $alamat            = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $anggota           = (int)($_POST['anggota_keluarga'] ?? 0);
    $umur              = (int)($_POST['umur'] ?? 0);
    $tanggal_lahir     = mysqli_real_escape_string($conn, $_POST['tanggal_lahir'] ?? '');
    $pendidikan        = mysqli_real_escape_string($conn, $_POST['pendidikan_terakhir'] ?? '');
    $riwayat_penyakit  = mysqli_real_escape_string($conn, $_POST['riwayat_penyakit'] ?? '');
    $lat               = (float)($_POST['latitude'] ?? 0);
    $lng               = (float)($_POST['longitude'] ?? 0);

    $sql = "INSERT INTO penduduk_miskin 
        (nama_kk, alamat, anggota_keluarga, umur, tanggal_lahir, pendidikan_terakhir, riwayat_penyakit, latitude, longitude)
        VALUES ('$nama_kk','$alamat',$anggota,$umur,'$tanggal_lahir','$pendidikan','$riwayat_penyakit',$lat,$lng)";

    mysqli_query($conn, $sql);
    header('Location: index.php');
    exit;
}
?>