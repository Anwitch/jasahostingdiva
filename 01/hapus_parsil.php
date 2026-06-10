<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "DELETE FROM parsil WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Parsil Berhasil Dihapus!');
                window.location='index.php';
              </script>";
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
}
?>