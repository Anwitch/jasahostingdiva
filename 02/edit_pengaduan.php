<?php
include 'koneksi.php';
$id = mysqli_real_escape_string($conn, $_GET['id']);
$data = mysqli_query($conn, "SELECT * FROM pengaduan_warga WHERE id='$id'");
$d = mysqli_fetch_array($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Pengaduan</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 40px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: sans-serif; }
        button { width: 100%; padding: 10px; background: #dc2626; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        label { font-size: 12px; font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="card">
        <h3 style="color:#dc2626; text-align:center; margin-bottom:15px;">Edit Laporan Warga</h3>
        <form action="proses_edit_pengaduan.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
            
            <label>Nama Pelapor</label>
            <input type="text" name="nama_pelapor" value="<?php echo $d['nama_pelapor']; ?>" required>
            
            <label>Kontak Pelapor</label>
            <input type="text" name="kontak_pelapor" value="<?php echo $d['kontak_pelapor']; ?>" required>
            
            <label>Deskripsi Laporan / Aduan</label>
            <textarea name="deskripsi_laporan" rows="4" required><?php echo $d['deskripsi_laporan']; ?></textarea>
            
            <button type="submit">UPDATE LAPORAN</button>
        </form>
    </div>
</body>
</html>