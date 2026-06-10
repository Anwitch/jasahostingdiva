<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin','walikota'])) {
    header('Location: login.php'); exit;
}

// Update status laporan
if (isset($_POST['update_status'])) {
    $id     = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan= mysqli_real_escape_string($conn, $_POST['catatan_admin'] ?? '');
    mysqli_query($conn, "UPDATE laporan_cepat SET status='$status', catatan_admin='$catatan' WHERE id=$id");
    header('Location: kelola_laporan.php?updated=1'); exit;
}

$q = mysqli_query($conn, "SELECT * FROM laporan_cepat ORDER BY tgl_lapor DESC");
$total   = mysqli_num_rows($q);
$masuk   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM laporan_cepat WHERE status='Masuk'"));
$proses  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM laporan_cepat WHERE status='Diproses'"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM laporan_cepat WHERE status='Selesai'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Laporan - WebGIS Pontianak</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #0f172a; }
.topbar {
    background: #0f172a; color: white; padding: 14px 28px;
    display: flex; align-items: center; justify-content: space-between;
}
.topbar h1 { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.topbar h1 i { color: #3b82f6; }
.topbar-right { display: flex; align-items: center; gap: 12px; }
.topbar-role { background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.topbar a { color: #94a3b8; text-decoration: none; font-size: 12px; }
.topbar a:hover { color: white; }

.container { max-width: 1100px; margin: 28px auto; padding: 0 20px; }

.stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
.stat-card { background: white; border-radius: 14px; padding: 20px; border: 1px solid #e2e8f0; }
.stat-card .num { font-size: 28px; font-weight: 800; }
.stat-card .lbl { font-size: 12px; color: #64748b; margin-top: 4px; font-weight: 600; }
.stat-card .icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px; }

.card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
.card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
.card-header h2 { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.card-header h2 i { color: #3b82f6; }

table { width: 100%; border-collapse: collapse; }
th { background: #f8fafc; padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
td { padding: 13px 16px; border-top: 1px solid #f1f5f9; font-size: 13px; vertical-align: top; }
tr:hover td { background: #fafbfc; }

.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; }
.b-masuk    { background: #fef3c7; color: #d97706; }
.b-diproses { background: #dbeafe; color: #2563eb; }
.b-selesai  { background: #dcfce7; color: #16a34a; }
.b-kat { background: #f1f5f9; color: #475569; font-size: 10px; padding: 2px 7px; border-radius: 4px; font-weight: 600; }

.btn-sm { padding: 6px 12px; border-radius: 7px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
.btn-primary { background: #eff6ff; color: #2563eb; }
.btn-back { background: #0f172a; color: white; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }

.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
.modal-overlay.open { display: flex; }
.modal { background: white; border-radius: 18px; padding: 28px; width: 460px; max-width: 95vw; }
.modal h3 { font-size: 16px; font-weight: 700; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
.modal h3 i { color: #3b82f6; }
.form-group { margin-bottom: 14px; }
.form-group label { font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 5px; }
.form-group select, .form-group textarea { width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-family: inherit; }
.modal-footer { display: flex; gap: 8px; justify-content: flex-end; margin-top: 18px; }
.btn-cancel { background: #f1f5f9; color: #64748b; padding: 9px 18px; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; }
.btn-save { background: #0f172a; color: white; padding: 9px 18px; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; }

.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
</style>
</head>
<body>
<div class="topbar">
    <h1><i class="fa-solid fa-bell"></i> Kelola Laporan Masyarakat</h1>
    <div class="topbar-right">
        <span class="topbar-role"><i class="fa-solid fa-circle-user"></i> <?= htmlspecialchars($_SESSION['user_nama']) ?> (<?= $_SESSION['user_role'] ?>)</span>
        <a href="index.php"><i class="fa-solid fa-map"></i> Kembali ke Peta</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</div>

<div class="container">
    <?php if (isset($_GET['updated'])): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Status laporan berhasil diperbarui!</div>
    <?php endif; ?>

    <div class="stats">
        <div class="stat-card">
            <div class="icon" style="background:#f8fafc; color:#64748b;"><i class="fa-solid fa-list"></i></div>
            <div class="num"><?= $total ?></div><div class="lbl">Total Laporan</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:#fef3c7; color:#d97706;"><i class="fa-solid fa-inbox"></i></div>
            <div class="num" style="color:#d97706;"><?= $masuk ?></div><div class="lbl">Masuk / Baru</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:#dbeafe; color:#2563eb;"><i class="fa-solid fa-gears"></i></div>
            <div class="num" style="color:#2563eb;"><?= $proses ?></div><div class="lbl">Sedang Diproses</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:#dcfce7; color:#16a34a;"><i class="fa-solid fa-circle-check"></i></div>
            <div class="num" style="color:#16a34a;"><?= $selesai ?></div><div class="lbl">Selesai</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-table-list"></i> Daftar Laporan Masuk</h2>
            <a href="index.php" class="btn-back"><i class="fa-solid fa-map"></i> Lihat di Peta</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            mysqli_data_seek($q, 0);
            $no = 1;
            while ($d = mysqli_fetch_assoc($q)):
                $badgeClass = ['Masuk'=>'b-masuk','Diproses'=>'b-diproses','Selesai'=>'b-selesai'][$d['status']] ?? 'b-masuk';
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <strong><?= htmlspecialchars($d['nama_pelapor']) ?></strong><br>
                    <span style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($d['kontak_pelapor']) ?></span>
                </td>
                <td><span class="badge b-kat"><?= $d['kategori'] ?></span></td>
                <td style="max-width:220px;"><?= htmlspecialchars(substr($d['deskripsi'],0,80)) ?>...</td>
                <td style="font-size:12px;color:#64748b;"><?= date('d M Y', strtotime($d['tgl_lapor'])) ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= $d['status'] ?></span></td>
                <td>
                    <button class="btn-sm btn-primary" onclick="openModal(<?= $d['id'] ?>, '<?= $d['status'] ?>', '<?= addslashes($d['catatan_admin'] ?? '') ?>')">
                        <i class="fa-solid fa-pen"></i> Update
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL UPDATE STATUS -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <h3><i class="fa-solid fa-pen-to-square"></i> Update Status Laporan</h3>
        <form method="POST">
            <input type="hidden" name="update_status" value="1">
            <input type="hidden" name="id" id="modal-id">
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="modal-status">
                    <option value="Masuk">Masuk</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catatan Admin</label>
                <textarea name="catatan_admin" id="modal-catatan" rows="3" placeholder="Tulis catatan tindak lanjut..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, status, catatan) {
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-status').value = status;
    document.getElementById('modal-catatan').value = catatan;
    document.getElementById('modal').classList.add('open');
}
function closeModal() {
    document.getElementById('modal').classList.remove('open');
}
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
</body>
</html>