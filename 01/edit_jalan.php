<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jalan WHERE id='$id'"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama_jalan'];
    $status = $_POST['status_jalan'];
    mysqli_query($conn, "UPDATE jalan SET nama_jalan='$nama', status_jalan='$status' WHERE id='$id'");
    echo "<script>alert('Data Jalan Diperbarui!'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Jalan</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; background: #f0f2f5; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 350px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; background: #1a73e8; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Edit Data Jalan</h3>
        <form method="POST">
            <label>Nama Jalan</label>
            <input type="text" name="nama_jalan" value="<?= $data['nama_jalan'] ?>" required>
            <label>Status Jalan</label>
            <select name="status_jalan">
                <option value="Jalan Nasional" <?= ($data['status_jalan'] == 'Jalan Nasional') ? 'selected' : '' ?>>Jalan Nasional</option>
                <option value="Jalan Provinsi" <?= ($data['status_jalan'] == 'Jalan Provinsi') ? 'selected' : '' ?>>Jalan Provinsi</option>
                <option value="Jalan Kabupaten" <?= ($data['status_jalan'] == 'Jalan Kabupaten') ? 'selected' : '' ?>>Jalan Kabupaten</option>
            </select>
            <button type="submit" name="update">Simpan Perubahan</button>
            <center><br><a href="index.php" style="text-decoration:none; color:gray; font-size:13px">← Kembali</a></center>
        </form>
    </div>
</body>
</html>