<?php
// PHP 8.1+ membuat mysqli melempar exception saat gagal koneksi (bukan return false).
// Kembalikan ke mode lama agar @mysqli_connect() & pengecekan if(!$conn) di bawah berfungsi.
mysqli_report(MYSQLI_REPORT_OFF);

// Cek apakah web sedang berjalan di dalam container Docker
$is_docker = file_exists('/.dockerenv');

// Membaca kredensial dari environment variables (sangat direkomendasikan untuk Coolify)
$host = getenv('DB_HOST') ?: (getenv('SERVICE_MYSQL_HOST') ?: null);
$user = getenv('DB_USER') ?: (getenv('SERVICE_MYSQL_USER') ?: null);
$pass = getenv('DB_PASSWORD') ?: (getenv('SERVICE_MYSQL_PASSWORD') ?: null);
$db   = getenv('DB_DATABASE') ?: (getenv('SERVICE_MYSQL_DB') ?: null);

// Jika ada URL koneksi tunggal (misal DATABASE_URL / SERVICE_DATABASE_URL)
$db_url = getenv('DATABASE_URL') ?: (getenv('SERVICE_DATABASE_URL') ?: null);
if ($db_url) {
    $host = $db_url;
}

// Helper untuk parsing connection string (mysql://user:pass@host:port/db)
function parse_db_uri($uri, &$host, &$user, &$pass, &$db) {
    if ($uri && strpos($uri, '://') !== false) {
        $parsed = parse_url($uri);
        $host = $parsed['host'] ?? $host;
        $port = $parsed['port'] ?? null;
        if ($port) {
            $host = $host . ":" . $port;
        }
        if (isset($parsed['user'])) {
            $user = $parsed['user'];
        }
        if (isset($parsed['pass'])) {
            $pass = $parsed['pass'];
        }
        if (isset($parsed['path'])) {
            $db = ltrim($parsed['path'], '/');
        }
    }
}

// Lakukan parsing jika $host berupa URI
parse_db_uri($host, $host, $user, $pass, $db);

// Jika environment variables tidak terdefinisi (seperti di lokal/development), gunakan auto-detection fallback
if (!$host) {
    // Ambil host dari request, buang port jika ada (mis. "localhost:8000" -> "localhost")
    $hostHeader = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
    $hostHeader = preg_replace('/:\d+$/', '', $hostHeader);

    if (in_array($hostHeader, ['localhost', '127.0.0.1', '::1', ''], true)) {
        // KONEKSI UNTUK LOKAL (Laragon / Docker Lokal)
        // Jika di dalam Docker, gunakan host.docker.internal untuk mengakses MySQL di host Windows
        $host = $is_docker ? "host.docker.internal" : "localhost";
        $user = "root";
        
        // Coba koneksi ke server dengan password "root" (bawaan MySQL Server Anda)
        // Jika gagal, gunakan password kosong "" (bawaan Laragon/XAMPP)
        $pass = "root";
        $test_conn = @mysqli_connect($host, $user, $pass);
        if (!$test_conn) {
            $pass = "";
        } else {
            mysqli_close($test_conn);
        }
        
        $db   = "db_gis_spbu"; // Silakan sesuaikan jika nama database lokal Anda berbeda
    } else {
        // FALLBACK KREDENSIAL SERVER ONLINE COOLIFY jika env vars tidak diatur
        // Menggunakan kredensial database online yang Anda deploy
        $host = "fwk0cgc840wg0gc8g04gs0gs:3306";
        $user = "mysql";
        $db   = "default";
        
        // Coba hubungkan dengan db_pass (V7aRN...) terlebih dahulu
        $pass = "V7aRN0kJdnaT0kWlYiHWMF4hYWDfXUFFyztql7IeoW7FrKm0hcWFRKOim5kDU4Hi";
        $test_conn = @mysqli_connect($host, $user, $pass, $db);
        if (!$test_conn) {
            // Jika gagal, gunakan password dari connection string (KvEkb...)
            $pass = "KvEkbHqNswbc2zSA4HiqvtzxQwfegOiUUkiZBg6pCRz5JBhEpSbNyHRytgtXwXMn";
        } else {
            mysqli_close($test_conn);
        }
    }
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>