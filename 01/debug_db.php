<?php
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#1e293b; color:#f8fafc; border-radius:12px; margin:20px;'>";
echo "<h2 style='color:#3b82f6; margin-top:0;'>🔍 Database Inspector — Folder 01</h2>";
echo "<p style='color:#94a3b8;'>Host: <b>$host</b> | Database: <b>$db</b></p><hr style='border-color:#334155;'>";

// 1. Ambil semua tabel yang ada di database
$result_tables = mysqli_query($conn, "SHOW TABLES");

if (mysqli_num_rows($result_tables) == 0) {
    echo "<p style='color:#ef4444; font-weight:bold;'>❌ Tidak ada tabel ditemukan di database ini! Silakan jalankan import_db.php terlebih dahulu.</p>";
} else {
    while ($table_row = mysqli_fetch_row($result_tables)) {
        $table_name = $table_row[0];
        echo "<div style='background:#0f172a; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #334155;'>";
        echo "<h3 style='color:#10b981; margin-top:0;'>📊 Tabel: <span style='color:#fff;'>$table_name</span></h3>";

        // 2. Ambil data isi tabel
        $data_result = mysqli_query($conn, "SELECT * FROM $table_name LIMIT 10");
        
        if (mysqli_num_rows($data_result) == 0) {
            echo "<p style='color:#e2e8f0; font-style:italic;'>Kosong (Belum ada data isi).</p>";
        } else {
            echo "<div style='overflow-x:auto;'>";
            echo "<table border='1' cellpadding='8' style='border-collapse:collapse; width:100%; text-align:left; border-color:#334155;'>";
            
            // Cetak Header Kolom
            echo "<tr style='background:#1e293b; color:#94a3b8;'>";
            $fields = mysqli_fetch_fields($data_result);
            foreach ($fields as $field) {
                echo "<th style='padding:10px;'>{$field->name}</th>";
            }
            echo "</tr>";

            // Cetak Baris Data
            while ($row = mysqli_fetch_assoc($data_result)) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td style='padding:10px; color:#cbd5e1;'> " . htmlspecialchars($cell ?? 'NULL') . " </td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<p style='font-size:12px; color:#64748b; margin-bottom:0;'>Menampilkan up to 10 data teratas.</p>";
        }
        echo "</div>";
    }
}
echo "<br><a href='index.php' style='color:#3b82f6; text-decoration:none; font-weight:bold;'>← Kembali ke Peta</a>";
echo "</div>";
?>