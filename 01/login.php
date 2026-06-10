<?php
session_start();
include 'koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = md5(trim($_POST['password'] ?? ''));
    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $user = mysqli_fetch_assoc($q);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - WebGIS Pontianak</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --bg:        #0b1120;
  --bg2:       #111827;
  --card:      #161f30;
  --border:    rgba(255,255,255,0.07);
  --border2:   rgba(255,255,255,0.13);
  --text:      #f1f5f9;
  --muted:     #94a3b8;
  --accent:    #3b82f6;
  --accent2:   #60a5fa;
  --btn:       #3b82f6;
  --btn-text:  #ffffff;
  --input-bg:  #1e2b40;
  --input-bd:  rgba(255,255,255,0.1);
  --input-focus: #3b82f6;
  --tag-bg:    rgba(59,130,246,0.15);
  --tag-col:   #93c5fd;
  --shadow:    0 40px 100px rgba(0,0,0,0.6);
  --err-bg:    rgba(239,68,68,0.12);
  --err-bd:    rgba(239,68,68,0.3);
  --err-col:   #f87171;
}

[data-theme="light"] {
  --bg:        #e8eef7;
  --bg2:       #dde5f0;
  --card:      #ffffff;
  --border:    rgba(0,0,0,0.06);
  --border2:   rgba(0,0,0,0.12);
  --text:      #0f172a;
  --muted:     #64748b;
  --accent:    #2563eb;
  --accent2:   #3b82f6;
  --btn:       #2563eb;
  --btn-text:  #ffffff;
  --input-bg:  #f1f5fb;
  --input-bd:  rgba(0,0,0,0.1);
  --input-focus: #2563eb;
  --tag-bg:    #eff6ff;
  --tag-col:   #1d4ed8;
  --shadow:    0 40px 100px rgba(0,0,0,0.15);
  --err-bg:    #fef2f2;
  --err-bd:    #fecaca;
  --err-col:   #dc2626;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }

body {
  font-family: 'Sora', sans-serif;
  background: var(--bg);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  transition: background 0.4s;
  position: relative;
  overflow: hidden;
}

/* background orbs */
.orb {
  position: fixed;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.25;
  pointer-events: none;
  transition: opacity 0.4s;
}
.orb1 { width: 500px; height: 500px; background: #3b82f6; top: -150px; left: -100px; }
.orb2 { width: 400px; height: 400px; background: #8b5cf6; bottom: -100px; right: -80px; }
.orb3 { width: 300px; height: 300px; background: #06b6d4; top: 40%; left: 40%; }

[data-theme="light"] .orb { opacity: 0.12; }

/* theme toggle */
.theme-toggle {
  position: fixed;
  top: 20px; right: 20px;
  width: 44px; height: 44px;
  border-radius: 12px;
  background: var(--card);
  border: 1px solid var(--border2);
  color: var(--muted);
  font-size: 16px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.theme-toggle:hover { color: var(--accent); border-color: var(--accent); }

/* card */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 24px;
  width: 100%;
  max-width: 440px;
  padding: 48px 44px;
  box-shadow: var(--shadow);
  position: relative;
  z-index: 10;
  transition: background 0.4s, border-color 0.4s, box-shadow 0.4s;
  animation: fadeUp 0.5s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* logo */
.logo-wrap {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 36px;
}

.logo-icon {
  width: 52px; height: 52px;
  border-radius: 16px;
  background: linear-gradient(135deg, #2563eb, #7c3aed);
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
  color: #fff;
  flex-shrink: 0;
  box-shadow: 0 8px 24px rgba(37,99,235,0.4);
}

.logo-text h1 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text);
  transition: color 0.4s;
}

.logo-text p {
  font-size: 11px;
  color: var(--muted);
  margin-top: 2px;
  transition: color 0.4s;
}

/* badge */
.badge-live {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--tag-bg);
  color: var(--tag-col);
  font-size: 10px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 999px;
  margin-bottom: 20px;
  letter-spacing: 0.04em;
  transition: background 0.4s, color 0.4s;
}

.dot-live {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: currentColor;
  animation: pulse 1.8s infinite;
}

@keyframes pulse {
  0%,100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.4; transform: scale(0.7); }
}

/* heading */
.heading {
  font-size: 26px;
  font-weight: 700;
  color: var(--text);
  line-height: 1.2;
  margin-bottom: 6px;
  transition: color 0.4s;
}

.subheading {
  font-size: 13px;
  color: var(--muted);
  margin-bottom: 28px;
  transition: color 0.4s;
}

/* divider */
.divider {
  height: 1px;
  background: var(--border);
  margin: 0 -44px 28px;
  transition: background 0.4s;
}

/* form */
.field { margin-bottom: 18px; }

.field label {
  display: block;
  font-size: 11px;
  font-weight: 600;
  color: var(--muted);
  letter-spacing: 0.06em;
  text-transform: uppercase;
  margin-bottom: 8px;
  transition: color 0.4s;
}

.input-wrap { position: relative; }

.input-wrap i.ico {
  position: absolute;
  left: 14px; top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
  font-size: 14px;
  pointer-events: none;
  transition: color 0.4s;
}

.input-wrap input {
  width: 100%;
  padding: 13px 14px 13px 42px;
  background: var(--input-bg);
  border: 1.5px solid var(--input-bd);
  border-radius: 12px;
  font-size: 14px;
  font-family: 'Sora', sans-serif;
  color: var(--text);
  outline: none;
  transition: all 0.25s;
}

.input-wrap input::placeholder { color: var(--muted); opacity: 0.6; }
.input-wrap input:focus { border-color: var(--input-focus); background: var(--input-bg); box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }

/* eye toggle */
.eye-btn {
  position: absolute;
  right: 14px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none;
  color: var(--muted); font-size: 14px;
  cursor: pointer; padding: 0;
  transition: color 0.2s;
}
.eye-btn:hover { color: var(--accent); }

/* error */
.error-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--err-bg);
  border: 1px solid var(--err-bd);
  color: var(--err-col);
  padding: 11px 14px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 500;
  margin-bottom: 18px;
  animation: shake 0.4s ease;
}

