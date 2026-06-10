<?php
include 'koneksi.php';

// Ambil data kiriman dari form edit_masjid.php
$id           = $_POST['id'];
$nama_masjid  = $_POST['nama_masjid'];
$nama_pic     = $_POST['nama_pic'];
$radius_meter = $_POST['radius_meter'];

// Proteksi data sederhana agar aman dari bad character
$id           = mysqli_real_escape_string($conn, $id);
$nama_masjid  = mysqli_real_escape_string($conn, $nama_masjid);
$nama_pic     = mysqli_real_escape_string($conn, $nama_pic);
$radius_meter = (int)$radius_meter;

// Query update data masjid berdasarkan ID
$query = "UPDATE masjid SET 
          nama_masjid='$nama_masjid', 
          nama_pic='$nama_pic', 
          radius_meter='$radius_meter' 
          WHERE id='$id'";

$update = mysqli_query($conn, $query);

if ($update) {
    // Jika berhasil, langsung lempar balik secara otomatis ke index.php (Peta)
    echo "<script>
            alert('Data Masjid Berhasil Diperbarui!');
            window.location.href='index.php';
          </script>";
} else {
    // Jika gagal, tampilkan pesan errornya biar ketahuan salahnya di mana
    echo "Gagal memperbarui data ke database: " . mysqli_error($conn);
}
?>