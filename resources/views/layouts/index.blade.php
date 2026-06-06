<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="DeportBeca UMSA - El deporte abre las puertas" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DeportBeca | El deporte abre las puertas</title>
    <link href="{{ asset('img/brand/logos.jpg') }}" rel="icon" type="image/png">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* ============================================================
   VARIABLES UMSA
   ============================================================ */
:root {
    --umsa-blue:       #1A5276;
    --umsa-blue-dark:  #0D3451;
    --umsa-blue-mid:   #1F618D;
    --umsa-blue-light: rgba(26, 82, 118, 0.18);
    --umsa-red:        #C0392B;
    --umsa-red-dark:   #921E13;
    --umsa-red-light:  rgba(192, 57, 43, 0.18);
    --bg-page:         #080F18;
    --bg-surface:      #0D1B26;
    --bg-card:         #112233;
    --border:          #1E3450;
    --border-light:    rgba(255,255,255,0.08);
    --text-white:      #EAF1F8;
    --text-gray:       #8AAFC8;
    --text-muted:      #4A6A85;
}

/* ============================================================
   RESET & BASE
   ============================================================ */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg-page);
    color: var(--text-white);
    overflow-x: hidden;
}

/* ============================================================
   NAVBAR
   ============================================================ */
#mainNav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 1000;
    padding: 18px 0;
    background: transparent;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

#mainNav.scrolled {
    background: rgba(8, 15, 24, 0.94);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}

.nav-brand-text {
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    font-size: 1.5rem;
    letter-spacing: -0.5px;
    text-decoration: none;
}

.nav-brand-text .brand-umsa { color: var(--umsa-blue); }
.nav-brand-text .brand-sep  { color: var(--text-muted); }
.nav-brand-text .brand-dep  { color: var(--umsa-red); }

.nav-link-custom {
    color: rgba(255,255,255,0.85) !important;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 8px 14px !important;
    border-radius: 8px;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.02em;
}

.nav-link-custom:hover {
    color: #fff !important;
    background: rgba(26,82,118,0.25);
}

.nav-link-custom.nav-cta {
    background: linear-gradient(135deg, var(--umsa-red) 0%, var(--umsa-red-dark) 100%);
    color: #fff !important;
    font-weight: 600;
    padding: 8px 20px !important;
}

.nav-link-custom.nav-cta:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(192,57,43,0.4);
    background: linear-gradient(135deg, var(--umsa-red-dark) 0%, var(--umsa-red) 100%);
}

/* ============================================================
   HERO SECTION
   ============================================================ */
.hero {
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* Animated gradient background */
.hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 30%, rgba(26, 82, 118, 0.55) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 70%, rgba(192, 57, 43, 0.25) 0%, transparent 55%),
        radial-gradient(ellipse 100% 100% at 50% 50%, var(--bg-page) 0%, var(--bg-page) 100%);
    animation: heroBgShift 10s ease-in-out infinite alternate;
}

@keyframes heroBgShift {
    0%   { filter: hue-rotate(0deg) brightness(1); }
    100% { filter: hue-rotate(8deg) brightness(1.06); }
}

/* Grid lines overlay */
.hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(26,82,118,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,82,118,0.06) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%);
    -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%);
}

/* Floating orbs */
.hero-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    animation: orbFloat 8s ease-in-out infinite;
    pointer-events: none;
}

.hero-orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(26,82,118,0.4) 0%, transparent 70%);
    top: -100px; left: -100px;
    animation-delay: 0s;
}

.hero-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(192,57,43,0.3) 0%, transparent 70%);
    bottom: -50px; right: -50px;
    animation-delay: -3s;
}

.hero-orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(31,97,141,0.35) 0%, transparent 70%);
    top: 40%; right: 20%;
    animation-delay: -6s;
}

@keyframes orbFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%       { transform: translate(30px, -40px) scale(1.08); }
    66%       { transform: translate(-20px, 20px) scale(0.95); }
}

/* Floating particles */
.particles {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.particle {
    position: absolute;
    border-radius: 50%;
    animation: particleDrift linear infinite;
    opacity: 0;
}

@keyframes particleDrift {
    0%   { transform: translateY(100vh) scale(0); opacity: 0; }
    5%   { opacity: 1; }
    90%  { opacity: 0.6; }
    100% { transform: translateY(-10vh) scale(1); opacity: 0; }
}

/* Hero content */
.hero-content {
    position: relative;
    z-index: 10;
    text-align: center;
    padding: 120px 20px 80px;
    max-width: 900px;
    margin: 0 auto;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(26,82,118,0.25);
    border: 1px solid rgba(26,82,118,0.5);
    border-radius: 50px;
    padding: 8px 20px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #7FB3D3;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 28px;
    backdrop-filter: blur(8px);
}

.hero-badge .badge-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--umsa-red);
    box-shadow: 0 0 8px var(--umsa-red);
    animation: dotPulse 2s ease-in-out infinite;
}

@keyframes dotPulse {
    0%, 100% { box-shadow: 0 0 6px var(--umsa-red); transform: scale(1); }
    50%       { box-shadow: 0 0 14px var(--umsa-red), 0 0 24px rgba(192,57,43,0.4); transform: scale(1.3); }
}

