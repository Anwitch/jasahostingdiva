<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin','walikota'])) {
    header('Location: login.php'); exit;
}

// Tambah histori
if (isset($_POST['tambah'])) {
    $id_warga       = (int)$_POST['id_warga'];
    $jenis          = mysqli_real_escape_string($conn, $_POST['jenis_bantuan']);
    $jumlah         = mysqli_real_escape_string($conn, $_POST['jumlah'] ?? '');
    $tgl            = mysqli_real_escape_string($conn, $_POST['tanggal_bantuan']);
    $ket            = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');
    $petugas        = mysqli_real_escape_string($conn, $_SESSION['user_nama']);
    mysqli_query($conn, "INSERT INTO histori_bantuan (id_warga,jenis_bantuan,jumlah,tanggal_bantuan,keterangan,petugas) VALUES ($id_warga,'$jenis','$jumlah','$tgl','$ket','$petugas')");
    header('Location: histori_bantuan.php?added=1'); exit;
}

// Hapus histori
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM histori_bantuan WHERE id=$id");
    header('Location: histori_bantuan.php'); exit;
}

$warga_list = mysqli_query($conn, "SELECT id, nama_kk FROM penduduk_miskin ORDER BY nama_kk");
$histori    = mysqli_query($conn, "SELECT h.*, p.nama_kk FROM histori_bantuan h JOIN penduduk_miskin p ON h.id_warga=p.id ORDER BY h.tanggal_bantuan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histori Bantuan - WebGIS Pontianak</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; }
.topbar { background: #0f172a; color: white; padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; }
.topbar h1 { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.topbar h1 i { color: #22c55e; }
.topbar a { color: #94a3b8; text-decoration: none; font-size: 12px; margin-left: 16px; }
.topbar a:hover { color: white; }
.topbar-role { background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.container { max-width: 1100px; margin: 28px auto; padding: 0 20px; display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start; }
.card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
.card-header { padding: 18px 22px; border-bottom: 1px solid #f1f5f9; }
.card-header h2 { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.card-header h2 i { color: #22c55e; }
.card-body { padding: 20px 22px; }
.form-group { margin-bottom: 14px; }
.form-group label { font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 5px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-family: inherit; color: #0f172a; }
.btn-submit { width: 100%; padding: 11px; background: #0f172a; color: white; border: none; border-radius: 9px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px; }
table { width: 100%; border-collapse: collapse; }
th { background: #f8fafc; padding: 11px 14px; text-align: left; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; }
td { padding: 12px 14px; border-top: 1px solid #f1f5f9; font-size: 13px; }
.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 10px; font-weight: 700; }
.b-blt { background: #fef3c7; color: #d97706; }
.b-sembako { background: #dcfce7; color: #16a34a; }
.b-bpjs { background: #dbeafe; color: #2563eb; }
.b-lain { background: #f1f5f9; color: #475569; }
.btn-del { background: #fef2f2; color: #dc2626; border: none; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: none; }
.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; grid-column: 1/-1; }
</style>
</head>
<body>
<div class="topbar">
    <h1><i class="fa-solid fa-hand-holding-heart"></i> Histori Bantuan Warga</h1>
    <div style="display:flex;align-items:center;gap:8px;">
        <span class="topbar-role"><?= htmlspecialchars($_SESSION['user_nama']) ?> (<?= $_SESSION['user_role'] ?>)</span>
        <a href="index.php"><i class="fa-solid fa-map"></i> Peta</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</div>

<div class="container">
    <?php if (isset($_GET['added'])): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Data bantuan berhasil ditambahkan!</div>
    <?php endif; ?>

    <!-- FORM TAMBAH -->
    <div class="card">
        <div class="card-header"><h2><i class="fa-solid fa-plus-circle"></i> Tambah Bantuan</h2></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Nama Warga</label>
                    <select name="id_warga" required>
                        <option value="">-- Pilih Warga --</option>
                        <?php while($w = mysqli_fetch_assoc($warga_list)): ?>
                        <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['nama_kk']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenis Bantuan</label>
                    <select name="jenis_bantuan" required>
                        <option value="BLT">BLT (Bantuan Langsung Tunai)</option>
                        <option value="Sembako">Paket Sembako</option>
                        <option value="BPJS">BPJS Kesehatan</option>
                        <option value="PKH">PKH</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah / Nominal</label>
                    <input type="text" name="jumlah" placeholder="Contoh: Rp 600.000 / 5 Kg Beras">
                </div>
                <div class="form-group">
                    <label>Tanggal Bantuan</label>
                    <input type="date" name="tanggal_bantuan" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>
                <button type="submit" name="tambah" class="btn-submit">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Bantuan
                </button>
            </form>
        </div>
    </div>

    <!-- TABEL HISTORI -->
    <div class="card">
        <div class="card-header"><h2><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Bantuan</h2></div>
        <table>
            <thead>
                <tr><th>#</th><th>Warga</th><th>Jenis</th><th>Jumlah</th><th>Tanggal</th><th>Petugas</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php $no=1; while ($h = mysqli_fetch_assoc($histori)): 
                $bc = ['BLT'=>'b-blt','Sembako'=>'b-sembako','BPJS'=>'b-bpjs'][$h['jenis_bantuan']] ?? 'b-lain';
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><strong><?= htmlspecialchars($h['nama_kk']) ?></strong></td>
                <td><span class="badge <?= $bc ?>"><?= $h['jenis_bantuan'] ?></span></td>
                <td><?= htmlspecialchars($h['jumlah'] ?? '-') ?></td>
                <td style="font-size:12px;color:#64748b;"><?= date('d M Y', strtotime($h['tanggal_bantuan'])) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($h['petugas'] ?? '-') ?></td>
                <td><a href="?hapus=<?= $h['id'] ?>" class="btn-del" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>