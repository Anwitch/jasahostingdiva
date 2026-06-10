<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
$role      = $_SESSION['user_role'] ?? 'masyarakat';
$user_nama = $_SESSION['user_nama'] ?? '';

$words    = explode(' ', trim($user_nama));
$initials = '';
foreach (array_slice($words, 0, 2) as $w) $initials .= strtoupper(substr($w, 0, 1));
if (!$initials) $initials = 'U';
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebGIS Pontianak</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ===== RESET ===== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; overflow: hidden; }

/* ===== THEME VARIABLES ===== */
:root {
    --nav-bg: #0f172a;
    --nav-border: rgba(255,255,255,0.07);
    --nav-text: #94a3b8;
    --nav-text-hover: #f1f5f9;
    --nav-brand: #ffffff;
    --nav-surface: rgba(255,255,255,0.06);
    --nav-surface-hover: rgba(255,255,255,0.10);
    --dm-btn-bg: rgba(255,255,255,0.08);
    --dm-btn-hover: rgba(255,255,255,0.13);
    --dm-icon-color: #fbbf24;
}
[data-theme="light"] {
    --nav-bg: #ffffff;
    --nav-border: rgba(0,0,0,0.08);
    --nav-text: #64748b;
    --nav-text-hover: #0f172a;
    --nav-brand: #0f172a;
    --nav-surface: rgba(0,0,0,0.04);
    --nav-surface-hover: rgba(0,0,0,0.07);
    --dm-btn-bg: rgba(0,0,0,0.05);
    --dm-btn-hover: rgba(0,0,0,0.09);
    --dm-icon-color: #7c3aed;
}

/* ===== TOPBAR ===== */
#topnav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 10000;
    background: var(--nav-bg);
    border-bottom: 1px solid var(--nav-border);
    height: 52px; padding: 0 20px;
    display: flex; align-items: center; justify-content: space-between;
    transition: background 0.3s, border-color 0.3s;
}
.nav-left  { display: flex; align-items: center; gap: 14px; }
.nav-right { display: flex; align-items: center; gap: 3px; }

