<?php
include 'koneksi.php';

// 1. Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id']) && $_GET['id'] != "") {
    
    $id = $_GET['id'];

    // 2. Gunakan mysqli_real_escape_string untuk keamanan tambahan
    $id = mysqli_real_escape_string($conn, $id);

    // 3. Jalankan query hapus
    $query = "DELETE FROM spbu WHERE id='$id'";
    
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, munculkan pesan dan kembali ke index
        echo "<script>
                alert('Data SPBU Berhasil Dihapus!');
                window.location='index.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location='index.php';
              </script>";
    }

} else {
    // Jika tidak ada ID di URL, langsung lempar balik ke index
    header("location:index.php");
}
?>