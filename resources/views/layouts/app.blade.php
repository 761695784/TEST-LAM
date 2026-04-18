<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LAM Voice') — L'AfricaMobile</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /*
           THÈME SOMBRE (défaut)
     */
        [data-theme="dark"] {
            --bg-base:        #0a0d14;
            --bg-surface:     #111520;
            --bg-card:        #161b2e;
            --bg-card-hover:  #1c2340;
            --border:         #232a42;
            --border-light:   #2e3756;
            --accent:         #e8500a;
            --accent-soft:    rgba(232,80,10,.12);
            --accent-glow:    rgba(232,80,10,.35);
            --teal:           #0fcfb0;
            --text-primary:   #eef0f8;
            --text-secondary: #8a93b5;
            --text-muted:     #4d5880;
            --shadow:         0 4px 24px rgba(0,0,0,.45);
            --shadow-sm:      0 2px 10px rgba(0,0,0,.3);
            --toggle-bg:      #1c2340;
            --toggle-border:  #2e3756;
            /* badges sombres */
            --badge-draft-color:     #94a3b8;
            --badge-scheduled-color: #60a5fa;
            --badge-completed-color: #4ade80;
            --badge-cancelled-color: #f87171;
            --badge-pending-color:   #fbbf24;
            --badge-sent-color:      #60a5fa;
            --badge-answered-color:  #4ade80;
            --badge-failed-color:    #f87171;
            --badge-noanswer-color:  #94a3b8;
            --alert-success-color:   #4ade80;
            --alert-error-color:     #f87171;
            --alert-info-color:      #60a5fa;
        }

        /*
           THÈME CLAIR
     */
        [data-theme="light"] {
            --bg-base:        #f1f5fb;
            --bg-surface:     #ffffff;
            --bg-card:        #ffffff;
            --bg-card-hover:  #eef2fb;
            --border:         #e2e8f4;
            --border-light:   #d0d9ed;
            --accent:         #e8500a;
            --accent-soft:    rgba(232,80,10,.09);
            --accent-glow:    rgba(232,80,10,.22);
            --teal:           #0aaa95;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --shadow:         0 4px 24px rgba(15,23,42,.09);
            --shadow-sm:      0 2px 10px rgba(15,23,42,.06);
            --toggle-bg:      #e8edf8;
            --toggle-border:  #d0d9ed;
            /* badges clairs (plus sombres pour lisibilité) */
            --badge-draft-color:     #64748b;
            --badge-scheduled-color: #2563eb;
            --badge-completed-color: #16a34a;
            --badge-cancelled-color: #dc2626;
            --badge-pending-color:   #d97706;
            --badge-sent-color:      #2563eb;
            --badge-answered-color:  #16a34a;
            --badge-failed-color:    #dc2626;
            --badge-noanswer-color:  #64748b;
            --alert-success-color:   #16a34a;
            --alert-error-color:     #dc2626;
            --alert-info-color:      #2563eb;
        }

        /*
           VARIABLES FIXES
     */
        :root {
            --sidebar-w: 260px;
            --topbar-h:  64px;
            --radius:    12px;
            --radius-sm: 8px;
        }

        /*
           BASE
     */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background .3s, color .3s;
        }

        h1,h2,h3,h4,h5,h6 { font-family: 'Syne', sans-serif; }

        /*
           SIDEBAR
     */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease, background .3s, border-color .3s;
        }

        .sidebar-logo {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 0 16px var(--accent-glow);
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 18px;
            color: var(--text-primary);
            letter-spacing: -.3px;
            line-height: 1.1;
        }

        .logo-text span {
            display: block;
            font-size: 10px;
            font-weight: 400;
            color: var(--text-secondary);
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 8px 12px 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            margin-bottom: 2px;
        }

        .nav-link i { font-size: 16px; }

        .nav-link:hover {
            color: var(--text-primary);
            background: var(--bg-card-hover);
        }

        .nav-link.active {
            color: var(--accent);
            background: var(--accent-soft);
        }

        /*
           TOGGLE DARK / LIGHT (dans le footer sidebar)
     */
        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
        }

        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--toggle-bg);
            border: 1px solid var(--toggle-border);
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            width: 100%;
            transition: border-color .2s, background .2s;
            margin-bottom: 10px;
        }

        .theme-toggle:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .theme-toggle-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .theme-toggle-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        /* Piste du switch */
        .switch-track {
            position: relative;
            width: 40px;
            height: 22px;
            border-radius: 20px;
            transition: background .3s;
            flex-shrink: 0;
        }

        [data-theme="dark"]  .switch-track { background: var(--accent); }
        [data-theme="light"] .switch-track { background: #cbd5e1; }

        /* Pouce du switch */
        .switch-thumb {
            position: absolute;
            top: 3px;
            width: 16px; height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: left .3s cubic-bezier(.34,1.56,.64,1);
            box-shadow: 0 1px 4px rgba(0,0,0,.25);
        }

        [data-theme="dark"]  .switch-thumb { left: 3px; }
        [data-theme="light"] .switch-thumb { left: 21px; }

        .sidebar-version {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
        }

        .status-dot { color: var(--teal); }

        /*
           TOPBAR
     */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            z-index: 999;
            transition: background .3s, border-color .3s, left .3s;
        }

        [data-theme="dark"] .topbar { background: rgba(10,13,20,.9); backdrop-filter: blur(10px); }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 18px;
            flex: 1;
            color: var(--text-primary);
        }

        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        .burger-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 22px;
            cursor: pointer;
            padding: 4px;
        }

        /*
           MAIN
     */
        .main-wrap {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-content {
            padding: 28px;
            max-width: 1400px;
        }

        /*
           CARDS
     */
        .lam-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: background .3s, border-color .3s;
        }

        .lam-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .lam-card-body { padding: 24px; }

        /*
           STAT CARDS
     */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: border-color .2s, transform .2s, background .3s;
            box-shadow: var(--shadow-sm);
        }

        .stat-card:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
        }

        /*
           BOUTONS
     */
        .btn-lam {
            background: var(--accent);
            color: #fff !important;
            border: none;
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .btn-lam:hover {
            background: #d4460a;
            box-shadow: 0 4px 16px var(--accent-glow);
            transform: translateY(-1px);
        }

        .btn-lam-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-lam-ghost:hover {
            color: var(--text-primary);
            background: var(--bg-card-hover);
        }

        .btn-lam-danger {
            background: rgba(239,68,68,.1);
            color: #dc2626;
            border: 1px solid rgba(239,68,68,.2);
            border-radius: var(--radius-sm);
            padding: 7px 14px;
            font-size: 13px;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        [data-theme="dark"] .btn-lam-danger { color: #f87171; }

        .btn-lam-danger:hover {
            background: rgba(239,68,68,.18);
            border-color: rgba(239,68,68,.35);
        }

        /*
           FORMULAIRES
     */
        .lam-input {
            background: var(--bg-base);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 14px;
            padding: 10px 14px;
            width: 100%;
            transition: border-color .2s, box-shadow .2s, background .3s, color .3s;
            font-family: 'DM Sans', sans-serif;
        }

        .lam-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
            background: var(--bg-base);
            color: var(--text-primary);
        }

        .lam-input::placeholder { color: var(--text-muted); }

        .lam-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        select.lam-input option {
            background: var(--bg-surface);
            color: var(--text-primary);
        }

        textarea.lam-input { resize: vertical; min-height: 100px; }

        .form-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        [data-theme="dark"] .form-error { color: #f87171; }

        /*
           BADGES STATUTS CAMPAGNE
     */
        .badge-statut {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .badge-statut::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-draft     { background: rgba(100,116,139,.12); color: var(--badge-draft-color); }
        .badge-scheduled { background: rgba(59,130,246,.12);  color: var(--badge-scheduled-color); }
        .badge-running   { background: rgba(15,207,176,.12);  color: var(--teal); animation: pulse-badge 2s infinite; }
        .badge-completed { background: rgba(34,197,94,.12);   color: var(--badge-completed-color); }
        .badge-cancelled { background: rgba(239,68,68,.12);   color: var(--badge-cancelled-color); }

        @keyframes pulse-badge {
            0%,100% { opacity:1; }
            50%      { opacity:.6; }
        }

        /*
           BADGES STATUTS APPEL
     */
        .badge-call {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-pending   { background: rgba(245,158,11,.12); color: var(--badge-pending-color); }
        .badge-sent      { background: rgba(59,130,246,.12); color: var(--badge-sent-color); }
        .badge-answered  { background: rgba(34,197,94,.12);  color: var(--badge-answered-color); }
        .badge-failed    { background: rgba(239,68,68,.12);  color: var(--badge-failed-color); }
        .badge-no_answer { background: rgba(100,116,139,.12);color: var(--badge-noanswer-color); }

        /*
           TABLE
     */
        .lam-table { width: 100%; border-collapse: collapse; }

        .lam-table thead th {
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            background: var(--bg-card);
        }

        .lam-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .lam-table tbody tr:last-child { border-bottom: none; }
        .lam-table tbody tr:hover { background: var(--bg-card-hover); }

        .lam-table td {
            padding: 14px 16px;
            font-size: 14px;
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .lam-table td.td-primary { color: var(--text-primary); font-weight: 500; }

        /*
           FILTRE BAR
     */
        .filter-bar {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .filter-bar .lam-input { max-width: 280px; }

        /*
           PAGINATION
     */
        .pagination { gap: 4px; }

        .page-link {
            background: var(--bg-card) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-secondary) !important;
            border-radius: var(--radius-sm) !important;
            padding: 6px 12px !important;
            font-size: 13px;
            transition: all .2s;
        }

        .page-link:hover {
            background: var(--bg-card-hover) !important;
            color: var(--text-primary) !important;
        }

        .page-item.active .page-link {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }

        .page-item.disabled .page-link { opacity: .4; }

        /*
           ALERTES FLASH
     */
        .lam-alert {
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .lam-alert-success {
            background: rgba(34,197,94,.1);
            border: 1px solid rgba(34,197,94,.25);
            color: var(--alert-success-color);
        }

        .lam-alert-error {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.25);
            color: var(--alert-error-color);
        }

        .lam-alert-info {
            background: rgba(59,130,246,.1);
            border: 1px solid rgba(59,130,246,.25);
            color: var(--alert-info-color);
        }

        /*
           EMPTY STATE
     */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: var(--text-muted); margin-bottom: 16px; display: block; }
        .empty-state h3 { font-size: 18px; color: var(--text-secondary); margin-bottom: 8px; }
        .empty-state p { font-size: 14px; color: var(--text-muted); }

        /*
           OVERLAY MOBILE
     */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 999;
        }

        /*
           ANIMATIONS
     */
        .fade-in {
            animation: fadeIn .35s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /*
           RESPONSIVE
     */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .topbar { left: 0; }
            .burger-btn { display: flex; }
            .main-wrap { margin-left: 0; }
            .page-content { padding: 16px; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .filter-bar .lam-input { max-width: 100%; }
        }
    </style>

    @stack('styles')

    {{--
        Script inline exécuté AVANT le rendu du body pour éviter
        le flash blanc → dark ou dark → blanc au chargement de page.
    --}}
    <script>
        (function() {
            const theme = localStorage.getItem('lam-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">📡</div>
        <div class="logo-text">
            LAM Voice
            <span>L'AfricaMobile</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Navigation</div>

        <a href="{{ route('campagnes.index') }}"
           class="nav-link {{ request()->routeIs('campagnes.index') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            Tableau de bord
        </a>

        <a href="{{ route('campagnes.index') }}"
           class="nav-link {{ request()->routeIs('campagnes.show') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i>
            Campagnes
        </a>

        <a href="{{ route('campagnes.create') }}"
           class="nav-link {{ request()->routeIs('campagnes.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            Nouvelle campagne
        </a>

        <div class="nav-section-label" style="margin-top:16px;">Filtres rapides</div>

        @foreach([
            'DRAFT'     => ['Brouillons', 'bi-pencil-square'],
            'SCHEDULED' => ['Planifiées', 'bi-calendar-event'],
            'RUNNING'   => ['En cours',   'bi-play-circle'],
            'COMPLETED' => ['Terminées',  'bi-check-circle'],
            'CANCELLED' => ['Annulées',   'bi-x-circle'],
        ] as $statut => [$label, $icon])
        <a href="{{ route('campagnes.index', ['statut' => $statut]) }}"
           class="nav-link {{ request('statut') === $statut ? 'active' : '' }}">
            <i class="bi {{ $icon }}"></i>
            {{ $label }}
        </a>
        @endforeach
    </nav>

    <!--  Footer sidebar  -->
    <div class="sidebar-footer">

        <!-- Bouton basculer thème -->
        <button class="theme-toggle" id="themeToggle" onclick="basculerTheme()">
            <div class="theme-toggle-left">
                <span id="themeIcon" style="font-size:16px;"></span>
                <span id="themeLabel" class="theme-toggle-label"></span>
            </div>
            <!-- Switch visuel -->
            <div class="switch-track">
                <div class="switch-thumb"></div>
            </div>
        </button>

    </div>
</aside>

<!--TOPBAR -->
<header class="topbar">
    <button class="burger-btn" id="burgerBtn">
        <i class="bi bi-list"></i>
    </button>

    <div class="topbar-title">@yield('page-title', 'Tableau de bord')</div>

    <div class="topbar-actions">
        @yield('topbar-actions')
        <a href="{{ route('campagnes.create') }}" class="btn-lam d-none d-md-inline-flex">
            <i class="bi bi-plus-lg"></i> Nouvelle campagne
        </a>
    </div>
</header>

<!--  CONTENU -->
<main class="main-wrap">
    <div class="page-content fade-in">

        @if(session('succes'))
            <div class="lam-alert lam-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('succes') }}
            </div>
        @endif

        @if(session('erreur'))
            <div class="lam-alert lam-alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('erreur') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    //  GESTION DU THÈME DARK / LIGHT

    /**
     * Met à jour l'icône et le texte du bouton selon le thème actif.
     */
    function mettreAJourToggle(theme) {
        const icon  = document.getElementById('themeIcon');
        const label = document.getElementById('themeLabel');

        if (theme === 'dark') {
            label.textContent = 'Mode clair';
        } else {
            label.textContent = 'Mode sombre';
        }
    }

    /** Bascule entre dark et light, persiste dans localStorage */
    function basculerTheme() {
        const html    = document.documentElement;
        const actuel  = html.getAttribute('data-theme');
        const nouveau = actuel === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', nouveau);
        localStorage.setItem('lam-theme', nouveau);
        mettreAJourToggle(nouveau);
    }

    // Initialise le toggle dès que le DOM est prêt
    (function() {
        const theme = localStorage.getItem('lam-theme') || 'dark';
        mettreAJourToggle(theme);
    })();

    //  SIDEBAR MOBILE

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const burger  = document.getElementById('burgerBtn');

    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('show'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); }

    burger?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);

    //  AUTO-DISMISS ALERTES FLASH

    document.querySelectorAll('.lam-alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity    = '0';
            el.style.transform  = 'translateY(-6px)';
            setTimeout(() => el.remove(), 420);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>