.hero-logo {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    border: 3px solid rgba(26,82,118,0.6);
    box-shadow:
        0 0 0 6px rgba(26,82,118,0.12),
        0 0 0 12px rgba(26,82,118,0.06),
        0 20px 60px rgba(0,0,0,0.5);
    animation: logoGlow 4s ease-in-out infinite, logoFloat 5s ease-in-out infinite;
    margin: 0 auto 32px;
    display: block;
    object-fit: cover;
}

@keyframes logoGlow {
    0%, 100% { box-shadow: 0 0 0 6px rgba(26,82,118,0.12), 0 0 0 12px rgba(26,82,118,0.06), 0 20px 60px rgba(0,0,0,0.5); }
    50%       { box-shadow: 0 0 0 8px rgba(26,82,118,0.22), 0 0 0 18px rgba(26,82,118,0.1), 0 20px 80px rgba(26,82,118,0.35); }
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-12px); }
}

.hero-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    font-size: clamp(2.4rem, 6vw, 4.5rem);
    line-height: 1.1;
    letter-spacing: -1px;
    margin-bottom: 20px;
    color: var(--text-white);
}

.hero-title .hl-blue {
    background: linear-gradient(135deg, #5DADE2 0%, #85C1E9 50%, #AED6F1 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.hero-title .hl-red {
    background: linear-gradient(135deg, var(--umsa-red) 0%, #E74C3C 50%, #F1948A 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    position: relative;
    display: inline-block;
}

.hero-subtitle {
    color: var(--text-gray);
    font-size: clamp(1rem, 2vw, 1.2rem);
    font-weight: 400;
    line-height: 1.7;
    margin-bottom: 40px;
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
}

.hero-pills {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 44px;
}

.hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 50px;
    padding: 7px 16px;
    font-size: 0.82rem;
    color: var(--text-gray);
    font-weight: 500;
    backdrop-filter: blur(6px);
    transition: all 0.25s;
}

.hero-pill:hover {
    background: rgba(26,82,118,0.25);
    border-color: rgba(26,82,118,0.5);
    color: var(--text-white);
    transform: translateY(-2px);
}

.hero-pill i { color: var(--umsa-red); }

.hero-ctas {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg, var(--umsa-red) 0%, var(--umsa-red-dark) 100%);
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 14px 32px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(192,57,43,0.4);
    position: relative;
    overflow: hidden;
}

.btn-hero-primary::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.3s;
}

.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(192,57,43,0.55);
    color: #fff;
}

.btn-hero-primary:hover::after { opacity: 1; }

.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: rgba(26,82,118,0.2);
    color: #7FB3D3;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 14px 32px;
    border-radius: 50px;
    border: 1px solid rgba(26,82,118,0.5);
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(8px);
}

.btn-hero-secondary:hover {
    background: rgba(26,82,118,0.4);
    border-color: var(--umsa-blue);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(26,82,118,0.35);
}

/* Scroll indicator */
.scroll-indicator {
    position: absolute;
    bottom: 36px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    animation: scrollBounce 2.5s ease-in-out infinite;
}

@keyframes scrollBounce {
    0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.5; }
    50%       { transform: translateX(-50%) translateY(8px); opacity: 1; }
}

.scroll-chevrons { display: flex; flex-direction: column; gap: 2px; }
.scroll-chevrons i { font-size: 0.8rem; line-height: 1; }

/* ============================================================
   STATS BAR
   ============================================================ */
.stats-bar {
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 0;
    position: relative;
    overflow: hidden;
}

.stats-bar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--umsa-blue), var(--umsa-red), var(--umsa-blue), transparent);
    animation: statLine 4s linear infinite;
    background-size: 200% 100%;
}

@keyframes statLine {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.stat-item {
    padding: 28px 20px;
    text-align: center;
    border-right: 1px solid var(--border);
    position: relative;
    transition: background 0.3s;
}

.stat-item:last-child { border-right: none; }
.stat-item:hover { background: rgba(26,82,118,0.08); }

.stat-number {
    font-family: 'Montserrat', sans-serif;
    font-size: 2.4rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 6px;
    background: linear-gradient(135deg, var(--umsa-blue) 0%, #5DADE2 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stat-number.stat-red {
    background: linear-gradient(135deg, var(--umsa-red) 0%, #E74C3C 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stat-label {
    font-size: 0.78rem;
    color: var(--text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* ============================================================
   SECTION COMMON
   ============================================================ */
.section { padding: 100px 0; position: relative; }

.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--umsa-blue-light);
    border: 1px solid rgba(26,82,118,0.4);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #5DADE2;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.section-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    line-height: 1.2;
    color: var(--text-white);
    margin-bottom: 16px;
}

.section-title .hl {
    background: linear-gradient(135deg, #5DADE2 0%, #AED6F1 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.section-title .hl-r {
    background: linear-gradient(135deg, var(--umsa-red) 0%, #E74C3C 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.section-lead {
    color: var(--text-gray);
    font-size: 1.05rem;
    line-height: 1.75;
    max-width: 620px;
}

.divider-umsa {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0 48px;
}

.divider-umsa .d-line {
    height: 2px;
    flex: 1;
    max-width: 80px;
    background: linear-gradient(90deg, transparent, var(--umsa-blue));
    border-radius: 2px;
}

.divider-umsa .d-line.right { background: linear-gradient(90deg, var(--umsa-blue), transparent); }
.divider-umsa .d-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--umsa-red); box-shadow: 0 0 10px rgba(192,57,43,0.6); }

/* ============================================================
   QUIENES SOMOS
   ============================================================ */
.section-quienes {
    background: var(--bg-surface);
    position: relative;
    overflow: hidden;
}

.section-quienes::before {
    content: '';
    position: absolute;
    top: -200px; right: -200px;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(26,82,118,0.12) 0%, transparent 70%);
    pointer-events: none;
}

.info-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px;
    height: 100%;
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--umsa-blue), var(--umsa-red));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
}

