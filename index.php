<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebGIS Pontianak â€” Portal Sistem Informasi Geografis</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg: #F0F4F8;
    --surface: #ffffff;
    --surface2: #F7F9FB;
    --border: rgba(0,0,0,0.08);
    --border-md: rgba(0,0,0,0.13);
    --navy: #0C2D48;
    --navy2: #163D5E;
    --blue: #1A6FA8;
    --blue-light: #E8F3FB;
    --blue-mid: #378ADD;
    --green: #1D7A5F;
    --green-light: #E6F5F0;
    --amber: #B86800;
    --amber-light: #FEF3E2;
    --red: #C23B3B;
    --red-light: #FDEAEA;
    --purple: #5B5BD6;
    --purple-light: #EEF0FE;
    --text: #0C1B2A;
    --text2: #4A5E6E;
    --text3: #8A9BAA;
    --radius: 10px;
    --radius-lg: 16px;
    --shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.06);
    --shadow-lg: 0 8px 30px rgba(0,0,0,0.10);
  }

  body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    font-size: 14px;
    line-height: 1.6;
  }

  /* â”€â”€ NAVBAR â”€â”€ */
  .navbar {
    background: var(--navy);
    padding: 0 2rem;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }
  .brand-icon {
    width: 36px; height: 36px;
    background: var(--blue-mid); border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
  }
  .brand-icon svg { width: 18px; height: 18px; fill: none; stroke: #fff; stroke-width: 1.8; }
  .brand-name { font-family: 'Space Grotesk', sans-serif; font-size: 15px; font-weight: 600; color: #fff; }
  .brand-sub { font-size: 11px; color: rgba(255,255,255,0.45); }
  .nav-right { display: flex; align-items: center; gap: 8px; }
  .pill-online {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(29,122,95,0.25); color: #6DDDBC;
    font-size: 11px; font-weight: 500;
    padding: 4px 10px; border-radius: 20px;
    border: 1px solid rgba(109,221,188,0.2);
  }
  .pill-online::before {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; background: #6DDDBC;
    animation: blink 2s infinite;
  }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

  /* â”€â”€ HERO â”€â”€ */
  .hero {
    background: var(--navy2);
    position: relative; overflow: hidden;
    padding: 3rem 2.5rem 2.8rem;
    text-align: center;
  }
  .hero-grid-bg {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
  }
  .hero-content { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
  .hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 11px; font-weight: 500; letter-spacing: .06em;
    color: rgba(255,255,255,0.45); text-transform: uppercase;
    margin-bottom: 1.1rem;
  }
  .hero-eyebrow span { width: 20px; height: 1px; background: rgba(255,255,255,0.25); }
  .hero h1 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 36px; font-weight: 700;
    color: #fff; line-height: 1.18; margin-bottom: .75rem;
  }
  .hero h1 em { font-style: normal; color: #62C4F5; }
  .hero-desc {
    font-size: 14px; color: rgba(255,255,255,0.58);
    line-height: 1.75; max-width: 460px; margin: 0 auto 2rem;
  }
  .hero-chips { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }
  .hero-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.65);
    font-size: 11px; padding: 5px 12px; border-radius: 20px;
  }
  .hero-chip svg { width: 12px; height: 12px; }

  /* â”€â”€ MAIN â”€â”€ */
  .main { max-width: 960px; margin: 0 auto; padding: 2.5rem 1.5rem 3rem; }

  /* â”€â”€ PORTAL LABEL â”€â”€ */
  .section-label {
    font-size: 11px; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: var(--text3);
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: 10px;
  }
  .section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

  /* â”€â”€ PORTAL CARDS â”€â”€ */
  .portal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 2.5rem;
  }
  @media(max-width:640px){ .portal-grid { grid-template-columns: 1fr; } }

  .portal-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow);
    display: flex; flex-direction: column;
    transition: transform .18s, box-shadow .18s;
    text-decoration: none; color: inherit;
  }
  .portal-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
  }

  /* map preview area */
  .portal-map {
    height: 210px;
    position: relative;
    overflow: hidden;
  }
  .portal-map svg { width: 100%; height: 100%; display: block; }

  /* portal card body */
  .portal-body {
    padding: 1.1rem 1.25rem 1rem;
    flex: 1; display: flex; flex-direction: column;
  }
  .portal-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10px; font-weight: 600; letter-spacing: .04em;
    text-transform: uppercase;
    padding: 3px 9px; border-radius: 20px;
    margin-bottom: .65rem; width: fit-content;
  }
  .portal-badge svg { width: 10px; height: 10px; }
  .portal-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 17px; font-weight: 700;
    color: var(--text); margin-bottom: .35rem; line-height: 1.3;
  }
  .portal-desc {
    font-size: 12.5px; color: var(--text2);
    line-height: 1.65; margin-bottom: 1rem; flex: 1;
  }

  /* layer chips inside card */
  .layer-chips { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 1rem; }
  .lc {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 500;
    padding: 3px 9px; border-radius: 20px;
    background: var(--surface2); border: 1px solid var(--border);
    color: var(--text2);
  }
  .lc-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

  /* enter button */
  .portal-enter {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 14px; border-radius: var(--radius);
    font-size: 13px; font-weight: 600;
    cursor: pointer; border: none;
    font-family: 'Inter', sans-serif;
    transition: opacity .15s, transform .12s;
    color: #fff; text-decoration: none;
  }
  .portal-enter:hover { opacity: .9; transform: translateX(2px); }
  .portal-enter svg { width: 15px; height: 15px; }

  /* â”€â”€ STAT BAR â”€â”€ */
  .stat-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px; margin-bottom: 2rem;
  }
  .sb-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.1rem;
    display: flex; align-items: center; gap: 10px;
    box-shadow: var(--shadow);
  }
  .sb-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .sb-icon svg { width: 17px; height: 17px; stroke-width: 1.8; }
  .sb-label { font-size: 11px; color: var(--text3); margin-bottom: 1px; }
  .sb-val { font-size: 19px; font-weight: 600; font-family: 'Space Grotesk', sans-serif; }

  /* â”€â”€ BOTTOM GRID â”€â”€ */
  .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 1.5rem; }
  @media(max-width:600px){ .bottom-grid { grid-template-columns: 1fr; } }

  .card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden;
    box-shadow: var(--shadow);
  }
  .card-head {
    padding: 12px 16px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
  }
  .card-head-title {
    font-size: 13px; font-weight: 500; color: var(--text);
    display: flex; align-items: center; gap: 7px;
  }
  .card-head-title svg { width: 15px; height: 15px; stroke-width: 2; flex-shrink: 0; }
  .card-body { padding: 14px 16px; }
  .badge { font-size: 10px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }
  .badge-blue { background: var(--blue-light); color: var(--blue); }
  .badge-green { background: var(--green-light); color: var(--green); }

  /* layer list */
  .layer-list { display: flex; flex-direction: column; gap: 5px; }
  .layer-item {
    display: flex; align-items: center; gap: 9px;
    padding: 8px 10px; border-radius: 8px;
    background: var(--surface2);
  }
  .l-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .l-name { font-size: 13px; color: var(--text); flex: 1; }
  .l-folder { font-size: 10px; font-weight: 600; letter-spacing: .03em; padding: 2px 7px; border-radius: 10px; }

  /* activity */
  .act-list { display: flex; flex-direction: column; }
  .act-item { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); }
  .act-item:last-child { border-bottom: none; }
  .act-ic { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
  .act-ic svg { width: 14px; height: 14px; stroke-width: 2; }
  .act-title { font-size: 13px; color: var(--text); line-height: 1.4; }
  .act-meta { font-size: 11px; color: var(--text3); margin-top: 1px; }

  /* account card */
  .account-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden;
    box-shadow: var(--shadow); margin-bottom: 1.5rem;
  }
  .ac-header {
    background: var(--navy); padding: 1.1rem 1.4rem;
    display: flex; align-items: center; gap: 12px;
  }
  .ac-header-ic { width: 36px; height: 36px; border-radius: 9px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; }
  .ac-header-ic svg { width: 17px; height: 17px; stroke: rgba(255,255,255,0.8); stroke-width: 1.8; }
  .ac-header-title { font-size: 14px; font-weight: 500; color: #fff; }
  .ac-header-sub { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 1px; }
  .ac-body { padding: 12px 14px; display: flex; flex-direction: column; gap: 8px; }
  .ac-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 9px; background: var(--surface2); }
  .av { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; flex-shrink: 0; }
  .ac-name { font-size: 13px; font-weight: 500; color: var(--text); }
  .ac-pass { font-size: 11px; color: var(--text3); font-family: 'Space Grotesk', monospace; margin-top: 1px; }
  .ac-role { font-size: 10px; font-weight: 600; padding: 3px 9px; border-radius: 20px; margin-left: auto; }
  .ac-foot { padding: 10px 14px; border-top: 1px solid var(--border); }
  .btn-login-full {
    width: 100%; background: var(--blue); color: #fff;
    border: none; border-radius: 9px; padding: 10px;
    font-size: 13px; font-weight: 500; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    font-family: 'Inter', sans-serif; transition: background .15s;
    text-decoration: none;
  }
  .btn-login-full:hover { background: var(--navy2); }
  .btn-login-full svg { width: 15px; height: 15px; stroke: white; stroke-width: 2; }

  /* footer */
  .footer {
    background: var(--navy); padding: 1.2rem 2rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    border-top: 1px solid rgba(255,255,255,0.05);
  }
  .footer p { font-size: 11px; color: rgba(255,255,255,0.35); }
  .footer-links { display: flex; gap: 14px; flex-wrap: wrap; }
  .footer-links a {
    font-size: 11px; color: rgba(255,255,255,0.45);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    transition: color .12s;
  }
  .footer-links a:hover { color: rgba(255,255,255,0.8); }
  .footer-links a svg { width: 12px; height: 12px; stroke-width: 2; }

  /* color helpers */
  .c-blue-bg { background: var(--blue-light); }
  .c-blue-icon { stroke: var(--blue); }
  .c-green-bg { background: var(--green-light); }
  .c-green-icon { stroke: var(--green); }
  .c-amber-bg { background: var(--amber-light); }
  .c-amber-icon { stroke: var(--amber); }
  .c-red-bg { background: var(--red-light); }
  .c-red-icon { stroke: var(--red); }
  .c-purple-bg { background: var(--purple-light); }
  .c-purple-icon { stroke: var(--purple); }
  .c-blue-text { color: var(--blue); }
  .c-green-text { color: var(--green); }
  .c-amber-text { color: var(--amber); }
  .c-red-text { color: var(--red); }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a class="brand" href="#">
    <div class="brand-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
    </div>
    <div>
      <div class="brand-name">WebGIS Pontianak</div>
      <div class="brand-sub">Portal Sistem Informasi Geografis</div>
    </div>
  </a>
  <div class="nav-right">
    <span class="pill-online">Sistem Online</span>
  </div>
