<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM penduduk_miskin WHERE id='$id'");
$d = mysqli_fetch_array($data);

// Penanganan variasi nama kolom database (antisipasi huruf besar/kecil)
$jumlah_keluarga = $d['jumlah_keluarga'] ?? $d['anggota_keluarga'] ?? 0;
$umur = $d['umur'] ?? 0;
$pendidikan = $d['pendidikan_terakhir'] ?? '';
$lat = $d['latitude'] ?? $d['Latitude'] ?? '';
$lng = $d['longitude'] ?? $d['Longitude'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Miskin</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 40px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 380px; }
        label { font-size: 13px; font-weight: bold; color: #495057; display: block; margin-top: 10px; }
        input { width: 100%; padding: 10px; margin: 5px 0 12px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #fd7e14; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px; transition: background 0.2s; }
        button:hover { background: #e8590c; }
    </style>
</head>
<body>
    <div class="card">
        <h3 style="color:#fd7e14; text-align:center; margin-bottom: 20px;">Edit Data Warga</h3>
        <form action="proses_edit_miskin.php" method="POST">
            <!-- ID Warga (Hidden) -->
            <input type="hidden" name="id" value="<?php echo $d['id']; ?>">

            <label>Nama Kepala Keluarga (KK)</label>
            <input type="text" name="nama_kk" value="<?php echo $d['nama_kk']; ?>" required>

            <label>Alamat Domisili</label>
            <input type="text" name="alamat" value="<?php echo $d['alamat']; ?>" required>

            <!-- INPUT YANG SEBELUMNYA HILANG KITA TAMBAHKAN DI BAWAH INI -->
            <label>Jumlah Anggota Keluarga</label>
            <input type="number" name="anggota_keluarga" value="<?php echo $jumlah_keluarga; ?>" required>

            <label>Umur Kepala Keluarga</label>
            <input type="number" name="umur" value="<?php echo $umur; ?>" required>

            <label>Pendidikan Terakhir</label>
            <input type="text" name="pendidikan_terakhir" value="<?php echo $pendidikan; ?>" required>

            <label>Latitude (Titik Peta)</label>
            <input type="text" name="latitude" value="<?php echo $lat; ?>" required>

            <label>Longitude (Titik Peta)</label>
            <input type="text" name="longitude" value="<?php echo $lng; ?>" required>

            <button type="submit">UPDATE DATA WARGA</button>
        </form>
    </div>
</body>
</html>