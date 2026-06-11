<?php
include 'koneksi.php';

echo "<div style='font-family:sans-serif; padding:20px; background:#0f172a; color:#f8fafc; border-radius:12px; margin:20px;'>";
echo "<h2 style='color:#14b8a6; margin-top:0;'>🏛 Database Jeroan Inspector — Folder 02 (UAS)</h2>";
echo "<p style='color:#64748b;'>Host Target: <b>$host</b> | Active Database: <b>$db</b></p><hr style='border-color:#1e293b;'>";

// 1. Ambil daftar tabel
$result_tables = mysqli_query($conn, "SHOW TABLES");

if (mysqli_num_rows($result_tables) == 0) {
    echo "<p style='color:#f43f5e; font-weight:bold;'>❌ Database masih kosong melompong! Jalankan file import_db.php dulu.</p>";
} else {
    while ($table_row = mysqli_fetch_row($result_tables)) {
        $table_name = $table_row[0];
        echo "<div style='background:#1e293b; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #334155;'>";
        echo "<h3 style='color:#38bdf8; margin-top:0;'>📁 Tabel: <span style='color:#fff;'>$table_name</span></h3>";

        // 2. Tampilkan isi baris data tabel
        $data_result = mysqli_query($conn, "SELECT * FROM $table_name LIMIT 10");
        
        if (mysqli_num_rows($data_result) == 0) {
            echo "<p style='color:#94a3b8; font-style:italic;'>Tabel ini sudah terbuat, tapi datanya masih kosong.</p>";
        } else {
            echo "<div style='overflow-x:auto;'>";
            echo "<table border='1' cellpadding='8' style='border-collapse:collapse; width:100%; text-align:left; border-color:#475569;'>";
            
            // Header Kolom
            echo "<tr style='background:#0f172a; color:#94a3b8;'>";
            $fields = mysqli_fetch_fields($data_result);
            foreach ($fields as $field) {
                echo "<th style='padding:10px;'>{$field->name}</th>";
            }
            echo "</tr>";

            // Isi Data Pengguna / Warga
            while ($row = mysqli_fetch_assoc($data_result)) {
                echo "<tr>";
                foreach ($row as $key => $cell) {
                    // Beri highlight khusus kolom password agar kita tahu isinya di-enkripsi atau tidak
                    if ($key == 'password') {
                        echo "<td style='padding:10px; color:#f43f5e; font-weight:bold; background:rgba(244,63,94,0.1);'>" . htmlspecialchars($cell) . "</td>";
                    } else {
                        echo "<td style='padding:10px; color:#e2e8f0;'>" . htmlspecialchars($cell ?? 'NULL') . "</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
        echo "</div>";
    }
}
echo "<br><a href='login.php' style='color:#14b8a6; text-decoration:none; font-weight:bold;'>← Kembali ke Login</a>";
echo "</div>";
?>