.info-card:hover {
    transform: translateY(-8px);
    border-color: rgba(26,82,118,0.5);
    box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(26,82,118,0.2);
}

.info-card:hover::before { transform: scaleX(1); }

.info-card-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 20px;
    background: linear-gradient(135deg, var(--umsa-blue) 0%, var(--umsa-blue-mid) 100%);
    box-shadow: 0 8px 20px rgba(26,82,118,0.4);
}

.info-card-icon.red {
    background: linear-gradient(135deg, var(--umsa-red) 0%, var(--umsa-red-dark) 100%);
    box-shadow: 0 8px 20px rgba(192,57,43,0.4);
}

.info-card h3 {
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--text-white);
    margin-bottom: 12px;
}

.info-card p {
    color: var(--text-gray);
    line-height: 1.75;
    font-size: 0.92rem;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 32px;
}

.feature-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-gray);
    font-size: 0.88rem;
    font-weight: 500;
}

.feature-list li i {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--umsa-blue-light);
    border: 1px solid rgba(26,82,118,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5DADE2;
    font-size: 0.75rem;
    flex-shrink: 0;
}

/* ============================================================
   SECTION COMUNICADOS
   ============================================================ */
.section-comunicados {
    background: var(--bg-page);
    position: relative;
}

.section-comunicados::before {
    content: '';
    position: absolute;
    bottom: 0; left: -150px;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(192,57,43,0.07) 0%, transparent 70%);
    pointer-events: none;
}

/* ============================================================
   CTA SECTION
   ============================================================ */
.section-cta {
    background: var(--bg-surface);
    position: relative;
    overflow: hidden;
    text-align: center;
    padding: 90px 0;
}

.section-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 30% 50%, rgba(26,82,118,0.2) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 70% 50%, rgba(192,57,43,0.12) 0%, transparent 60%);
}

.cta-box {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
}

.cta-box h2 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: clamp(1.6rem, 3.5vw, 2.6rem);
    color: var(--text-white);
    margin-bottom: 16px;
}

.cta-box p {
    color: var(--text-gray);
    font-size: 1.05rem;
    margin-bottom: 36px;
    line-height: 1.65;
}

/* ============================================================
   FOOTER
   ============================================================ */
.site-footer {
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    padding: 70px 0 0;
    position: relative;
    overflow: hidden;
}

.site-footer::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, var(--umsa-blue) 30%, var(--umsa-red) 50%, var(--umsa-blue) 70%, transparent 100%);
}

.footer-brand-name {
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    font-size: 1.5rem;
    color: var(--text-white);
    margin-bottom: 10px;
}

.footer-brand-name span { color: var(--umsa-red); }
.footer-tagline { color: var(--text-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 24px; max-width: 260px; }

.footer-social-btn {
    width: 44px; height: 44px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-gray);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    margin-right: 8px;
}

.footer-social-btn:hover {
    background: var(--umsa-blue);
    border-color: var(--umsa-blue);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(26,82,118,0.45);
}

.footer-social-btn.whatsapp:hover {
    background: #25D366;
    border-color: #25D366;
    box-shadow: 0 6px 18px rgba(37,211,102,0.4);
}

.footer-col-title {
    font-weight: 700;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-muted);
    margin-bottom: 18px;
}

.footer-link {
    display: block;
    color: var(--text-gray);
    font-size: 0.88rem;
    text-decoration: none;
    padding: 5px 0;
    transition: color 0.2s, padding-left 0.2s;
}

.footer-link:hover { color: #5DADE2; padding-left: 6px; }

.footer-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: var(--text-gray);
    font-size: 0.88rem;
    margin-bottom: 12px;
}

