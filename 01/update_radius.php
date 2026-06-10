<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id          = (int)$_POST['id'];
    $radius_baru = (int)$_POST['radius_baru'];

    $query = "UPDATE masjid SET radius_meter = '$radius_baru' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "ok";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>