@keyframes shake {
  0%,100% { transform: translateX(0); }
  20%,60% { transform: translateX(-6px); }
  40%,80% { transform: translateX(6px); }
}

/* button */
.btn-login {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #2563eb, #7c3aed);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 600;
  font-family: 'Sora', sans-serif;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  margin-top: 4px;
  box-shadow: 0 8px 24px rgba(37,99,235,0.35);
  transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
  letter-spacing: 0.02em;
}

.btn-login:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(37,99,235,0.45); filter: brightness(1.05); }
.btn-login:active { transform: translateY(0); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }

/* akun section */
.akun-label {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 24px 0 14px;
}
.akun-line { flex: 1; height: 1px; background: var(--border); transition: background 0.4s; }
.akun-text { font-size: 10px; color: var(--muted); font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; transition: color 0.4s; }

.akun-grid { display: flex; flex-direction: column; gap: 8px; }

.akun-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  border: 1px solid var(--border);
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s;
  background: transparent;
}

.akun-item:hover {
  border-color: var(--accent);
  background: var(--tag-bg);
}

.akun-left { display: flex; align-items: center; gap: 10px; }

.akun-icon {
  width: 32px; height: 32px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
}

.ai-admin { background: rgba(37,99,235,0.15); color: #60a5fa; }
.ai-wali  { background: rgba(245,158,11,0.15); color: #fbbf24; }

[data-theme="light"] .ai-admin { background: #eff6ff; color: #2563eb; }
[data-theme="light"] .ai-wali  { background: #fffbeb; color: #d97706; }

.akun-info-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  transition: color 0.4s;
}

.akun-info-pass {
  font-size: 11px;
  color: var(--muted);
  margin-top: 1px;
  transition: color 0.4s;
}

.akun-badge {
  font-size: 10px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 999px;
  letter-spacing: 0.03em;
}

.ab-admin { background: rgba(37,99,235,0.15); color: #60a5fa; }
.ab-wali  { background: rgba(245,158,11,0.15); color: #fbbf24; }

[data-theme="light"] .ab-admin { background: #eff6ff; color: #1d4ed8; }
[data-theme="light"] .ab-wali  { background: #fffbeb; color: #b45309; }
</style>
</head>
<body>

<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>

<button class="theme-toggle" onclick="toggleTheme()" title="Toggle tema">
  <i class="fa-solid fa-sun" id="theme-icon"></i>
</button>

<div class="card">

  <div class="logo-wrap">
    <div class="logo-icon"><i class="fa-solid fa-map-location-dot"></i></div>
    <div class="logo-text">
      <h1>WebGIS Pontianak</h1>
      <p>Sistem Informasi Geografis Kota Pontianak</p>
    </div>
  </div>

  <div class="badge-live">
    <span class="dot-live"></span> Sistem Online
  </div>

  <div class="heading">Selamat Datang</div>
  <div class="subheading">Masuk ke akun Anda untuk mengakses peta</div>

  <div class="divider"></div>

  <?php if ($error): ?>
  <div class="error-box">
    <i class="fa-solid fa-circle-exclamation"></i>
    <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="POST">
    <div class="field">
      <label>Username</label>
      <div class="input-wrap">
        <i class="fa-solid fa-user ico"></i>
        <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
      </div>
    </div>

    <div class="field">
      <label>Password</label>
      <div class="input-wrap">
        <i class="fa-solid fa-lock ico"></i>
        <input type="password" name="password" id="pw" placeholder="Masukkan password" required>
        <button type="button" class="eye-btn" onclick="togglePw()">
          <i class="fa-solid fa-eye" id="eye-icon"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn-login">
      <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Sistem
    </button>
  </form>

  <div class="akun-label">
    <div class="akun-line"></div>
    <span class="akun-text">Akun Tersedia</span>
    <div class="akun-line"></div>
  </div>

  <div class="akun-grid">
    <div class="akun-item" onclick="fillLogin('admin','admin123')">
      <div class="akun-left">
        <div class="akun-icon ai-admin"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <div class="akun-info-name">admin</div>
          <div class="akun-info-pass">admin123</div>
        </div>
      </div>
      <span class="akun-badge ab-admin">Administrator</span>
    </div>

    <div class="akun-item" onclick="fillLogin('walikota','walikota123')">
      <div class="akun-left">
        <div class="akun-icon ai-wali"><i class="fa-solid fa-star"></i></div>
        <div>
          <div class="akun-info-name">walikota</div>
          <div class="akun-info-pass">walikota123</div>
        </div>
      </div>
      <span class="akun-badge ab-wali">Walikota</span>
    </div>
  </div>

</div>

<script>
function toggleTheme() {
  const html = document.documentElement;
  const icon = document.getElementById('theme-icon');
  if (html.getAttribute('data-theme') === 'dark') {
    html.setAttribute('data-theme', 'light');
    icon.className = 'fa-solid fa-moon';
  } else {
    html.setAttribute('data-theme', 'dark');
    icon.className = 'fa-solid fa-sun';
  }
}

function togglePw() {
  const pw = document.getElementById('pw');
  const icon = document.getElementById('eye-icon');
  if (pw.type === 'password') {
    pw.type = 'text';
    icon.className = 'fa-solid fa-eye-slash';
  } else {
    pw.type = 'password';
    icon.className = 'fa-solid fa-eye';
  }
}

function fillLogin(user, pass) {
  document.querySelector('input[name="username"]').value = user;
  document.getElementById('pw').value = pass;
}
</script>
</body>
</html>