.footer-contact-item i {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--umsa-blue-light);
    border: 1px solid rgba(26,82,118,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5DADE2;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.footer-bottom {
    border-top: 1px solid var(--border);
    padding: 20px 0;
    margin-top: 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.footer-bottom span { color: var(--text-muted); font-size: 0.8rem; }

/* ============================================================
   MODAL OVERRIDES
   ============================================================ */
.modal-content {
    background: linear-gradient(145deg, #0D1B26, #112233);
    border: 1px solid var(--border);
    border-radius: 20px;
}

/* ============================================================
   SVG WAVE SEPARATOR
   ============================================================ */
.wave-sep {
    display: block;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    margin-top: -2px;
}

.wave-sep svg { display: block; width: 100%; }

/* ============================================================
   MOBILE
   ============================================================ */
@media (max-width: 991px) {
    .stat-item { border-right: none; border-bottom: 1px solid var(--border); }
    .stat-item:last-child { border-bottom: none; }
}

@media (max-width: 768px) {
    .hero-title { font-size: 2.2rem; }
    .hero-ctas { flex-direction: column; align-items: center; }
    .feature-list { grid-template-columns: 1fr; }
    .footer-bottom { flex-direction: column; text-align: center; }
}
</style>
</head>

<body id="top">

<!-- ===================== NAVBAR ===================== -->
<nav id="mainNav" class="navbar navbar-expand-lg">
    <div class="container">
        <a class="nav-brand-text" href="#top">
            <span class="brand-umsa">BECAS</span><span class="brand-sep">&amp;</span><span class="brand-dep">DEPORTES</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks"
                style="background: rgba(26,82,118,0.3); color: #fff; padding: 6px 12px; border-radius: 8px;">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav ms-auto gap-1 align-items-lg-center mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link-custom" href="#quienes"><i class="fas fa-users"></i>Quienes somos</a></li>
                <li class="nav-item"><a class="nav-link-custom" href="#comunicados"><i class="fas fa-bullhorn"></i>Comunicados</a></li>
                <li class="nav-item"><a class="nav-link-custom" href="{{ route('portal.index') }}"><i class="fas fa-trophy"></i>Resultados</a></li>
                <li class="nav-item"><a class="nav-link-custom" href="#ubicacion"><i class="fas fa-map-marker-alt"></i>Contacto</a></li>
                <li class="nav-item">
                    <a class="nav-link-custom" href="#" data-bs-toggle="modal" data-bs-target="#preinscripcionModal">
                        <i class="fas fa-futbol"></i>Pre-inscripcion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-custom nav-cta" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>Panel Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===================== HERO ===================== -->
<section class="hero" id="top">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>

    <!-- Orbs -->
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <!-- Particles container -->
    <div class="particles" id="particles"></div>

    <div class="hero-content" data-aos="fade-up" data-aos-duration="900">
        <div class="hero-badge">
            <span class="badge-dot"></span>
            Universidad Mayor de San Andres
        </div>

        <img src="{{ asset('img/brand/logos.jpg') }}" class="hero-logo" alt="UMSA DeportBeca">

        <h1 class="hero-title">
            El deporte<br>
            <span class="hl-red">abre</span> las <span class="hl-blue">puertas</span>
        </h1>

        <p class="hero-subtitle">
            Division de Becas Deportivas de la UMSA. Conectamos el talento atletico con la excelencia academica a traves de un sistema integral de gestion deportiva.
        </p>

        <div class="hero-pills" data-aos="fade-up" data-aos-delay="200">
            <span class="hero-pill"><i class="fas fa-medal"></i> Atletismo</span>
            <span class="hero-pill"><i class="fas fa-futbol"></i> Futbol</span>
            <span class="hero-pill"><i class="fas fa-running"></i> Multiple disciplinas</span>
            <span class="hero-pill"><i class="fas fa-graduation-cap"></i> Beca universitaria</span>
        </div>

        <div class="hero-ctas" data-aos="fade-up" data-aos-delay="350">
            <a href="#" class="btn-hero-primary" data-bs-toggle="modal" data-bs-target="#preinscripcionModal">
                <i class="fas fa-pen-to-square"></i> Pre-inscribirse ahora
            </a>
            <a href="{{ route('portal.index') }}" class="btn-hero-secondary">
                <i class="fas fa-trophy"></i> Ver resultados
            </a>
        </div>
    </div>

    <div class="scroll-indicator">
        <span>Explorar</span>
        <div class="scroll-chevrons">
            <i class="fas fa-chevron-down"></i>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>

<!-- ===================== STATS BAR ===================== -->
<div class="stats-bar">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <div class="stat-number" data-count="{{ $totalEquipos ?? 0 }}">0</div>
                    <div class="stat-label">Equipos registrados</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <div class="stat-number stat-red" data-count="{{ $totalDisciplinas ?? 0 }}">0</div>
                    <div class="stat-label">Disciplinas activas</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <div class="stat-number" data-count="{{ $totalSeries ?? 0 }}">0</div>
                    <div class="stat-label">Series en curso</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <div class="stat-number stat-red" data-count="{{ $totalPartidos ?? 0 }}">0</div>
                    <div class="stat-label">Partidos jugados</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== QUIENES SOMOS ===================== -->
<section class="section section-quienes" id="quienes">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="section-tag"><i class="fas fa-shield-halved"></i>Nuestra institucion</div>
                <h2 class="section-title">Division de <span class="hl">Becas</span> Deportivas <span class="hl-r">UMSA</span></h2>
                <div class="divider-umsa">
                    <div class="d-line"></div>
                    <div class="d-dot"></div>
                    <div class="d-line right"></div>
                </div>
                <p class="section-lead">
                    Promovemos el desarrollo integral de los estudiantes universitarios a traves del deporte, facilitando su formacion academica mediante un sistema moderno de gestion de eventos y becas deportivas.
                </p>
                <ul class="feature-list mt-4">
                    <li><i class="fas fa-check"></i> Gestion digital</li>
                    <li><i class="fas fa-check"></i> Fixtures automaticos</li>
                    <li><i class="fas fa-check"></i> Resultados en tiempo real</li>
                    <li><i class="fas fa-check"></i> Pre-inscripcion online</li>
                    <li><i class="fas fa-check"></i> Tabla de posiciones</li>
                    <li><i class="fas fa-check"></i> Exportacion en PDF</li>
                </ul>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="info-card-icon">
                                <i class="fas fa-bullseye text-white"></i>
                            </div>
                            <h3>Nuestra Mision</h3>
                            <p>Promover el desarrollo integral de los estudiantes a traves de becas deportivas que faciliten su formacion academica y personal, fomentando la excelencia.</p>
                        </div>
                    </div>
                    <div class="col-sm-6" style="margin-top: 24px;">
                        <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="info-card-icon red">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <h3>Nuestra Vision</h3>
                            <p>Ser reconocidos como lideres en gestion de becas deportivas a nivel nacional, contribuyendo al crecimiento y exito de nuestros estudiantes.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                            <div class="info-card-icon red">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <h3>Comunidad Deportiva</h3>
                            <p>Una comunidad activa de atletas universitarios comprometidos con el deporte y la excelencia academica bajo los colores de la UMSA.</p>
                        </div>
                    </div>
                    <div class="col-sm-6" style="margin-top: 24px;">
                        <div class="info-card" data-aos="fade-up" data-aos-delay="400">
                            <div class="info-card-icon">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <h3>Competencia Universitaria</h3>
                            <p>Organizamos eventos intercarreras, olimpiadas e interauxiliares con sistema automatizado de fixtures y tabla de posiciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wave separator -->
<div class="wave-sep" style="background:#080F18;">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,40 C360,0 1080,80 1440,20 L1440,0 L0,0 Z" fill="#0D1B26"/>
    </svg>
</div>

<!-- ===================== COMUNICADOS ===================== -->
<section class="section section-comunicados" id="comunicados">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-tag mx-auto" style="width: fit-content;">
                <i class="fas fa-bullhorn"></i>Noticias y avisos
            </div>
            <h2 class="section-title">Panel de <span class="hl-r">Comunicados</span></h2>
            <div class="divider-umsa justify-content-center">
                <div class="d-line"></div>
                <div class="d-dot"></div>
                <div class="d-line right"></div>
            </div>
            <p class="section-lead mx-auto text-center">
                Mantente al tanto de los ultimos comunicados, convocatorias y resultados de la Division de Becas Deportivas.
            </p>
        </div>

        @yield('content')
    </div>
</section>

<!-- Wave separator -->
<div class="wave-sep" style="background:#080F18;">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,20 C360,70 1080,-10 1440,40 L1440,60 L0,60 Z" fill="#0D1B26"/>
    </svg>
</div>

<!-- ===================== CTA SECTION ===================== -->
<section class="section-cta" id="inscripcion">
    <div class="cta-box" data-aos="zoom-in">
        <div class="section-tag mx-auto mb-3" style="width:fit-content;">
            <i class="fas fa-rocket"></i>Comienza hoy
        </div>
        <h2>Forma parte de la<br><span style="background:linear-gradient(135deg,var(--umsa-red),#E74C3C);-webkit-background-clip:text;background-clip:text;color:transparent;">competencia universitaria</span></h2>
        <p>Inscribe a tu equipo o participacion individual en los eventos deportivos de la UMSA. El proceso es completamente digital, rapido y sencillo.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="#" class="btn-hero-primary" data-bs-toggle="modal" data-bs-target="#preinscripcionModal">
                <i class="fas fa-pen-to-square"></i> Inscribirme ahora
            </a>
            <a href="{{ route('portal.index') }}" class="btn-hero-secondary">
                <i class="fas fa-chart-bar"></i> Ver clasificaciones
            </a>
        </div>
    </div>
</section>

<!-- ===================== FOOTER ===================== -->
<footer class="site-footer" id="ubicacion">
    <div class="container">
        <div class="row g-5">
            <!-- Brand col -->
            <div class="col-lg-4" data-aos="fade-up">
                <div class="footer-brand-name">BECAS&amp;<span>DEPORTES</span></div>
                <p class="footer-tagline">Division de Becas Deportivas de la Universidad Mayor de San Andres &mdash; El deporte abre las puertas.</p>
                <div>
                    <a href="https://www.facebook.com/becasydeportesumsa/?locale=es_LA" target="_blank" class="footer-social-btn" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://web.whatsapp.com/send?phone=59167062611&text=Hola+tengo+una+consulta" target="_blank" class="footer-social-btn whatsapp" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <button class="footer-social-btn" data-bs-toggle="modal" data-bs-target="#direccion" title="Ubicacion">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Links col -->
            <div class="col-6 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                <div class="footer-col-title">Navegacion</div>
                <a href="#quienes" class="footer-link">Quienes somos</a>
                <a href="#comunicados" class="footer-link">Comunicados</a>
                <a href="{{ route('portal.index') }}" class="footer-link">Resultados</a>
                <a href="#inscripcion" class="footer-link">Pre-inscripcion</a>
                <a href="{{ route('login') }}" class="footer-link">Panel Admin</a>
            </div>

            <!-- Eventos col -->
            <div class="col-6 col-lg-2" data-aos="fade-up" data-aos-delay="150">
                <div class="footer-col-title">Eventos</div>
                <a href="#" class="footer-link">Intercarreras</a>
                <a href="#" class="footer-link">Olimpiadas</a>
                <a href="#" class="footer-link">Interauxiliares</a>
                <a href="{{ route('portal.index') }}" class="footer-link">Tabla de posiciones</a>
            </div>

            <!-- Contact col -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="footer-col-title">Contacto</div>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Federico Zuazo, El Alto &mdash; La Paz, Bolivia</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fab fa-facebook-f"></i>
                    <span>becasydeportesumsa</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <span>+591 67062611</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-university"></i>
                    <span>Universidad Mayor de San Andres &mdash; UMSA</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 DeportBeca UMSA &mdash; Todos los derechos reservados.</span>
            <span style="color: var(--umsa-red); font-weight: 600; font-size: 0.82rem;">
                <i class="fas fa-heart me-1"></i> Hecho con pasion en Bolivia
            </span>
        </div>
    </div>
</footer>

<!-- ===================== MODAL UBICACION ===================== -->
<div class="modal fade" id="direccion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="text-white fw-bold"><i class="fas fa-map-marker-alt me-2" style="color:var(--umsa-red);"></i>Nuestra Ubicacion</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4336977280886!2d-68.12877148835632!3d-16.504188394021998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f206505af984f%3A0xa8cdd0c3fc238d87!2sColiseo%20Universitario!5e0!3m2!1ses!2sbo!4v1775747940032!5m2!1ses!2sbo"
                    width="100%" height="380" style="border:0; border-radius: 14px;" allowfullscreen="" loading="lazy"></iframe>
                <div class="mt-3 d-flex align-items-center gap-2" style="color:var(--text-gray);font-size:.9rem;">
                    <i class="fas fa-location-dot" style="color:var(--umsa-red);"></i>
                    Federico Zuazo, El Alto &mdash; La Paz, Bolivia
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL PRE-INSCRIPCION ===================== -->
<div class="modal fade" id="preinscripcionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,var(--umsa-blue-dark),var(--bg-surface));">
                <div>
                    <h4 class="text-white fw-bold mb-0" id="modalTituloEvento">
                        <i class="fas fa-pen-to-square me-2" style="color:var(--umsa-red);"></i>Pre-inscripcion Deportiva
                    </h4>
                    <p class="mb-0 mt-1" style="color:var(--text-muted);font-size:.82rem;">Ingresa el codigo de acceso proporcionado por el organizador</p>
                </div>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div id="pasoCodigo">
                                <div class="text-center mb-5 mt-3">
                                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--umsa-blue),var(--umsa-blue-mid));display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 8px 32px rgba(26,82,118,0.45);">
                                        <i class="fas fa-key text-white" style="font-size:1.8rem;"></i>
                                    </div>
                                    <h5 class="text-white fw-bold">Ingresa el codigo de acceso</h5>
                                    <p style="color:var(--text-muted);">El codigo fue proporcionado por el organizador del evento</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-7">
                                        <div class="input-group mb-3">
                                            <input type="text" id="codigoAcceso"
                                                class="form-control form-control-lg text-center fw-bold"
                                                placeholder="Ej: IC-ABC123"
                                                style="font-size:1.2rem;letter-spacing:3px;background:var(--bg-card);border:1px solid var(--border);color:var(--text-white);">
                                            <button class="btn btn-lg fw-bold" onclick="window.validarCodigoAcceso()"
                                                style="background:linear-gradient(135deg,var(--umsa-red),var(--umsa-red-dark));color:#fff;border:none;padding:0 24px;">
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                        <div id="errorCodigo" class="alert alert-danger d-none rounded-pill text-center"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="pasoFormulario" style="display:none;">
                                <div id="formularioContainer">
                                    <div class="text-center py-5">
                                        <div class="spinner-border" style="color:var(--umsa-blue);"></div>
                                        <p class="mt-3" style="color:var(--text-gray);">Cargando formulario...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL VERIFICAR ===================== -->
<div class="modal fade" id="verificarEstadoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="text-white fw-bold">Verificar Estado de Inscripcion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="codigoVerificar" class="form-control" placeholder="Ingrese su codigo de inscripcion"
                    style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-white);">
                <button class="btn btn-primary mt-3 w-100 fw-bold"
                    style="background:linear-gradient(135deg,var(--umsa-blue),var(--umsa-blue-dark));border:none;"
                    onclick="verificarEstadoInscripcion()">
                    <i class="fas fa-search me-2"></i>Verificar
                </button>
                <div id="resultadoEstado" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== SCRIPTS ===================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// AOS