.nav-brand {
    display: flex; align-items: center; gap: 9px;
    font-size: 14px; font-weight: 800;
    color: var(--nav-brand); text-decoration: none; white-space: nowrap;
}
.brand-icon {
    width: 30px; height: 30px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 8px; display: flex; align-items: center;
    justify-content: center; font-size: 13px; color: white; flex-shrink: 0;
}
.nav-divider { width: 1px; height: 20px; background: var(--nav-border); margin: 0 7px; flex-shrink: 0; }
.nav-role {
    padding: 3px 10px; border-radius: 20px;
    font-size: 10px; font-weight: 800; letter-spacing: 0.8px;
    text-transform: uppercase; flex-shrink: 0;
}
.nav-role.walikota   { background: rgba(251,191,36,0.15); color: #f59e0b; border: 1px solid rgba(251,191,36,0.25); }
.nav-role.admin      { background: rgba(59,130,246,0.15);  color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
.nav-role.masyarakat { background: rgba(34,197,94,0.15);   color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
[data-theme="light"] .nav-role.walikota   { background:rgba(217,119,6,0.10);  color:#d97706; border-color:rgba(217,119,6,0.2); }
[data-theme="light"] .nav-role.admin      { background:rgba(37,99,235,0.08);  color:#2563eb; border-color:rgba(37,99,235,0.2); }
[data-theme="light"] .nav-role.masyarakat { background:rgba(22,163,74,0.08);  color:#16a34a; border-color:rgba(22,163,74,0.2); }

.nav-btn {
    display: flex; align-items: center; gap: 6px; padding: 6px 11px; border-radius: 8px;
    font-size: 12px; font-weight: 600; color: var(--nav-text); background: transparent;
    border: none; cursor: pointer; text-decoration: none;
    transition: background 0.15s, color 0.15s; white-space: nowrap;
}
.nav-btn i { font-size: 13px; }
.nav-btn:hover { background: var(--nav-surface-hover); color: var(--nav-text-hover); }
.nav-btn.laporan { color: #f59e0b; }
.nav-btn.laporan:hover { background: rgba(251,191,36,0.12); color: #fbbf24; }
.nav-btn.bantuan { color: #4ade80; }
.nav-btn.bantuan:hover { background: rgba(34,197,94,0.12); }
[data-theme="light"] .nav-btn.laporan { color: #d97706; }
[data-theme="light"] .nav-btn.bantuan { color: #16a34a; }

.notif-wrap { position: relative; display: inline-flex; }
.notif-dot {
    position: absolute; top: 1px; right: 1px; width: 7px; height: 7px;
    border-radius: 50%; background: #f59e0b;
    box-shadow: 0 0 0 2px var(--nav-bg); animation: ping-dot 2s infinite;
    pointer-events: none;
}
@keyframes ping-dot { 0%,100%{opacity:1} 50%{opacity:0.35} }

.user-chip {
    display: flex; align-items: center; gap: 7px;
    padding: 4px 10px 4px 4px;
    background: var(--nav-surface); border: 1px solid var(--nav-border);
    border-radius: 30px; cursor: default; flex-shrink: 0;
}
.user-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 800; color: white; flex-shrink: 0;
}
.user-name { font-size: 12px; font-weight: 600; color: var(--nav-text-hover); white-space: nowrap; }

.nav-link-logout {
    display: flex; align-items: center; gap: 6px; padding: 6px 11px;
    border-radius: 8px; font-size: 12px; font-weight: 600; color: #f87171;
    text-decoration: none; transition: background 0.15s; white-space: nowrap;
}
.nav-link-logout:hover { background: rgba(248,113,113,0.12); }
[data-theme="light"] .nav-link-logout { color: #dc2626; }
[data-theme="light"] .nav-link-logout:hover { background: rgba(220,38,38,0.08); }

.dm-btn {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--dm-btn-bg); border: 1px solid var(--nav-border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; margin: 0 4px;
    transition: background 0.2s, transform 0.15s;
}
.dm-btn:hover  { background: var(--dm-btn-hover); }
.dm-btn:active { transform: scale(0.90); }
.dm-btn i { font-size: 14px; color: var(--dm-icon-color); transition: color 0.3s; }

/* ===== MAP ===== */
#map {
    height: calc(100vh - 52px); width: 100%;
    margin-top: 52px; position: relative; z-index: 1;
}
.leaflet-control-layers { display: none !important; }
.leaflet-popup { pointer-events: auto !important; }

/* ===== CUSTOM MARKER ===== */
.custom-div-icon { background: transparent; border: none; }
.pin-container {
    position: relative; width: 32px; height: 42px;
    filter: drop-shadow(0px 4px 6px rgba(15,23,42,0.35));
    transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
}
.pin-container:hover { transform: scale(1.2) translateY(-4px); z-index: 9999 !important; }
.pin-icon-wrapper {
    position: absolute; top: 0; left: 0; width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 13px; z-index: 2;
}

/* ===== POPUP ===== */
.leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); padding: 4px; }
.leaflet-popup-content { margin: 12px 14px; min-width: 260px; }
.form-title {
    display: flex; align-items: center; gap: 8px;
    font-weight: 700; font-size: 14px; color: #0f172a;
    margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;
}
.form-title i { color: #3b82f6; font-size: 15px; }
.info-box {
    background: #f8fafc; padding: 12px; border-radius: 10px;
    font-size: 12px; line-height: 1.8; color: #475569; border: 1px solid #edf2f7;
}
.info-box strong { color: #0f172a; font-weight: 600; }
.badge-zone {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; margin-top: 6px;
}
.badge-in  { background: #ecfdf5; color: #059669; }
.badge-out { background: #fdf2f8; color: #db2777; }
.action-group { display: flex; gap: 6px; margin-top: 12px; flex-wrap: wrap; }
.btn-action {
    text-align: center; padding: 8px; border-radius: 8px; font-size: 11px;
    text-decoration: none; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    transition: all 0.2s; width: 100%; flex: 1; min-width: 65px; border: none;
}
.btn-edit   { background: #eff6ff; color: #2563eb; }
.btn-edit:hover   { background: #dbeafe; }
.btn-delete { background: #fef2f2; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; }
.btn-submit {
    width: 100%; padding: 10px; background: #0f172a; color: white;
    border: none; border-radius: 8px; cursor: pointer; margin-top: 8px;
    font-size: 12px; font-weight: 600;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.choice-group {
    display: flex; gap: 4px; margin-bottom: 12px;
    background: #f1f5f9; padding: 3px; border-radius: 8px;
}
.btn-choice {
    flex: 1; padding: 8px 4px; cursor: pointer; font-size: 11px;
    border: none; border-radius: 6px; background: transparent;
    color: #64748b; font-weight: 600;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.btn-choice.active { background: white; color: #0f172a; box-shadow: 0 2px 4px rgba(0,0,0,0.06); }
#form-container input,
#form-container select,
#form-container textarea {
    width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px;
    margin-bottom: 8px; font-size: 12px; font-family: inherit; background: #fff; color: #0f172a;
}
#form-container label { font-size: 11px; color: #475569; font-weight: 600; display: block; margin-bottom: 4px; }

/* ===== LAYER PANEL ===== */
#layer-panel {
    position: fixed; top: 72px; right: 20px; z-index: 9999; width: 308px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
#layer-toggle-btn {
    width: 100%; background: #0f172a; color: #f8fafc; border: none;
    border-radius: 14px; padding: 13px 18px; font-size: 13px; font-weight: 700;
    cursor: pointer; display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 8px 24px -4px rgba(15,23,42,0.35);
    transition: box-shadow 0.25s, transform 0.2s; user-select: none;
}
#layer-toggle-btn:hover  { box-shadow: 0 12px 30px -4px rgba(15,23,42,0.45); transform: translateY(-1px); }
#layer-toggle-btn:active { transform: scale(0.98); }
.ltb-left { display: flex; align-items: center; gap: 10px; }
.ltb-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.25); animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%,100% { box-shadow: 0 0 0 3px rgba(34,197,94,0.25); }
    50%      { box-shadow: 0 0 0 6px rgba(34,197,94,0.10); }
}
.ltb-chevron { font-size: 11px; opacity: 0.5; transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
#layer-panel.open .ltb-chevron { transform: rotate(180deg); }
.ltb-count {
    background: #3b82f6; color: white; font-size: 10px; font-weight: 800;
    width: 20px; height: 20px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

#layer-body {
    margin-top: 10px; background: #fff; border: 1px solid #e8edf5;
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 20px 50px -10px rgba(15,23,42,0.18);
    display: none;
}
#layer-body.open {
    display: block;
    animation: panelSlide 0.28s cubic-bezier(0.4,0,0.2,1);
}
@keyframes panelSlide {
    from { opacity: 0; transform: translateY(-12px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.lp-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 16px 18px 14px; display: flex; align-items: flex-end; justify-content: space-between;
}
.lp-header-title { font-size: 11px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.5); }
.lp-header-sub   { font-size: 14px; font-weight: 700; color: #fff; margin-top: 2px; }
.lp-header-badge {
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 700;
    padding: 4px 9px; border-radius: 20px;
}

.lp-scroll {
    max-height: 430px; overflow-y: auto; padding: 14px 14px 10px;
    scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
}
.lp-scroll::-webkit-scrollbar       { width: 4px; }
.lp-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.lp-section {
    font-size: 10px; font-weight: 800; letter-spacing: 1.8px; text-transform: uppercase;
    color: #94a3b8; margin: 14px 4px 8px;
    display: flex; align-items: center; gap: 8px;
}
.lp-section::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }
.lp-section:first-child { margin-top: 2px; }

/* basemap cards */
.bm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 4px; }
.bm-card {
    background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px;
    padding: 10px 12px; cursor: pointer; transition: all 0.2s;
    display: flex; flex-direction: column; align-items: center; gap: 6px; position: relative;
}
.bm-card:hover  { border-color: #93c5fd; background: #eff6ff; }
.bm-card.active { border-color: #3b82f6; background: #eff6ff; }
.bm-card.active::after {
    content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; top: 6px; right: 8px; font-size: 9px; color: #3b82f6;
}
.bm-thumb     { width: 100%; height: 44px; border-radius: 7px; overflow: hidden; }
.bm-thumb-osm { background: linear-gradient(135deg, #a8d5a2 0%, #f7f3e3 40%, #c8e6f5 100%); }
.bm-thumb-sat { background: linear-gradient(135deg, #2d4a1e 0%, #3d6b2d 40%, #1a3a0a 100%); }
.bm-thumb-osm-inner { width: 100%; height: 100%; position: relative; overflow: hidden; }
.bm-road { position: absolute; background: #fff; border-radius: 2px; opacity: 0.8; }
.bm-thumb-sat-inner {
    width: 100%; height: 100%;
    background:
        repeating-linear-gradient(0deg,  rgba(0,0,0,0.08) 0px, rgba(0,0,0,0.08) 1px, transparent 1px, transparent 12px),
        repeating-linear-gradient(90deg, rgba(0,0,0,0.08) 0px, rgba(0,0,0,0.08) 1px, transparent 1px, transparent 14px);
}
.bm-label { font-size: 11px; font-weight: 700; color: #334155; }

/* Quick Add buttons */
.qa-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 7px; margin-bottom: 4px; }
.qa-btn {
    border-radius: 11px; padding: 10px 6px 8px; cursor: pointer; border: 1.5px solid transparent;
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif;
}
.qa-btn:hover { transform: translateY(-2px); }
.qa-btn i     { font-size: 15px; }
.qa-btn span  { font-size: 10px; font-weight: 700; }

/* Layer rows */
.lp-row {
    display: flex; align-items: center; gap: 10px; padding: 8px 8px;
    border-radius: 11px; cursor: pointer; transition: background 0.15s, transform 0.15s;
    margin-bottom: 3px; border: 1px solid transparent;
}
.lp-row:hover  { background: #f8fafc; border-color: #e8edf5; }
.lp-row:active { transform: scale(0.99); }
.lp-icon {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0; transition: transform 0.2s;
}
.lp-row:hover .lp-icon { transform: scale(1.1); }
.icon-rumibadah { background: #ecfdf5; color: #10b981; }
.icon-miskin  { background: #ede9fe; color: #8b5cf6; }
.icon-lapor   { background: #fff1f2; color: #e11d48; }

.lp-info  { flex: 1; min-width: 0; }
.lp-name  { font-size: 12.5px; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lp-desc  { font-size: 10.5px; color: #94a3b8; margin-top: 1px; }

/* add btn per row */
.lp-add-btn {
    width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid transparent;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; cursor: pointer; flex-shrink: 0; transition: all 0.15s;
    font-family: inherit;
}

/* toggle switch */
.lp-toggle  { position: relative; width: 36px; height: 20px; flex-shrink: 0; }
.lp-toggle input { opacity: 0; width: 0; height: 0; }
.lp-slider  {
    position: absolute; inset: 0; background: #e2e8f0; border-radius: 20px;
    cursor: pointer; transition: background 0.25s;
}
.lp-slider::before {
    content: ''; position: absolute; width: 14px; height: 14px;
    left: 3px; top: 3px; background: white; border-radius: 50%;
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.lp-toggle input:checked + .lp-slider             { background: #3b82f6; }
.lp-toggle input:checked + .lp-slider::before     { transform: translateX(16px); }

/* footer */
.lp-footer {
    padding: 11px 14px; border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.lp-footer-txt      { font-size: 11px; color: #94a3b8; }
.lp-footer-txt span { color: #3b82f6; font-weight: 700; }
.lp-btn-all {
    font-size: 11px; font-weight: 700; color: #64748b;
    background: #f1f5f9; border: none; border-radius: 7px;
    padding: 5px 10px; cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.lp-btn-all:hover { background: #e2e8f0; color: #0f172a; }

/* ===== DRAWER OVERLAY ===== */
#drawer-overlay {
    position: fixed; inset: 0; z-index: 19998;
    background: rgba(15,23,42,0.45);
    backdrop-filter: blur(3px);
    display: none; animation: fadeIn 0.2s ease;
}
#drawer-overlay.open { display: block; }

/* ===== DRAWER PANEL ===== */
#drawer-panel {
    position: fixed; top: 52px; right: 0; bottom: 0; z-index: 19999;
    width: 390px; background: #fff;
    box-shadow: -20px 0 60px rgba(15,23,42,0.18);
    display: flex; flex-direction: column;
    transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden;
}
#drawer-panel.open { transform: translateX(0); }

.drawer-header {
    padding: 20px 22px 18px; flex-shrink: 0;
    display: flex; align-items: flex-start; justify-content: space-between;
}
.drawer-header-text { flex: 1; }
.drawer-header-eyebrow {
    font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
    color: rgba(255,255,255,0.65); margin-bottom: 3px;
}
.drawer-header-title { font-size: 17px; font-weight: 800; color: #fff; }
.drawer-header-icon { font-size: 26px; color: rgba(255,255,255,0.9); margin-right: 10px; }
.drawer-close-btn {
    width: 34px; height: 34px; border-radius: 10px;
    background: rgba(255,255,255,0.18); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff; flex-shrink: 0; transition: background 0.15s;
}
.drawer-close-btn:hover { background: rgba(255,255,255,0.28); }

.drawer-body {
    flex: 1; overflow-y: auto; padding: 20px 22px 24px;
    scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
}
.drawer-body::-webkit-scrollbar       { width: 4px; }
.drawer-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* Form inside drawer */
.df-group { margin-bottom: 14px; }
.df-label {
    font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 5px; display: block;
}
.df-label .req { color: #ef4444; margin-left: 2px; }
.df-input, .df-select, .df-textarea {
    width: 100%; padding: 9px 11px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 12.5px; font-family: 'Plus Jakarta Sans', sans-serif;
    background: #fff; color: #0f172a; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.df-input:focus, .df-select:focus, .df-textarea:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.df-input.readonly { background: #f8fafc; color: #64748b; font-weight: 600; }
.df-textarea { resize: vertical; line-height: 1.6; }
.df-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.df-hint {
    margin-top: 5px; padding: 9px 11px; border-radius: 8px;
    font-size: 11px; line-height: 1.6;
}
.df-hint-blue   { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

.df-divider { border: none; border-top: 1px solid #f1f5f9; margin: 18px 0; }

.df-submit-btn {
    width: 100%; padding: 13px; border: none; border-radius: 11px;
    font-size: 13px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: opacity 0.2s, transform 0.15s; color: #fff;
    margin-top: 4px;
}
.df-submit-btn:hover   { opacity: 0.9; transform: translateY(-1px); }
.df-submit-btn:active  { transform: scale(0.98); }
.df-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

/* success state */
.drawer-success {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 12px; padding: 40px;
}
.drawer-success-icon {
    width: 64px; height: 64px; border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: white;
}
.drawer-success-title { font-size: 18px; font-weight: 800; color: #0f172a; }
.drawer-success-desc  { font-size: 13px; color: #64748b; }

/* toast */
#toast {
    position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(20px);
    background: #0f172a; color: #fff; padding: 11px 22px; border-radius: 12px;
    font-size: 13px; font-weight: 600; z-index: 99999;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    opacity: 0; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); pointer-events: none;
    display: flex; align-items: center; gap: 8px;
}
#toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

@keyframes fadeIn { from{opacity:0} to{opacity:1} }
</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<div id="topnav">
    <div class="nav-left">
        <a href="#" class="nav-brand">
            <div class="brand-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            WebGIS Pontianak
        </a>
        <span class="nav-role <?= $role ?>"><?= strtoupper($role) ?></span>
    </div>
    <div class="nav-right">
        <?php if (in_array($role, ['admin','walikota'])): ?>
        <div class="notif-wrap">
            <a href="kelola_laporan.php" class="nav-btn laporan">
                <i class="fa-solid fa-bell"></i> Kelola Laporan
            </a>
            <span class="notif-dot"></span>
        </div>
        <a href="histori_bantuan.php" class="nav-btn bantuan">
            <i class="fa-solid fa-hand-holding-heart"></i> Histori Bantuan
        </a>
        <div class="nav-divider"></div>
        <?php endif; ?>

        <div class="user-chip">
            <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
            <span class="user-name"><?= htmlspecialchars($user_nama) ?></span>
        </div>
        <div class="nav-divider"></div>
        <button class="dm-btn" id="dmBtn" onclick="toggleDarkMode()" title="Ganti tema">
            <i class="fa-solid fa-moon" id="dmIcon"></i>
        </button>
        <a href="logout.php" class="nav-link-logout">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>
    </div>
</div>

<!-- ===== LAYER PANEL ===== -->
<div id="layer-panel">
    <button id="layer-toggle-btn" onclick="togglePanel()">
        <div class="ltb-left">
            <div class="ltb-dot"></div>
            <span>Kontrol Lapisan</span>
            <div class="ltb-count" id="active-count">3</div>
        </div>
        <i class="fa-solid fa-chevron-down ltb-chevron"></i>
    </button>

    <div id="layer-body">
        <div class="lp-header">
            <div>
                <div class="lp-header-title">WebGIS</div>
                <div class="lp-header-sub">Peta Sosial &amp; Warga</div>
            </div>
            <div class="lp-header-badge">Pontianak</div>
        </div>

        <div class="lp-scroll">

            <!-- BASEMAP -->
            <div class="lp-section">Peta Dasar</div>
            <div class="bm-grid">
                <div class="bm-card active" id="bm-osm" onclick="switchBasemapVisual('osm')">
                    <div class="bm-thumb bm-thumb-osm">
                        <div class="bm-thumb-osm-inner">
                            <div class="bm-road" style="width:70%;height:3px;top:35%;left:0;"></div>
                            <div class="bm-road" style="width:3px;height:70%;top:0;left:35%;"></div>
                            <div class="bm-road" style="width:50%;height:2px;top:60%;left:25%;opacity:0.5;"></div>
                        </div>
                    </div>
                    <div class="bm-label"><i class="fa-solid fa-map" style="margin-right:4px;color:#3b82f6;font-size:10px;"></i>Peta Jalan</div>
                </div>
                <div class="bm-card" id="bm-satellite" onclick="switchBasemapVisual('satellite')">
                    <div class="bm-thumb bm-thumb-sat"><div class="bm-thumb-sat-inner"></div></div>
                    <div class="bm-label"><i class="fa-solid fa-earth-asia" style="margin-right:4px;color:#6b7280;font-size:10px;"></i>Satelit</div>
                </div>
            </div>

            <!-- QUICK ADD -->
            <div class="lp-section">Tambah Data</div>
            <div class="qa-grid">
                <button class="qa-btn" style="background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.25);" onclick="openDrawer('masjid')">
                    <i class="fa-solid fa-place-of-worship" style="color:#10b981;"></i>
                    <span style="color:#10b981;">Rumah Ibadah</span>
                </button>
                <button class="qa-btn" style="background:rgba(139,92,246,0.1);border-color:rgba(139,92,246,0.25);" onclick="openDrawer('miskin')">
                    <i class="fa-solid fa-house-user" style="color:#8b5cf6;"></i>
                    <span style="color:#8b5cf6;">Warga</span>
                </button>
                <button class="qa-btn" style="background:rgba(225,29,72,0.1);border-color:rgba(225,29,72,0.25);" onclick="openDrawer('lapor')">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#e11d48;"></i>
                    <span style="color:#e11d48;">Laporan</span>
                </button>
            </div>

            <!-- LAYER TOGGLES -->
            <div class="lp-section">Data Spasial</div>

            <div class="lp-row" onclick="toggleLayerNew('masjid')">
                <div class="lp-icon icon-rumibadah"><i class="fa-solid fa-place-of-worship"></i></div>
                <div class="lp-info">
                    <div class="lp-name">Rumah Ibadah</div>
                    <div class="lp-desc">Titik lokasi rumah ibadah</div>
                </div>
                <button class="lp-add-btn" style="background:rgba(16,185,129,0.12);border-color:rgba(16,185,129,0.3);color:#10b981;"
                    onclick="event.stopPropagation();openDrawer('masjid')" title="Tambah Rumah Ibadah">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <label class="lp-toggle" onclick="event.stopPropagation()">
                    <input type="checkbox" checked id="chk-masjid" onchange="toggleLayerNew('masjid')">
                    <span class="lp-slider"></span>
                </label>
            </div>

            <div class="lp-row" onclick="toggleLayerNew('miskin')">
                <div class="lp-icon icon-miskin"><i class="fa-solid fa-house-user"></i></div>
                <div class="lp-info">
                    <div class="lp-name">Warga Miskin</div>
                    <div class="lp-desc">Sebaran penduduk miskin</div>
                </div>
                <button class="lp-add-btn" style="background:rgba(139,92,246,0.12);border-color:rgba(139,92,246,0.3);color:#8b5cf6;"
                    onclick="event.stopPropagation();openDrawer('miskin')" title="Tambah Warga">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <label class="lp-toggle" onclick="event.stopPropagation()">
                    <input type="checkbox" checked id="chk-miskin" onchange="toggleLayerNew('miskin')">
                    <span class="lp-slider"></span>
                </label>
            </div>

            <div class="lp-row" onclick="toggleLayerNew('lapor')">
                <div class="lp-icon icon-lapor"><i class="fa-solid fa-bell"></i></div>
                <div class="lp-info">
                    <div class="lp-name">Pengaduan Warga</div>
                    <div class="lp-desc">Laporan &amp; aduan masyarakat</div>
                </div>
                <button class="lp-add-btn" style="background:rgba(225,29,72,0.12);border-color:rgba(225,29,72,0.3);color:#e11d48;"
                    onclick="event.stopPropagation();openDrawer('lapor')" title="Buat Laporan">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <label class="lp-toggle" onclick="event.stopPropagation()">
                    <input type="checkbox" checked id="chk-lapor" onchange="toggleLayerNew('lapor')">
                    <span class="lp-slider"></span>
                </label>
            </div>

        </div><!-- /lp-scroll -->

        <div class="lp-footer">
            <div class="lp-footer-txt"><span id="footer-count">3</span> lapisan aktif</div>
            <button class="lp-btn-all" id="btn-all" onclick="toggleAllLayers()">Sembunyikan Semua</button>
        </div>
    </div><!-- /layer-body -->
</div><!-- /layer-panel -->

<!-- ===== MAP ===== -->
<div id="map"></div>

<!-- ===== DRAWER OVERLAY ===== -->
<div id="drawer-overlay" onclick="closeDrawer()"></div>

<!-- ===== DRAWER PANEL ===== -->
<div id="drawer-panel">
    <div id="drawer-header" class="drawer-header">
        <!-- filled by JS -->
    </div>
    <div id="drawer-body" class="drawer-body">
        <!-- filled by JS -->
    </div>
</div>

<!-- ===== TOAST ===== -->
<div id="toast">
    <i class="fa-solid fa-circle-check" style="color:#22c55e;"></i>
    <span id="toast-msg">Data berhasil disimpan!</span>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

<script>
/* ===================== DARK MODE ===================== */
(function(){
    var saved = localStorage.getItem('webgis-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    updateDmIcon(saved);
})();
function toggleDarkMode() {
    var html = document.documentElement;
    var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('webgis-theme', next);
    updateDmIcon(next);
}
function updateDmIcon(theme) {
    var icon = document.getElementById('dmIcon');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
}

/* ===================== MAP INIT ===================== */
var osmLayer       = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });
var map = L.map('map', { layers: [osmLayer], zoomControl: true }).setView([-0.0263, 109.3425], 13);

function createCustomIcon(iconClass, pinColor) {
    return L.divIcon({
        html: '<div class="pin-container">' +
            '<svg width="32" height="42" viewBox="0 0 32 42" xmlns="http://www.w3.org/2000/svg">' +
            '<path d="M16 0C7.16 0 0 7.16 0 16c0 10.11 14.85 24.71 15.49 25.34.28.28.74.28 1.02 0C17.15 40.71 32 26.11 32 16 32 7.16 24.84 0 16 0z" fill="' + pinColor + '" stroke="#ffffff" stroke-width="2"/>' +
            '</svg>' +
            '<div class="pin-icon-wrapper"><i class="' + iconClass + '"></i></div>' +
            '</div>',
        className: 'custom-div-icon', iconSize: [32,42], iconAnchor: [16,42], popupAnchor: [0,-40]
    });
}

var masjidGroup = L.layerGroup().addTo(map);
var miskinGroup = L.layerGroup().addTo(map);
var laporGroup  = L.layerGroup().addTo(map);

var layerMap = {
    masjid: masjidGroup,
    miskin: miskinGroup,
    lapor:  laporGroup
};
var allHidden = false;

/* ===================== LAYER PANEL ===================== */
function togglePanel() {
    var panel = document.getElementById('layer-panel');
    var body  = document.getElementById('layer-body');
    panel.classList.toggle('open');
    body.classList.toggle('open');
}
function toggleLayerNew(name) {
    var layer = layerMap[name];
    var chk   = document.getElementById('chk-' + name);
    if (map.hasLayer(layer)) { map.removeLayer(layer); if (chk) chk.checked = false; }
    else                     { map.addLayer(layer);    if (chk) chk.checked = true;  }
    updateLayerCount();
}
function updateLayerCount() {
    var count = Object.keys(layerMap).filter(function(n){ return map.hasLayer(layerMap[n]); }).length;
    document.getElementById('active-count').textContent = count;
    document.getElementById('footer-count').textContent = count;
    document.getElementById('btn-all').textContent = count === 0 ? 'Tampilkan Semua' : 'Sembunyikan Semua';
    allHidden = (count === 0);
}
function toggleAllLayers() {
    allHidden = !allHidden;
    Object.keys(layerMap).forEach(function(n) {
        var chk = document.getElementById('chk-' + n);
        if (allHidden) { map.removeLayer(layerMap[n]); if(chk) chk.checked = false; }
        else           { map.addLayer(layerMap[n]);    if(chk) chk.checked = true;  }
    });
    updateLayerCount();
}
function switchBasemap(type) {
    if (type === 'osm') { map.removeLayer(satelliteLayer); map.addLayer(osmLayer); }
    else                { map.removeLayer(osmLayer);       map.addLayer(satelliteLayer); }
}
function switchBasemapVisual(type) {
    switchBasemap(type);
    document.getElementById('bm-osm').classList.toggle('active',       type === 'osm');
    document.getElementById('bm-satellite').classList.toggle('active', type === 'satellite');
}
document.addEventListener('click', function(e) {
    var panel = document.getElementById('layer-panel');
    if (!panel.contains(e.target)) {
        panel.classList.remove('open');
        document.getElementById('layer-body').classList.remove('open');
    }
});

/* ===================== TOAST ===================== */
function showToast(msg) {
    var el = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg || 'Data berhasil disimpan!';
    el.classList.add('show');
    setTimeout(function(){ el.classList.remove('show'); }, 3200);
}

/* ===================== DRAWER ===================== */
var drawerConfigs = {
    masjid: {
        title: 'Tambah Rumah Ibadah',
        icon:  'fa-solid fa-place-of-worship',
        color: '#10b981',
        action: 'simpan_rumah_ibadah.php'
    },
    miskin: {
        title: 'Tambah Data Warga',
        icon:  'fa-solid fa-house-user',
        color: '#8b5cf6',
        action: 'simpan_miskin.php'
    },
    lapor: {
        title: 'Kirim Laporan Cepat',
        icon:  'fa-solid fa-triangle-exclamation',
        color: '#e11d48',
        action: 'simpan_laporan_cepat.php'
    }
};

var currentDrawerKey = null;
var lastClickedLatLng = null;

map.on('click', function(e) {
    lastClickedLatLng = e.latlng;
    if (!document.getElementById('drawer-panel').classList.contains('open')) {
        renderForm('miskin', e.latlng.lat, e.latlng.lng);
    } else {
        var latEl = document.getElementById('df-latitude');
        var lngEl = document.getElementById('df-longitude');
        if (latEl) latEl.value = e.latlng.lat.toFixed(6);
        if (lngEl) lngEl.value = e.latlng.lng.toFixed(6);
        showToast('Koordinat diperbarui dari klik peta');
    }
});

function openDrawer(key, prefillLat, prefillLng) {
    currentDrawerKey = key;
    var cfg = drawerConfigs[key];
    if (!cfg) return;

    document.getElementById('layer-panel').classList.remove('open');
    document.getElementById('layer-body').classList.remove('open');

    document.getElementById('drawer-header').style.background =
        'linear-gradient(135deg, ' + cfg.color + ' 0%, ' + cfg.color + 'bb 100%)';
    document.getElementById('drawer-header').innerHTML =
        '<div style="display:flex;align-items:center;gap:12px;flex:1;">' +
            '<div style="width:42px;height:42px;border-radius:13px;background:rgba(255,255,255,0.2);' +
                'display:flex;align-items:center;justify-content:center;">' +
                '<i class="' + cfg.icon + '" style="font-size:18px;color:#fff;"></i>' +
            '</div>' +
            '<div>' +
                '<div class="drawer-header-eyebrow">Tambah Data Baru</div>' +
                '<div class="drawer-header-title">' + cfg.title + '</div>' +
            '</div>' +
        '</div>' +
        '<button class="drawer-close-btn" onclick="closeDrawer()">' +
            '<i class="fa-solid fa-xmark"></i>' +
        '</button>';

    var lat = prefillLat || (lastClickedLatLng ? lastClickedLatLng.lat.toFixed(6) : '');
    var lng = prefillLng || (lastClickedLatLng ? lastClickedLatLng.lng.toFixed(6) : '');
    document.getElementById('drawer-body').innerHTML = buildDrawerForm(key, cfg, lat, lng);

    document.getElementById('drawer-overlay').classList.add('open');
    document.getElementById('drawer-panel').classList.add('open');
}

function closeDrawer() {
    document.getElementById('drawer-overlay').classList.remove('open');
    document.getElementById('drawer-panel').classList.remove('open');
    currentDrawerKey = null;
}

function buildDrawerForm(key, cfg, lat, lng) {
    var html = '<form id="drawer-form" onsubmit="submitDrawerForm(event)">';

    if (key === 'masjid') {
        html += field('Nama Rumah Ibadah', 'nama_rumah_ibadah', 'text', true, 'Contoh: Nama Rumah Ibadah');
        html += field('Nama PIC / Penanggungjawab', 'nama_pic', 'text', true, 'Nama penanggung jawab');
        html += field('Radius Jangkauan (meter)', 'radius_meter', 'number', false, '500');
        html += coordHint();
        html += coordFields(lat, lng);
    }
    else if (key === 'miskin') {
        html += field('Nama Kepala Keluarga', 'nama_kk', 'text', true, 'Nama lengkap KK');
        html += field('Alamat Lengkap', 'alamat', 'text', true, 'Alamat tempat tinggal');
        html += '<div class="df-row2">';
        html += field('Jumlah Anggota', 'anggota_keluarga', 'number', true, '4');
        html += field('Umur KK', 'umur', 'number', true, '35');
        html += '</div>';
        html += field('Tanggal Lahir KK', 'tanggal_lahir', 'date', false, '');
        html += selectField('Pendidikan Terakhir', 'pendidikan_terakhir', true, [
            {val:'Tidak Sekolah',label:'Tidak Sekolah'},
            {val:'SD',label:'SD'},
            {val:'SMP',label:'SMP'},
            {val:'SMA/SMK',label:'SMA / SMK'},
            {val:'D3',label:'D3'},
            {val:'S1',label:'S1'},
        ]);
        html += field('Riwayat Penyakit', 'riwayat_penyakit', 'text', false, 'Contoh: Hipertensi, Diabetes, -');
        html += coordHint();
        html += coordFields(lat, lng);
    }
    else if (key === 'lapor') {
        html += field('Nama Pelapor', 'nama_pelapor', 'text', true, 'Nama lengkap');
        html += field('Kontak WhatsApp', 'kontak_pelapor', 'text', true, '08xxxxxxxxxx');
        html += selectField('Kategori Laporan', 'kategori', true, [
            {val:'Jalan Rusak',       label:'Jalan Rusak'},
            {val:'Banjir',            label:'Banjir'},
            {val:'Sampah',            label:'Sampah'},
            {val:'Penerangan Jalan',  label:'Penerangan Jalan'},
            {val:'Lainnya',           label:'Lainnya'},
        ]);
        html += '<div class="df-group">';
        html += '<label class="df-label">Deskripsi Laporan <span class="req">*</span></label>';
        html += '<textarea class="df-textarea" name="deskripsi_laporan" rows="4" required ' +
            'placeholder="Ceritakan masalah secara detail..."></textarea>';
        html += '</div>';
        html += coordHint();
        html += coordFields(lat, lng);
    }

    html += '<input type="hidden" name="action_key" value="' + key + '">';

    html += '<button type="submit" class="df-submit-btn" id="drawer-submit-btn" style="background:' + cfg.color + ';">' +
        '<i class="fa-solid fa-floppy-disk"></i> Simpan Data' +
        '</button>';
    html += '</form>';
    return html;
}

function field(label, name, type, required, placeholder) {
    var req = required ? '<span class="req">*</span>' : '';
    return '<div class="df-group">' +
        '<label class="df-label">' + label + req + '</label>' +
        '<input class="df-input" type="' + type + '" name="' + name + '" id="df-' + name + '" ' +
        (required ? 'required ' : '') +
        (placeholder ? 'placeholder="' + placeholder + '" ' : '') + '>' +
        '</div>';
}
function selectField(label, name, required, options) {
    var req = required ? '<span class="req">*</span>' : '';
    var opts = '<option value="">Pilih...</option>';
    options.forEach(function(o){ opts += '<option value="' + o.val + '">' + o.label + '</option>'; });
    return '<div class="df-group">' +
        '<label class="df-label">' + label + req + '</label>' +
        '<select class="df-select" name="' + name + '" id="df-' + name + '" ' + (required ? 'required' : '') + '>' +
        opts + '</select></div>';
}
function coordHint() {
    return '<div class="df-hint df-hint-blue" style="margin-bottom:12px;">' +
        '<i class="fa-solid fa-map-pin" style="margin-right:5px;"></i>' +
        'Klik lokasi di peta untuk mengisi koordinat secara otomatis, atau isi manual di bawah.' +
        '</div>';
}
function coordFields(lat, lng) {
    return '<div class="df-row2">' +
        '<div class="df-group"><label class="df-label">Latitude <span class="req">*</span></label>' +
        '<input class="df-input" type="text" name="latitude" id="df-latitude" required placeholder="-0.0263" value="' + (lat||'') + '"></div>' +
        '<div class="df-group"><label class="df-label">Longitude <span class="req">*</span></label>' +
        '<input class="df-input" type="text" name="longitude" id="df-longitude" required placeholder="109.3425" value="' + (lng||'') + '"></div>' +
        '</div>';
}

function submitDrawerForm(e) {
    e.preventDefault();
    var cfg = drawerConfigs[currentDrawerKey];
    if (!cfg) return;
    var btn = document.getElementById('drawer-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';

    var formData = new FormData(document.getElementById('drawer-form'));
    fetch(cfg.action, { method: 'POST', body: formData })
        .then(function(res) { return res.text(); })
        .then(function() {
            document.getElementById('drawer-body').innerHTML =
                '<div class="drawer-success">' +
                '<div class="drawer-success-icon" style="background:' + cfg.color + ';">' +
                '<i class="fa-solid fa-check" style="font-size:26px;"></i></div>' +
                '<div class="drawer-success-title">Data Tersimpan!</div>' +
                '<div class="drawer-success-desc">Data berhasil ditambahkan ke peta.</div>' +
                '<button onclick="closeDrawer();location.reload();" style="margin-top:16px;padding:10px 24px;' +
                'background:' + cfg.color + ';color:#fff;border:none;border-radius:10px;' +
                'font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;">' +
                '<i class="fa-solid fa-rotate" style="margin-right:6px;"></i>Muat Ulang Peta</button>' +
                '</div>';
            showToast('Data berhasil disimpan!');
        })
        .catch(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data';
            showToast('Gagal menyimpan. Coba lagi.');
            document.getElementById('toast').style.background = '#dc2626';
        });
}

/* ===================== KLIK PETA (form popup) ===================== */
function updateRadiusData(event, form) {
    event.preventDefault();
    var formData = new FormData(form);
    var targetUrl = formData.has('id_warga') ? 'update_radius_warga.php' : 'update_radius.php';
    fetch(targetUrl, { method: 'POST', body: formData })
        .then(function(res){ return res.text(); })
        .then(function(){ alert('Radius spasial diperbarui!'); window.location.reload(); });
    return false;
}

function renderForm(type, lat, lng) {
    var html =
        '<div class="form-title"><i class="fa-solid fa-map-pin"></i> Tambah Titik Atribut</div>' +
        '<div class="choice-group">' +
            '<button onclick="renderForm(\'masjid\',' + lat + ',' + lng + ')" class="btn-choice ' + (type==='masjid'?'active':'') + '"><i class="fa-solid fa-place-of-worship"></i>Rumah Ibadah</button>' +
            '<button onclick="renderForm(\'miskin\',' + lat + ',' + lng + ')" class="btn-choice ' + (type==='miskin'?'active':'') + '"><i class="fa-solid fa-house-user"></i>Warga</button>' +
            '<button onclick="renderForm(\'lapor\',' + lat + ',' + lng + ')" class="btn-choice ' + (type==='lapor'?'active':'') + '"><i class="fa-solid fa-triangle-exclamation"></i>Lapor</button>' +
        '</div><div id="form-container">';

    if (type === 'masjid') {
        html +=
            '<form action="simpan_rumah_ibadah.php" method="POST">' +
            '<label>Nama Rumah Ibadah</label><input name="nama_rumah_ibadah" required placeholder="Nama rumah ibadah">' +
            '<label>Nama PIC / Penanggungjawab</label><input name="nama_pic" required placeholder="Nama penanggungjawab">' +
            '<input type="hidden" name="latitude" value="' + lat + '">' +
            '<input type="hidden" name="longitude" value="' + lng + '">' +
            '<button class="btn-submit" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Rumah Ibadah</button></form>';
    } else if (type === 'miskin') {
        html +=
            '<form action="simpan_miskin.php" method="POST">' +
            '<label>Nama Kepala Keluarga</label><input name="nama_kk" required>' +
            '<label>Alamat</label><input name="alamat" required>' +
            '<label>Anggota Keluarga</label><input type="number" name="anggota_keluarga" required min="1">' +
            '<label>Umur KK</label><input type="number" name="umur" required min="1">' +
            '<label>Tanggal Lahir KK</label><input type="date" name="tanggal_lahir">' +
            '<label>Pendidikan Terakhir</label>' +
            '<select name="pendidikan_terakhir">' +
                '<option value="Tidak Sekolah">Tidak Sekolah</option>' +
                '<option value="SD">SD</option>' +
                '<option value="SMP">SMP</option>' +
                '<option value="SMA/SMK">SMA/SMK</option>' +
                '<option value="D3">D3</option>' +
                '<option value="S1">S1</option>' +
            '</select>' +
            '<label>Riwayat Penyakit</label>' +
            '<input name="riwayat_penyakit" placeholder="Contoh: Hipertensi, Diabetes, -">' +
            '<input type="hidden" name="latitude" value="' + lat + '">' +
            '<input type="hidden" name="longitude" value="' + lng + '">' +
            '<button class="btn-submit" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Warga</button></form>';
    } else if (type === 'lapor') {
        html +=
            '<form action="simpan_laporan_cepat.php" method="POST">' +
            '<label>Nama Pelapor</label><input name="nama_pelapor" required>' +
            '<label>Kontak (WhatsApp)</label><input name="kontak_pelapor" required placeholder="08xxxxxxxxxx">' +
            '<label>Kategori Laporan</label>' +
            '<select name="kategori" required>' +
                '<option value="Jalan Rusak">Jalan Rusak</option>' +
                '<option value="Banjir">Banjir</option>' +
                '<option value="Sampah">Sampah</option>' +
                '<option value="Penerangan">Penerangan Jalan</option>' +
                '<option value="Lainnya">Lainnya</option>' +
            '</select>' +
            '<label>Deskripsi Laporan</label>' +
            '<textarea name="deskripsi_laporan" rows="3" required placeholder="Ceritakan masalah secara detail..."></textarea>' +
            '<input type="hidden" name="latitude" value="' + lat + '">' +
            '<input type="hidden" name="longitude" value="' + lng + '">' +
            '<button class="btn-submit" style="background:#e11d48;" type="submit">' +
            '<i class="fa-solid fa-paper-plane"></i> Kirim Laporan</button></form>';
    }

    html += '</div>';
    L.popup({ maxWidth: 340 }).setLatLng([lat, lng]).setContent(html).openOn(map);
}
</script>

<?php
function ambilKoordinatBersih($row) {
    $lat = !empty($row['latitude'])  ? (float)$row['latitude']  : 0;
    $lng = !empty($row['longitude']) ? (float)$row['longitude'] : 0;
    return [$lat, $lng];
}

// =========================================================================
// 1. DATA RUMAH IBADAH
// =========================================================================
$masjids = [];
$q = mysqli_query($conn, "SELECT * FROM masjid ORDER BY id DESC");
if ($q && mysqli_num_rows($q) > 0) {
    while ($d = mysqli_fetch_assoc($q)) {
        list($lat, $lng) = ambilKoordinatBersih($d);
        if ($lat == 0 || $lng == 0) continue;
        $rad         = !empty($d['radius_meter']) ? (int)$d['radius_meter'] : 500;
        $masjids[]   = ['lat'=>$lat,'lng'=>$lng,'radius'=>$rad];
        $nama_masjid = htmlspecialchars($d['nama_masjid'] ?? 'Rumah Ibadah', ENT_QUOTES);
        $nama_pic    = htmlspecialchars($d['nama_pic']    ?? '-',             ENT_QUOTES);
        $id          = (int)$d['id'];
        $popup = "<div class='form-title'><i class='fa-solid fa-place-of-worship'></i> {$nama_masjid}</div>"
               . "<div class='info-box'><strong>PIC:</strong> {$nama_pic}<br><strong>Jangkauan:</strong> {$rad} Meter"
               . "<hr style='margin:6px 0;border:0;border-top:1px solid #e2e8f0;'>"
               . "<form onsubmit='return updateRadiusData(event,this)'><input type='hidden' name='id' value='{$id}'>"
               . "<div style='display:flex;gap:4px;'><input type='number' name='radius_baru' value='{$rad}' style='width:80px;padding:4px;font-size:11px;' required>"
               . "<button type='submit' style='background:#10b981;color:white;border:0;padding:4px 8px;border-radius:4px;cursor:pointer;'>Set</button></div></form></div>"
               . "<div class='action-group'>"
               . "<a href='edit_rumah_ibadah.php?id={$id}' class='btn-action btn-edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>"
               . "<a href='hapus_rumah_ibadah.php?id={$id}' class='btn-action btn-delete' onclick='return confirm(\"Hapus Data Rumah Ibadah?\")'>Hapus</a></div>";
        $popupJs = json_encode($popup);
        echo "<script>
        L.circle([$lat,$lng],{radius:$rad,color:'#10b981',weight:1.5,fillOpacity:0.1}).addTo(masjidGroup);
        L.marker([$lat,$lng],{icon:createCustomIcon('fa-solid fa-place-of-worship','#10b981')}).bindPopup($popupJs).addTo(masjidGroup);
        </script>\n";
    }
}

// =========================================================================
// 2. DATA PENDUDUK MISKIN
// =========================================================================
$q = mysqli_query($conn, "SELECT * FROM penduduk_miskin ORDER BY id DESC");
if ($q && mysqli_num_rows($q) > 0) {
    while ($d = mysqli_fetch_assoc($q)) {
        list($lat, $lng) = ambilKoordinatBersih($d);
        if ($lat == 0 || $lng == 0) continue;
        $dalamRadius = false;
        foreach ($masjids as $m) {
            $R = 6371000; $dLat = deg2rad($lat-$m['lat']); $dLng = deg2rad($lng-$m['lng']);
            $a = sin($dLat/2)*sin($dLat/2)+cos(deg2rad($m['lat']))*cos(deg2rad($lat))*sin($dLng/2)*sin($dLng/2);
            if (($R*2*atan2(sqrt($a),sqrt(1-$a))) <= $m['radius']) { $dalamRadius = true; break; }
        }
        $hexColor  = $dalamRadius ? '#6366f1' : '#db2777';
        $zoneText  = $dalamRadius
            ? "<span class='badge-zone badge-in'><i class='fa-solid fa-circle-check'></i> Terjangkau Radius</span>"
            : "<span class='badge-zone badge-out'><i class='fa-solid fa-circle-xmark'></i> Luar Radius</span>";
        $nama_kk   = htmlspecialchars($d['nama_kk'] ?? 'Warga', ENT_QUOTES);
        $alamat    = htmlspecialchars($d['alamat']   ?? '-',     ENT_QUOTES);
        $penyakit  = htmlspecialchars($d['riwayat_penyakit'] ?? '-', ENT_QUOTES);
        $tgl_lahir = !empty($d['tanggal_lahir']) ? date('d M Y', strtotime($d['tanggal_lahir'])) : '-';
        $rad_warga = !empty($d['radius_warga']) ? (int)$d['radius_warga'] : 300;
        $id        = (int)$d['id'];
        $popup = "<div class='form-title'><i class='fa-solid fa-house-user'></i> {$nama_kk}</div>"
               . "<div class='info-box'>"
               . "<strong>Alamat:</strong> {$alamat}<br>"
               . "<strong>Tgl Lahir:</strong> {$tgl_lahir}<br>"
               . "<strong>Riwayat Penyakit:</strong> {$penyakit}<br>"
               . $zoneText
               . "<hr style='margin:6px 0;border:0;border-top:1px solid #e2e8f0;'>"
               . "<form onsubmit='return updateRadiusData(event,this)'><input type='hidden' name='id_warga' value='{$id}'>"
               . "<div style='display:flex;gap:4px;'><input type='number' name='radius_baru_warga' value='{$rad_warga}' style='width:80px;padding:4px;font-size:11px;' required>"
               . "<button type='submit' style='background:#10b981;color:white;border:0;padding:4px 8px;border-radius:4px;cursor:pointer;'>Set</button></div></form></div>"
               . "<div class='action-group'>"
               . "<a href='edit_miskin.php?id={$id}' class='btn-action btn-edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>"
               . "<a href='hapus_miskin.php?id={$id}' class='btn-action btn-delete' onclick='return confirm(\"Hapus Data?\")'>Hapus</a></div>"
               . "<button onclick=\"map.closePopup();renderForm('lapor',{$lat},{$lng});\" class='btn-submit' style='background:#e11d48;margin-top:8px;'>"
               . "<i class='fa-solid fa-triangle-exclamation'></i> Buat Laporan</button>";
        $popupJs = json_encode($popup);
        echo "<script>
        L.circle([$lat,$lng],{radius:$rad_warga,color:'$hexColor',weight:1,fillOpacity:0.04}).addTo(miskinGroup);
        L.marker([$lat,$lng],{icon:createCustomIcon('fa-solid fa-house-user','$hexColor')}).bindPopup($popupJs).addTo(miskinGroup);
        </script>\n";
    }
}

// =========================================================================
// 3. DATA LAPORAN CEPAT
// =========================================================================
$q = mysqli_query($conn, "SELECT * FROM laporan_cepat ORDER BY tgl_lapor DESC");
if ($q && mysqli_num_rows($q) > 0) {
    while ($d = mysqli_fetch_assoc($q)) {
        list($lat, $lng) = ambilKoordinatBersih($d);
        if ($lat == 0 || $lng == 0) continue;
        $nama_pelapor = htmlspecialchars($d['nama_pelapor'] ?? 'Anonim', ENT_QUOTES);
        $kategori     = htmlspecialchars($d['kategori']     ?? '-',      ENT_QUOTES);
        $deskripsi    = htmlspecialchars(substr($d['deskripsi'] ?? '-', 0, 100), ENT_QUOTES);
        $status       = $d['status'] ?? 'Masuk';
        $statusColor  = ['Masuk'=>'#d97706','Diproses'=>'#2563eb','Selesai'=>'#16a34a'][$status] ?? '#64748b';
        $tgl          = date('d M Y', strtotime($d['tgl_lapor']));
        $id           = (int)$d['id'];
        $popup = "<div class='form-title' style='color:#e11d48;'><i class='fa-solid fa-triangle-exclamation'></i> Laporan Cepat</div>"
               . "<div class='info-box'>"
               . "<strong>Pelapor:</strong> {$nama_pelapor}<br>"
               . "<strong>Kategori:</strong> {$kategori}<br>"
               . "<strong>Laporan:</strong> {$deskripsi}<br>"
               . "<strong>Tanggal:</strong> {$tgl}<br>"
               . "<span style='display:inline-flex;align-items:center;gap:4px;padding:2px 8px;background:#f8fafc;border-radius:6px;font-size:10px;font-weight:700;color:{$statusColor};margin-top:4px;'>"
               . "<i class='fa-solid fa-circle' style='font-size:6px;'></i> {$status}</span>"
               . "</div>"
               . "<div class='action-group'>"
               . "<a href='edit_pengaduan.php?id={$id}' class='btn-action btn-edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>"
               . "<a href='hapus_pengaduan.php?id={$id}' class='btn-action btn-delete' onclick='return confirm(\"Hapus Laporan?\")'><i class='fa-solid fa-trash'></i> Hapus</a>"
               . "</div>";
        $popupJs = json_encode($popup);
        echo "<script>L.marker([$lat,$lng],{icon:createCustomIcon('fa-solid fa-triangle-exclamation','#e11d48')}).bindPopup($popupJs).addTo(laporGroup);</script>\n";
    }
}

// Fallback tabel pengaduan_warga lama
$q2 = mysqli_query($conn, "SHOW TABLES LIKE 'pengaduan_warga'");
if ($q2 && mysqli_num_rows($q2) > 0) {
    $q = mysqli_query($conn, "SELECT * FROM pengaduan_warga ORDER BY id DESC");
    if ($q && mysqli_num_rows($q) > 0) {
        while ($d = mysqli_fetch_assoc($q)) {
            list($lat, $lng) = ambilKoordinatBersih($d);
            if ($lat == 0 || $lng == 0) continue;
            $nama_pelapor = htmlspecialchars($d['nama_pelapor']      ?? 'Anonim', ENT_QUOTES);
            $aduan        = htmlspecialchars($d['deskripsi_laporan'] ?? '-',      ENT_QUOTES);
            $id           = (int)$d['id'];
            $popup = "<div class='form-title' style='color:#e11d48;'><i class='fa-solid fa-triangle-exclamation'></i> Laporan Warga</div>"
                   . "<div class='info-box'><strong>Pelapor:</strong> {$nama_pelapor}<br><strong>Aduan:</strong> {$aduan}</div>"
                   . "<div class='action-group'>"
                   . "<a href='edit_pengaduan.php?id={$id}' class='btn-action btn-edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>"
                   . "<a href='hapus_pengaduan.php?id={$id}' class='btn-action btn-delete' onclick='return confirm(\"Hapus Laporan?\")'><i class='fa-solid fa-trash'></i> Hapus</a></div>";
            $popupJs = json_encode($popup);
            echo "<script>L.marker([$lat,$lng],{icon:createCustomIcon('fa-solid fa-triangle-exclamation','#e11d48')}).bindPopup($popupJs).addTo(laporGroup);</script>\n";
        }
    }
}
?>
</body>
</html>