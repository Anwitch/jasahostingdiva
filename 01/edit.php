<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$query = mysqli_query($conn, "SELECT * FROM spbu WHERE id='$id'");
$data  = mysqli_fetch_array($query);

if (!$data) {
    $_SESSION['alert'] = 'Data tidak ditemukan!';
    header('Location: index.php');
    exit;
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $wa   = mysqli_real_escape_string($conn, $_POST['wa']);
    $jam  = $_POST['jam'];
    
    $sql = "UPDATE spbu SET nama_spbu='$nama', no_whatsapp='$wa', status_24jam='$jam' WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = 'Data SPBU Berhasil Diperbarui!';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['alert'] = 'Gagal Update: ' . mysqli_error($conn);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data SPBU</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-top: 0;
            color: #1f2937;
            text-align: center;
            font-weight: 600;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #4b5563;
            font-size: 14px;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #2563eb;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
        }
        a:hover {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit SPBU</h2>
        <form method="POST">
            <label for="nama">Nama SPBU</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama_spbu']); ?>" required>

            <label for="wa">No. WhatsApp</label>
            <input type="text" id="wa" name="wa" value="<?php echo htmlspecialchars($data['no_whatsapp']); ?>" required>

            <label for="jam">Status 24 Jam</label>
            <select id="jam" name="jam">
                <option value="Aktif" <?php echo ($data['status_24jam'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Tidak Aktif" <?php echo ($data['status_24jam'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>

            <button type="submit" name="update">Update</button>
            <a href="index.php">Kembali</a>
        </form>
    </div>
</body>
</html>