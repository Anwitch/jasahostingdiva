<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_spbu']);
    $wa   = mysqli_real_escape_string($conn, $_POST['no_whatsapp']);
    $jam  = mysqli_real_escape_string($conn, $_POST['status_24jam']);
    $lat  = (float)$_POST['latitude'];
    $lng  = (float)$_POST['longitude'];

    $query = "INSERT INTO spbu (nama_spbu, no_whatsapp, status_24jam, latitude, longitude) 
              VALUES ('$nama', '$wa', '$jam', '$lat', '$lng')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data SPBU berhasil disimpan!');
                window.location='index.php';
              </script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>