AOS.init({ duration: 750, once: true, offset: 80, easing: 'ease-out-cubic' });

// Navbar scroll
window.addEventListener('scroll', function() {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
});

// ---- PARTICLES ----
(function() {
    var container = document.getElementById('particles');
    var colors = ['rgba(26,82,118,0.7)', 'rgba(192,57,43,0.6)', 'rgba(93,173,226,0.5)', 'rgba(255,255,255,0.3)'];
    for (var i = 0; i < 40; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        var size = Math.random() * 5 + 2;
        var left = Math.random() * 100;
        var dur = Math.random() * 15 + 10;
        var delay = Math.random() * 15;
        p.style.cssText = [
            'width:' + size + 'px',
            'height:' + size + 'px',
            'left:' + left + '%',
            'background:' + colors[Math.floor(Math.random() * colors.length)],
            'animation-duration:' + dur + 's',
            'animation-delay:' + delay + 's',
            'border-radius:50%'
        ].join(';');
        container.appendChild(p);
    }
}());

// ---- COUNTER ANIMATION ----
function animateCounter(el, target, dur) {
    var start = 0;
    var step = target / (dur / 16);
    var timer = setInterval(function() {
        start += step;
        if (start >= target) { start = target; clearInterval(timer); }
        el.textContent = Math.floor(start).toLocaleString();
    }, 16);
}

