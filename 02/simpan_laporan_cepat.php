<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_pelapor'] ?? '');
    $kontak   = mysqli_real_escape_string($conn, $_POST['kontak_pelapor'] ?? '');
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori'] ?? 'Lainnya');
    $deskripsi= mysqli_real_escape_string($conn, $_POST['deskripsi_laporan'] ?? '');
    $lat      = (float)($_POST['latitude'] ?? 0);
    $lng      = (float)($_POST['longitude'] ?? 0);

    $sql = "INSERT INTO laporan_cepat (nama_pelapor, kontak_pelapor, kategori, deskripsi, latitude, longitude)
            VALUES ('$nama','$kontak','$kategori','$deskripsi',$lat,$lng)";
    mysqli_query($conn, $sql);
    header('Location: index.php');
    exit;
}
?>