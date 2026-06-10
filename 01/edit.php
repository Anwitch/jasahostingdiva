<?php
include 'koneksi.php';

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 2. Ambil data lama dari database berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM spbu WHERE id='$id'");
$data  = mysqli_fetch_array($query);

// Jika data tidak ditemukan, balikkan ke index
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// 3. Proses Update saat tombol Simpan ditekan
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $wa   = mysqli_real_escape_string($conn, $_POST['wa']);
    $jam  = $_POST['jam'];
    
    // Update ke tabel spbu
    $sql = "UPDATE spbu SET nama_spbu='$nama', no_whatsapp='$wa', status_24jam='$jam' WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data SPBU Berhasil Diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "Gagal Update: " . mysqli_error($conn);
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
            background: #f0f2f5; 
        }
        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
        }
        h3 { 
            margin-top: 0; 
            color: #1a73e8; 
            text-align: center; 
            border-bottom: 2px solid #f0f2f5; 
            padding-bottom: 15px; 
            margin-bottom: 20px;
        }
        label { 
            font-size: 13px; 
            font-weight: 600; 
            color: #555; 
            display: block; 
            margin-bottom: 5px; 
        }
        input, select { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        input:focus, select:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
        }
        button { 
            width: 100%; 
            background: #1a73e8; 
            color: white; 
            border: none; 
            padding: 12px; 
            cursor: pointer; 
            border-radius: 8px; 
            font-weight: bold; 
            font-size: 15px;
            transition: 0.3s;
        }
        button:hover { 
            background: #1557b0; 
            box-shadow: 0 4px 12px rgba(26,115,232,0.3);
        }
        .btn-back { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            font-size: 13px; 
            text-decoration: none; 
            color: #666; 
        }
        .btn-back:hover { 
            color: #333; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="card">
        <h3>Edit Data SPBU</h3>
        <form method="POST">
            <label>NAMA SPBU</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_spbu']) ?>" placeholder="Masukkan nama SPBU" required>
            
            <label>NOMOR WHATSAPP</label>
            <input type="text" name="wa" value="<?= htmlspecialchars($data['no_whatsapp']) ?>" placeholder="Contoh: 08123456789" required>
            
            <label>STATUS OPERASIONAL</label>
            <select name="jam">
                <option value="Ya" <?= ($data['status_24jam'] == 'Ya') ? 'selected' : '' ?>>Buka 24 Jam (Ikon Hijau)</option>
                <option value="Tidak" <?= ($data['status_24jam'] == 'Tidak') ? 'selected' : '' ?>>Tutup Malam (Ikon Merah)</option>
            </select>
            
            <button type="submit" name="update">SIMPAN PERUBAHAN</button>
            <a href="index.php" class="btn-back">← Batal dan Kembali</a>
        </form>
    </div>
</body>
</html>