var countersStarted = false;
var observer = new IntersectionObserver(function(entries) {
    if (entries[0].isIntersecting && !countersStarted) {
        countersStarted = true;
        document.querySelectorAll('.stat-number[data-count]').forEach(function(el) {
            animateCounter(el, parseInt(el.dataset.count) || 0, 1800);
        });
    }
}, { threshold: 0.3 });

var statsBar = document.querySelector('.stats-bar');
if (statsBar) observer.observe(statsBar);

// ---- PRE-INSCRIPCION ----
var eventoActual = null;

function abrirModalPreinscripcion() {
    document.getElementById('pasoCodigo').style.display = 'block';
    document.getElementById('pasoFormulario').style.display = 'none';
    document.getElementById('codigoAcceso').value = '';
    document.getElementById('errorCodigo').classList.add('d-none');
    document.getElementById('formularioContainer').innerHTML =
        '<div class="text-center py-5"><div class="spinner-border" style="color:var(--umsa-blue);"></div><p class="mt-3" style="color:var(--text-gray);">Cargando formulario...</p></div>';
    document.getElementById('modalTituloEvento').innerHTML =
        '<i class="fas fa-pen-to-square me-2" style="color:var(--umsa-red);"></i>Pre-inscripcion Deportiva';
    eventoActual = null;
}

