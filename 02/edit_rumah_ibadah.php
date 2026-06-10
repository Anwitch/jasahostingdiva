<?php
include 'koneksi.php';
$id = $_GET['id'];
// Proteksi string agar aman dari error sql injection
$id = mysqli_real_escape_string($conn, $id);
$data = mysqli_query($conn, "SELECT * FROM masjid WHERE id='$id'");
$d = mysqli_fetch_array($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Masjid</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 50px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        h3 { color: #10b981; text-align: center; margin-bottom: 20px; }
        label { font-size: 13px; font-weight: 600; color: #475569; display: block; margin-top: 10px; }
        input { width: 100%; padding: 10px; margin: 5px 0 15px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px; }
        button:hover { background: #059669; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Edit Lokasi Masjid</h3>
        <form action="proses_edit_masjid.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
            
            <label>Nama Masjid</label>
            <input type="text" name="nama_masjid" value="<?php echo $d['nama_masjid']; ?>" required>
            
            <label>Nama PIC</label>
            <input type="text" name="nama_pic" value="<?php echo $d['nama_pic']; ?>" required>
            
            <label>Radius Jangkauan (Meter)</label>
            <input type="number" name="radius_meter" value="<?php echo $d['radius_meter']; ?>" required>
            
            <button type="submit">UPDATE DATA MASJID</button>
            <center><br><a href="index.php" style="text-decoration:none; color:gray; font-size:13px;">← Kembali ke Peta</a></center>
        </form>
    </div>
</body>
</html>