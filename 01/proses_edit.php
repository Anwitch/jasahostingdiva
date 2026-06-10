<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id   = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $wa   = mysqli_real_escape_string($conn, $_POST['wa']);
    $jam  = $_POST['jam'];

    $query = "UPDATE spbu SET nama_spbu='$nama', no_whatsapp='$wa', status_24jam='$jam' WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>