window.validarCodigoAcceso = function() {
    var codigo = document.getElementById('codigoAcceso').value.trim();
    var errorDiv = document.getElementById('errorCodigo');

    if (!codigo) {
        errorDiv.textContent = 'Por favor ingrese el codigo de acceso';
        errorDiv.classList.remove('d-none');
        return;
    }
    errorDiv.classList.add('d-none');

    Swal.fire({ title: 'Validando...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });

    fetch('{{ route("preinscripcion.validar.codigo") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ codigo_acceso: codigo })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        Swal.close();
        if (data.success) {
            eventoActual = data.evento;
            document.getElementById('modalTituloEvento').innerHTML =
                '<i class="fas fa-trophy me-2" style="color:var(--umsa-red);"></i>' + data.evento.nombre;
            cargarFormularioPreinscripcion(data.evento);
        } else {
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(function() {
        Swal.close();
        errorDiv.textContent = 'Error al validar el codigo.';
        errorDiv.classList.remove('d-none');
    });
};

function cargarFormularioPreinscripcion(evento) {
    var container = document.getElementById('formularioContainer');
    container.innerHTML = '<div class="text-center py-5"><div class="spinner-border" style="color:var(--umsa-blue);"></div></div>';

    fetch('{{ route("preinscripcion.formulario.modal") }}?tipo_evento=' + evento.tipo_evento)
        .then(function(r) { return r.text(); })
        .then(function(html) {
            container.innerHTML = html;
            document.getElementById('pasoCodigo').style.display = 'none';
            document.getElementById('pasoFormulario').style.display = 'block';
            inicializarEventosFormulario(evento);
        })
        .catch(function() {
            container.innerHTML = '<div class="alert alert-danger">Error al cargar el formulario</div>';
        });
}

function inicializarEventosFormulario(evento) {
    function handleTipoChange() {
        var tipoSelect = document.getElementById('tipo_inscripcion_select');
        var seccionGrupal = document.getElementById('seccionGrupal');
        var seccionIndividual = document.getElementById('seccionIndividual');
        var integrantesContainer = document.getElementById('integrantesContainer');
        var hiddenInput = document.getElementById('tipo_inscripcion_hidden');
        var facultadGrupal = document.getElementById('facultadGrupal');
        var nombreEquipo = document.querySelector('input[name="nombre_equipo"]');
        var carreraSelect = document.querySelector('select[name="carrera_id"]');
        if (!tipoSelect) return;
        if (tipoSelect.value === 'individual') {
            if (seccionGrupal) seccionGrupal.style.display = 'none';
            if (integrantesContainer) integrantesContainer.style.display = 'none';
            if (facultadGrupal) facultadGrupal.style.display = 'none';
            if (seccionIndividual) seccionIndividual.style.display = 'block';
            if (hiddenInput) hiddenInput.value = 'individual';
            if (nombreEquipo) nombreEquipo.required = false;
            if (carreraSelect) carreraSelect.required = false;
        } else {
            if (seccionGrupal) seccionGrupal.style.display = 'block';
            if (integrantesContainer) integrantesContainer.style.display = 'block';
            if (facultadGrupal) facultadGrupal.style.display = 'block';
            if (seccionIndividual) seccionIndividual.style.display = 'none';
            if (hiddenInput) hiddenInput.value = 'grupal';
            if (nombreEquipo) nombreEquipo.required = true;
            if (carreraSelect) carreraSelect.required = true;
            generarIntegrantes();
        }
    }

    function generarIntegrantes() {
        var cantidad = parseInt(document.getElementById('cantidadIntegrantes')?.value) || 2;
        var container = document.getElementById('integrantesContainer');
        if (!container) return;
        container.innerHTML = '';
        for (var i = 2; i <= cantidad; i++) {
            container.innerHTML += '<div class="card mt-3 p-3" style="background:var(--bg-card);border:1px solid var(--border);">'
                + '<strong class="text-white mb-2 d-block"><i class="fas fa-user"></i> INTEGRANTE ' + i + '</strong>'
                + '<div class="row"><div class="col-md-6 mb-2"><input type="text" name="integrantes[' + i + '][nombre]" class="form-control integrante-input" placeholder="Nombre completo"></div>'
                + '<div class="col-md-6 mb-2"><input type="text" name="integrantes[' + i + '][ci]" class="form-control integrante-input" placeholder="Cedula de Identidad"></div></div>'
                + '<hr style="border-color:var(--border);">'
                + '<h6 class="text-white">Documentos del Integrante ' + i + '</h6>'
                + '<div class="row"><div class="col-md-4 mb-2"><label class="text-white small">Cedula *</label>'
                + '<input type="file" name="integrantes[' + i + '][documento_ci]" class="form-control integrante-file" accept=".jpg,.jpeg,.png,.pdf"></div>'
                + '<div class="col-md-4 mb-2"><label class="text-white small">Seguro *</label>'
                + '<input type="file" name="integrantes[' + i + '][documento_seguro]" class="form-control integrante-file" accept=".jpg,.jpeg,.png,.pdf"></div>'
                + '<div class="col-md-4 mb-2"><label class="text-white small">Matricula *</label>'
                + '<input type="file" name="integrantes[' + i + '][documento_matricula]" class="form-control integrante-file" accept=".jpg,.jpeg,.png,.pdf"></div></div></div>';
        }
        var tipoSelect = document.getElementById('tipo_inscripcion_select');
        document.querySelectorAll('.integrante-input, .integrante-file').forEach(function(el) {
            el.required = tipoSelect?.value === 'grupal';
        });
    }

    var tipoSelect = document.getElementById('tipo_inscripcion_select');
    var cantidadInput = document.getElementById('cantidadIntegrantes');
    if (tipoSelect) {
        tipoSelect.removeEventListener('change', handleTipoChange);
        tipoSelect.addEventListener('change', handleTipoChange);
        setTimeout(handleTipoChange, 100);
    }
    if (cantidadInput) {
        cantidadInput.removeEventListener('change', generarIntegrantes);
        cantidadInput.addEventListener('change', function() {
            if (tipoSelect?.value === 'grupal') generarIntegrantes();
        });
        if (tipoSelect?.value === 'grupal') generarIntegrantes();
    }

    var form = document.getElementById('preinscripcionForm');
    if (form && !form.hasAttribute('data-listener-added')) {
        form.setAttribute('data-listener-added', 'true');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var tipoInscripcion = document.getElementById('tipo_inscripcion_select')?.value;
            var formData = new FormData(this);
            if (tipoInscripcion === 'individual') {
                var fac = document.querySelector('select[name="facultad_id_individual"]')?.value;
                var car = document.querySelector('select[name="carrera_id_individual"]')?.value;
                if (evento.tipo_evento === 'olimpiadas' && fac) formData.set('facultad_id', fac);
                else if (evento.tipo_evento === 'intercarreras' && car) formData.set('carrera_id', car);
                formData.delete('nombre_equipo');
                formData.delete('cantidad_integrantes');
                formData.delete('carrera_id');
                formData.delete('integrantes');
            }
            Swal.fire({ title: 'Enviando...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            fetch('{{ route("preinscripcion.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                Swal.close();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Inscripcion exitosa', html: data.message + '<br><strong>Codigo: ' + data.codigo + '</strong>' });
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('preinscripcionModal'));
                        if (modal) modal.hide();
                        abrirModalPreinscripcion();
                    }, 3000);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error al enviar la pre-inscripcion' });
            });
        });
    }
}

function verificarEstadoInscripcion() {
    var codigo = document.getElementById('codigoVerificar')?.value.trim();
    var resultDiv = document.getElementById('resultadoEstado');
    if (!codigo) { resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese un codigo</div>'; return; }
    resultDiv.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm" style="color:var(--umsa-blue);"></div></div>';
    fetch('/preinscripcion/verificar/' + codigo)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var e = data.data;
                var badge = e.estado === 'habilitado' ? 'success' : (e.estado === 'observado' ? 'warning' : 'secondary');
                resultDiv.innerHTML = '<div class="alert alert-' + badge + '"><strong>Estado:</strong> ' + e.estado + '<br><strong>Codigo:</strong> ' + e.codigo_inscripcion + '</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
        })
        .catch(function() { resultDiv.innerHTML = '<div class="alert alert-danger">Error al verificar</div>'; });
}

document.addEventListener('DOMContentLoaded', function() {
    var preModal = document.getElementById('preinscripcionModal');
    if (preModal) preModal.addEventListener('show.bs.modal', abrirModalPreinscripcion);

    // Enter key on codigo input
    var codigoInput = document.getElementById('codigoAcceso');
    if (codigoInput) {
        codigoInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') window.validarCodigoAcceso();
        });
    }
});
</script>
</body>
</html>
