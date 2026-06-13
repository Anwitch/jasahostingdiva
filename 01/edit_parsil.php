<?php
session_start();
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM parsil WHERE id='$id'"));

if (isset($_POST['update'])) {
    $pemilik = $_POST['nama_pemilik'];
    $status = $_POST['status_kepemilikan'];
    mysqli_query($conn, "UPDATE parsil SET nama_pemilik='$pemilik', status_kepemilikan='$status' WHERE id='$id'");
    $_SESSION['alert'] = 'Data Parsil Diperbarui!';
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Parsil</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; background: #f0f2f5; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 350px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; background: #1a73e8; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Edit Data Parsil</h3>
        <form method="POST">
            <label>Nama Pemilik</label>
            <input type="text" name="nama_pemilik" value="<?= htmlspecialchars($data['nama_pemilik']) ?>" required>
            <label>Status Kepemilikan</label>
            <select name="status_kepemilikan">
                <option value="SHM" <?= ($data['status_kepemilikan'] == 'SHM') ? 'selected' : '' ?>>SHM</option>
                <option value="HGB" <?= ($data['status_kepemilikan'] == 'HGB') ? 'selected' : '' ?>>HGB</option>
                <option value="HGU" <?= ($data['status_kepemilikan'] == 'HGU') ? 'selected' : '' ?>>HGU</option>
                <option value="HP" <?= ($data['status_kepemilikan'] == 'HP') ? 'selected' : '' ?>>HP</option>
            </select>
            <button type="submit" name="update">Simpan Perubahan</button>
            <center><br><a href="index.php" style="text-decoration:none; color:gray; font-size:13px">← Kembali</a></center>
        </form>
    </div>
</body>
</html>