</nav>

<!-- HERO -->
<div class="hero">
  <div class="hero-grid-bg"></div>
  <div class="hero-content">
    <div class="hero-eyebrow"><span></span>GIS Kota Pontianak<span></span></div>
    <h1>Pilih <em>Modul Peta</em><br>yang Ingin Diakses</h1>
    <p class="hero-desc">Sistem WebGIS terpadu Kota Pontianak terbagi menjadi dua modul: infrastruktur fisik dan data sosial warga. Pilih modul di bawah untuk masuk.</p>
    <div class="hero-chips">
      <span class="hero-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        Jalan &amp; Parsil
      </span>
      <span class="hero-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16"/><path d="M15 10h2a2 2 0 012 2v6a1 1 0 001 1v0a1 1 0 001-1v-9l-3-5"/><line x1="3" y1="22" x2="15" y2="22"/></svg>
        SPBU
      </span>
      <span class="hero-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Data Warga
      </span>
      <span class="hero-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        Pengaduan
      </span>
    </div>
  </div>
</div>

<!-- MAIN -->
<div class="main">

  <div class="section-label">Pilih Modul Peta</div>

  <!-- PORTAL CARDS -->
  <div class="portal-grid">

    <!-- FOLDER 01: Infrastruktur -->
    <a href="01/login.php" class="portal-card">
      <!-- MAP PREVIEW -->
      <div class="portal-map" style="background:#1C3A5E;">
        <svg viewBox="0 0 480 210" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <!-- BG water -->
          <rect width="480" height="210" fill="#1C3A5E"/>
          <!-- grid -->
          <rect width="480" height="210" fill="none"
            style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg,rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 30px 30px;"/>
          <!-- subtle terrain -->
          <path d="M0 130 Q80 112 160 125 Q240 138 320 122 Q400 106 480 118" stroke="rgba(255,255,255,0.07)" stroke-width="30" fill="none"/>
          <!-- Jalan Nasional merah -->
          <line x1="30" y1="98" x2="450" y2="94" stroke="#ef4444" stroke-width="4" opacity="0.85"/>
          <!-- Jalan Provinsi kuning -->
          <line x1="180" y1="20" x2="188" y2="190" stroke="#f59e0b" stroke-width="3" opacity="0.8"/>
          <line x1="310" y1="20" x2="304" y2="190" stroke="#f59e0b" stroke-width="3" opacity="0.75"/>
          <!-- Jalan Kabupaten biru -->
          <line x1="30" y1="155" x2="450" y2="150" stroke="#3b82f6" stroke-width="2.5" opacity="0.6"/>
          <line x1="30" y1="52" x2="450" y2="50" stroke="#3b82f6" stroke-width="2" opacity="0.5"/>
          <!-- Parsil polygon 1 -->
          <polygon points="60,35 130,32 135,75 62,78" fill="rgba(139,92,246,0.35)" stroke="#8b5cf6" stroke-width="1.5"/>
          <!-- Parsil polygon 2 -->
          <polygon points="350,120 420,116 425,168 352,172" fill="rgba(139,92,246,0.35)" stroke="#8b5cf6" stroke-width="1.5"/>
          <!-- Parsil polygon 3 small -->
          <polygon points="200,38 255,36 258,70 202,73" fill="rgba(139,92,246,0.25)" stroke="#8b5cf6" stroke-width="1.2"/>
          <!-- SPBU 24 jam hijau -->
          <rect x="96" y="82" width="13" height="17" rx="2" fill="#10b981" opacity="0.9"/>
          <rect x="98.5" y="84" width="8" height="4" rx="1" fill="rgba(255,255,255,0.5)"/>
          <rect x="101" y="93" width="3" height="6" fill="rgba(255,255,255,0.4)"/>

          <rect x="258" y="77" width="13" height="17" rx="2" fill="#10b981" opacity="0.9"/>
          <rect x="260.5" y="79" width="8" height="4" rx="1" fill="rgba(255,255,255,0.5)"/>
          <rect x="263" y="88" width="3" height="6" fill="rgba(255,255,255,0.4)"/>

          <!-- SPBU reguler merah -->
          <rect x="155" y="136" width="13" height="17" rx="2" fill="#ef4444" opacity="0.9"/>
          <rect x="157.5" y="138" width="8" height="4" rx="1" fill="rgba(255,255,255,0.5)"/>
          <rect x="160" y="147" width="3" height="6" fill="rgba(255,255,255,0.4)"/>

          <rect x="375" y="78" width="13" height="17" rx="2" fill="#ef4444" opacity="0.85"/>
          <rect x="377.5" y="80" width="8" height="4" rx="1" fill="rgba(255,255,255,0.5)"/>
          <rect x="380" y="89" width="3" height="6" fill="rgba(255,255,255,0.4)"/>

          <!-- Legend box -->
          <rect x="8" y="145" width="130" height="58" rx="6" fill="rgba(12,45,72,0.85)" stroke="rgba(255,255,255,0.12)" stroke-width="0.8"/>
          <text x="18" y="161" font-size="9" fill="rgba(255,255,255,0.5)" font-family="Inter,sans-serif" font-weight="600" letter-spacing="0.04em">LAPISAN AKTIF</text>
          <line x1="18" y1="164" x2="130" y2="164" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/>
          <line x1="18" y1="172" x2="30" y2="172" stroke="#ef4444" stroke-width="3"/><text x="34" y="175.5" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">Jalan Nasional</text>
          <rect x="18" y="179" width="9" height="11" rx="1.5" fill="#10b981"/><text x="31" y="188" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">SPBU 24 Jam</text>
          <polygon points="18,193 30,192 30.5,196 18.5,197" fill="rgba(139,92,246,0.7)"/><text x="34" y="198" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">Parsil Kavling</text>

          <!-- Folder label pill -->
          <rect x="350" y="8" width="118" height="22" rx="11" fill="rgba(59,130,246,0.25)" stroke="rgba(99,163,245,0.4)" stroke-width="0.8"/>
          <text x="409" y="22" font-size="10" fill="#93c5fd" font-family="Inter,sans-serif" font-weight="600" text-anchor="middle">Folder 01 â€” Infrastruktur</text>
        </svg>
      </div>

      <div class="portal-body">
        <div class="portal-badge" style="background:rgba(59,130,246,0.12);color:#1A6FA8;border:1px solid rgba(59,130,246,0.2);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
          Peta Infrastruktur
        </div>
        <div class="portal-title">Jaringan Jalan, SPBU &amp; Parsil</div>
        <div class="portal-desc">Kelola dan pantau infrastruktur fisik kota: ruas jalan berdasarkan status, titik SPBU 24 jam &amp; reguler, serta bidang parsil kavling seluruh wilayah Pontianak.</div>
        <div class="layer-chips">
          <span class="lc"><span class="lc-dot" style="background:#ef4444;"></span>Jalan Nasional</span>
          <span class="lc"><span class="lc-dot" style="background:#f59e0b;"></span>Jalan Provinsi</span>
          <span class="lc"><span class="lc-dot" style="background:#10b981;"></span>SPBU 24 Jam</span>
          <span class="lc"><span class="lc-dot" style="background:#ef4444;"></span>SPBU Reguler</span>
          <span class="lc"><span class="lc-dot" style="background:#8b5cf6;"></span>Parsil Kavling</span>
        </div>
        <span class="portal-enter" style="background:#1A6FA8;">
          Masuk ke Peta Infrastruktur
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
      </div>
    </a>

    <!-- FOLDER 02: Sosial & Warga -->
    <a href="02/login.php" class="portal-card">
      <!-- MAP PREVIEW -->
      <div class="portal-map" style="background:#1A3A2A;">
        <svg viewBox="0 0 480 210" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <!-- BG -->
          <rect width="480" height="210" fill="#1A3A2A"/>
          <!-- subtle terrain -->
          <path d="M0 120 Q80 105 160 118 Q240 130 320 115 Q400 100 480 112" stroke="rgba(255,255,255,0.06)" stroke-width="28" fill="none"/>
          <!-- Radius circles rumah ibadah -->
          <circle cx="200" cy="95" r="58" fill="rgba(16,185,129,0.08)" stroke="#10b981" stroke-width="1" stroke-dasharray="4 3"/>
          <circle cx="340" cy="115" r="48" fill="rgba(16,185,129,0.08)" stroke="#10b981" stroke-width="1" stroke-dasharray="4 3"/>
          <!-- Masjid pins -->
          <!-- pin 1 -->
          <path d="M200 66C195.58 66 192 69.58 192 74c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#10b981" stroke="#fff" stroke-width="1.2"/>
          <circle cx="200" cy="74" r="2.5" fill="white"/>
          <!-- pin 2 -->
          <path d="M340 85C335.58 85 332 88.58 332 93c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#10b981" stroke="#fff" stroke-width="1.2"/>
          <circle cx="340" cy="93" r="2.5" fill="white"/>
          <!-- Warga miskin dalam radius - ungu -->
          <path d="M185 90C180.58 90 177 93.58 177 98c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#6366f1" stroke="#fff" stroke-width="1.2"/>
          <circle cx="185" cy="98" r="2.5" fill="white"/>
          <path d="M215 105C210.58 105 207 108.58 207 113c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#6366f1" stroke="#fff" stroke-width="1.2"/>
          <circle cx="215" cy="113" r="2.5" fill="white"/>
          <path d="M323 118C318.58 118 315 121.58 315 126c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#6366f1" stroke="#fff" stroke-width="1.2"/>
          <circle cx="323" cy="126" r="2.5" fill="white"/>
          <!-- Warga miskin luar radius - pink -->
          <path d="M90 80C85.58 80 82 83.58 82 88c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#db2777" stroke="#fff" stroke-width="1.2"/>
          <circle cx="90" cy="88" r="2.5" fill="white"/>
          <path d="M420 130C415.58 130 412 133.58 412 138c0 5.06 8 12.5 8 12.5s8-7.44 8-12.5c0-4.42-3.58-8-8-8z" fill="#db2777" stroke="#fff" stroke-width="1.2"/>
          <circle cx="420" cy="138" r="2.5" fill="white"/>
          <!-- Laporan merah segitiga -->
          <polygon points="130,140 138,155 122,155" fill="#e11d48" stroke="#fff" stroke-width="1.2" opacity="0.9"/>
          <polygon points="280,158 288,173 272,173" fill="#e11d48" stroke="#fff" stroke-width="1.2" opacity="0.9"/>
          <polygon points="390,68 398,83 382,83" fill="#e11d48" stroke="#fff" stroke-width="1.2" opacity="0.85"/>

          <!-- Legend box -->
          <rect x="8" y="138" width="140" height="65" rx="6" fill="rgba(10,36,24,0.85)" stroke="rgba(255,255,255,0.12)" stroke-width="0.8"/>
          <text x="18" y="154" font-size="9" fill="rgba(255,255,255,0.5)" font-family="Inter,sans-serif" font-weight="600" letter-spacing="0.04em">LAPISAN AKTIF</text>
          <line x1="18" y1="157" x2="140" y2="157" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/>
          <circle cx="23" cy="166" r="4" fill="#10b981"/><text x="31" y="169" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">Rumah Ibadah</text>
          <circle cx="23" cy="179" r="4" fill="#6366f1"/><text x="31" y="182" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">Warga (dalam radius)</text>
          <polygon points="18,190 26,198 10,198" fill="#e11d48"/><text x="31" y="197" font-size="9" fill="rgba(255,255,255,0.7)" font-family="Inter,sans-serif">Laporan Pengaduan</text>

          <!-- Folder label pill -->
          <rect x="340" y="8" width="130" height="22" rx="11" fill="rgba(16,185,129,0.2)" stroke="rgba(52,211,153,0.35)" stroke-width="0.8"/>
          <text x="405" y="22" font-size="10" fill="#6ee7b7" font-family="Inter,sans-serif" font-weight="600" text-anchor="middle">Folder 02 â€” Sosial &amp; Warga</text>
        </svg>
      </div>

      <div class="portal-body">
        <div class="portal-badge" style="background:rgba(16,185,129,0.12);color:#1D7A5F;border:1px solid rgba(16,185,129,0.2);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          Peta Sosial &amp; Warga
        </div>
        <div class="portal-title">Data Warga, Ibadah &amp; Laporan</div>
        <div class="portal-desc">Pantau sebaran warga miskin &amp; analisis keterjangkauan radius rumah ibadah, serta kelola laporan pengaduan masyarakat berbasis lokasi geografis.</div>
        <div class="layer-chips">
          <span class="lc"><span class="lc-dot" style="background:#10b981;"></span>Rumah Ibadah</span>
          <span class="lc"><span class="lc-dot" style="background:#6366f1;"></span>Warga (dalam radius)</span>
          <span class="lc"><span class="lc-dot" style="background:#db2777;"></span>Warga (luar radius)</span>
          <span class="lc"><span class="lc-dot" style="background:#e11d48;"></span>Pengaduan</span>
        </div>
        <span class="portal-enter" style="background:#1D7A5F;">
          Masuk ke Peta Sosial
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
      </div>
    </a>

  </div>

  <!-- STAT BAR -->
  <div class="section-label">Ringkasan Data Keseluruhan</div>
  <div class="stat-bar">
    <div class="sb-card">
      <div class="sb-icon c-blue-bg">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" class="c-blue-icon"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
      </div>
      <div>
        <div class="sb-label">Ruas Jalan</div>
        <div class="sb-val c-blue-text">â€”</div>
      </div>
    </div>
    <div class="sb-card">
      <div class="sb-icon c-purple-bg">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" class="c-purple-icon"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      </div>
      <div>
        <div class="sb-label">Bidang Parsil</div>
        <div class="sb-val" style="color:var(--purple);">â€”</div>
      </div>
    </div>
    <div class="sb-card">
      <div class="sb-icon c-amber-bg">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" class="c-amber-icon"><path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16"/><path d="M15 10h2a2 2 0 012 2v6a1 1 0 001 1v0a1 1 0 001-1v-9l-3-5"/><line x1="3" y1="22" x2="15" y2="22"/></svg>
      </div>
      <div>
        <div class="sb-label">Titik SPBU</div>
        <div class="sb-val c-amber-text">â€”</div>
      </div>
    </div>
    <div class="sb-card">
      <div class="sb-icon c-green-bg">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" class="c-green-icon"><path d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072"/><circle cx="12" cy="12" r="1"/></svg>
      </div>
      <div>
        <div class="sb-label">Rumah Ibadah</div>
        <div class="sb-val c-green-text">â€”</div>
      </div>
    </div>
    <div class="sb-card">
      <div class="sb-icon" style="background:#EEF0FE;">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" style="stroke:#5B5BD6;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div>
        <div class="sb-label">Data Warga</div>
        <div class="sb-val" style="color:#5B5BD6;">â€”</div>
      </div>
    </div>
    <div class="sb-card">
      <div class="sb-icon c-red-bg">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" class="c-red-icon"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      </div>
      <div>
        <div class="sb-label">Pengaduan</div>
        <div class="sb-val c-red-text">â€”</div>
      </div>
    </div>
  </div>

  <!-- BOTTOM GRID -->
  <div class="bottom-grid">
    <!-- LAPISAN -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="#1A6FA8"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          Semua Lapisan Data
        </div>
      </div>
      <div class="card-body">
        <div class="layer-list">
          <div class="layer-item">
            <div class="l-dot" style="background:#ef4444;"></div>
            <span class="l-name">Jalan Nasional</span>
            <span class="l-folder" style="background:#FEF2F2;color:#DC2626;">Folder 01</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#f59e0b;"></div>
            <span class="l-name">Jalan Provinsi</span>
            <span class="l-folder" style="background:#FEF2F2;color:#DC2626;">Folder 01</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#10b981;"></div>
            <span class="l-name">SPBU 24 Jam</span>
            <span class="l-folder" style="background:#FEF2F2;color:#DC2626;">Folder 01</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#8b5cf6;"></div>
            <span class="l-name">Parsil Kavling</span>
            <span class="l-folder" style="background:#FEF2F2;color:#DC2626;">Folder 01</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#10b981;"></div>
            <span class="l-name">Rumah Ibadah</span>
            <span class="l-folder" style="background:#E6F5F0;color:#1D7A5F;">Folder 02</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#6366f1;"></div>
            <span class="l-name">Data Warga Miskin</span>
            <span class="l-folder" style="background:#E6F5F0;color:#1D7A5F;">Folder 02</span>
          </div>
          <div class="layer-item">
            <div class="l-dot" style="background:#e11d48;"></div>
            <span class="l-name">Pengaduan Warga</span>
            <span class="l-folder" style="background:#E6F5F0;color:#1D7A5F;">Folder 02</span>
          </div>
        </div>
      </div>
    </div>

    <!-- AKTIVITAS -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="#1D7A5F"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          Aktivitas Sistem
        </div>
        <span class="badge badge-green" id="waktu-update">â€”</span>
      </div>
      <div class="card-body" style="padding:8px 16px">
        <div class="act-list">
          <div class="act-item">
            <div class="act-ic c-blue-bg">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" class="c-blue-icon"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
            </div>
            <div>
              <div class="act-title">Data jalan diperbarui</div>
              <div class="act-meta">Folder 01 Â· edit_jalan.php</div>
            </div>
          </div>
          <div class="act-item">
            <div class="act-ic c-purple-bg">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" class="c-purple-icon"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </div>
            <div>
              <div class="act-title">Parsil baru disimpan</div>
              <div class="act-meta">Folder 01 Â· simpan_parsil.php</div>
            </div>
          </div>
          <div class="act-item">
            <div class="act-ic c-green-bg">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" class="c-green-icon"><path d="M18.364 5.636a9 9 0 010 12.728"/><circle cx="12" cy="12" r="1"/></svg>
            </div>
            <div>
              <div class="act-title">Radius ibadah diperbarui</div>
              <div class="act-meta">Folder 02 Â· update_radius.php</div>
            </div>
          </div>
          <div class="act-item">
            <div class="act-ic c-red-bg">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" class="c-red-icon"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            </div>
            <div>
              <div class="act-title">Laporan pengaduan masuk</div>
              <div class="act-meta">Folder 02 Â· simpan_laporan_cepat.php</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ACCOUNT -->
  <div class="account-card">
    <div class="ac-header">
      <div class="ac-header-ic">
        <svg viewBox="0 0 24 24" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="rgba(255,255,255,0.8)" stroke-width="1.8"/></svg>
      </div>
      <div>
        <div class="ac-header-title">Akses Petugas</div>
        <div class="ac-header-sub">Login berlaku untuk kedua modul peta (Folder 01 &amp; 02)</div>
      </div>
    </div>
    <div class="ac-body">
      <div class="ac-item">
        <div class="av" style="background:#E6F1FB;color:#1A6FA8;">AD</div>
        <div>
          <div class="ac-name">admin</div>
          <div class="ac-pass">admin123</div>
        </div>
        <span class="ac-role" style="background:#E6F1FB;color:#1A6FA8;">Administrator</span>
      </div>
      <div class="ac-item">
        <div class="av" style="background:#FEF3E2;color:#B86800;">WK</div>
        <div>
          <div class="ac-name">walikota</div>
          <div class="ac-pass">walikota123</div>
        </div>
        <span class="ac-role" style="background:#FEF3E2;color:#B86800;">Walikota</span>
      </div>
    </div>
    <div class="ac-foot" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
      <a href="01/login.php" class="btn-login-full" style="background:#1A6FA8;">
        <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Login Peta Infrastruktur
      </a>
      <a href="02/login.php" class="btn-login-full" style="background:#1D7A5F;">
        <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Login Peta Sosial
      </a>
    </div>
  </div>

</div>

<!-- FOOTER -->
<footer class="footer">
  <p>Â© 2026 WebGIS Pontianak Â· Pemerintah Kota Pontianak</p>
  <div class="footer-links">
    <a href="01/login.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
      Peta Infrastruktur
    </a>
    <a href="02/login.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Peta Sosial
    </a>
  </div>
</footer>

<script>
const now = new Date();
const pad = n => n.toString().padStart(2,'0');
document.getElementById('waktu-update').textContent =
  'Update ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ' WIB';
</script>
</body>
</html>
