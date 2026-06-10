<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "DELETE FROM pengaduan_warga WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Laporan pengaduan berhasil dihapus!');
                window.location.href='index.php';
              </script>";
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
}
?>