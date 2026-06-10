<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_warga        = (int)$_POST['id_warga'];
    $radius_baru_warga = (int)$_POST['radius_baru_warga'];

    $query = "UPDATE penduduk_miskin SET radius_warga = '$radius_baru_warga' WHERE id = '$id_warga'";

    if (mysqli_query($conn, $query)) {
        echo "ok";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>