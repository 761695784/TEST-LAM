<!DOCTYPE html>
<html lang="fr">
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
        /*  Variables  */
        :root {
            --bg-base:       #0a0d14;
            --bg-surface:    #111520;
            --bg-card:       #161b2e;
            --bg-card-hover: #1c2340;
            --border:        #232a42;
            --border-light:  #2e3756;

            --accent:        #e8500a;
            --accent-soft:   rgba(232,80,10,.12);
            --accent-glow:   rgba(232,80,10,.35);

            --teal:          #0fcfb0;
            --teal-soft:     rgba(15,207,176,.12);

            --text-primary:  #eef0f8;
            --text-secondary:#8a93b5;
            --text-muted:    #4d5880;

            --sidebar-w:     260px;
            --topbar-h:      64px;

            --radius:        12px;
            --radius-sm:     8px;
            --shadow:        0 4px 24px rgba(0,0,0,.4);
        }

        /*  Reset & Base  */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6,
        .font-display { font-family: 'Syne', sans-serif; }

        /*  Sidebar  */
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
            transition: transform .3s ease;
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
            display: flex;
            align-items: center;
            justify-content: center;
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
            background: var(--bg-card);
        }

        .nav-link.active {
            color: var(--accent);
            background: var(--accent-soft);
        }

        .nav-link .badge-count {
            margin-left: auto;
            background: var(--border-light);
            color: var(--text-secondary);
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        /*  Topbar  */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(10,13,20,.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            z-index: 999;
        }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 18px;
            flex: 1;
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

        /*  Main content  */
        .main-wrap {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-content {
            padding: 28px;
            max-width: 1400px;
        }

        /*  Cards  */
        .lam-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
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

        /* Stat cards  */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: border-color .2s, transform .2s;
        }

        .stat-card:hover {
            border-color: var(--border-light);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /*  Buttons  */
        .btn-lam {
            background: var(--accent);
            color: #fff;
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
            color: #fff;
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
            border-color: var(--border-light);
        }

        .btn-lam-danger {
            background: rgba(239,68,68,.12);
            color: #ef4444;
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

        .btn-lam-danger:hover {
            background: rgba(239,68,68,.22);
            color: #ef4444;
            border-color: rgba(239,68,68,.35);
        }

        /*  Forms  */
        .lam-input {
            background: var(--bg-base);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 14px;
            padding: 10px 14px;
            width: 100%;
            transition: border-color .2s, box-shadow .2s;
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

        select.lam-input option { background: var(--bg-surface); }

        textarea.lam-input { resize: vertical; min-height: 100px; }

        .form-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /*  Badges statuts campagne  */
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

        .badge-draft     { background: rgba(100,116,139,.15); color: #94a3b8; }
        .badge-scheduled { background: rgba(59,130,246,.15);  color: #60a5fa; }
        .badge-running   { background: rgba(15,207,176,.15);  color: var(--teal); animation: pulse-badge 2s infinite; }
        .badge-completed { background: rgba(34,197,94,.15);   color: #4ade80; }
        .badge-cancelled { background: rgba(239,68,68,.15);   color: #f87171; }

        @keyframes pulse-badge {
            0%, 100% { opacity: 1; }
            50% { opacity: .65; }
        }

        /*  Badges statuts appel  */
        .badge-call {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-pending   { background: rgba(251,191,36,.12); color: #fbbf24; }
        .badge-sent      { background: rgba(59,130,246,.12); color: #60a5fa; }
        .badge-answered  { background: rgba(34,197,94,.12);  color: #4ade80; }
        .badge-failed    { background: rgba(239,68,68,.12);  color: #f87171; }
        .badge-no_answer { background: rgba(100,116,139,.12);color: #94a3b8; }

        /*  Table  */
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

        .lam-table td.td-primary {
            color: var(--text-primary);
            font-weight: 500;
        }

        /*  Filter bar  */
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
        }

        .filter-bar .lam-input { max-width: 280px; }

        /* Pagination  */
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

        /*  Alerts  */
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
            color: #4ade80;
        }

        .lam-alert-error {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.25);
            color: #f87171;
        }

        .lam-alert-info {
            background: rgba(59,130,246,.1);
            border: 1px solid rgba(59,130,246,.25);
            color: #60a5fa;
        }

        /*  Empty state  */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .empty-state p { font-size: 14px; color: var(--text-muted); }

        /*  Overlay sidebar mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 999;
        }

        /*  Animations */
        .fade-in {
            animation: fadeIn .4s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /*  Responsive  */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

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
</head>
<body>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar  -->
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
           class="nav-link {{ request()->routeIs('campagnes.*') && !request()->routeIs('campagnes.index') ? '' : '' }}">
            <i class="bi bi-megaphone"></i>
            Campagnes
        </a>

        <a href="{{ route('campagnes.create') }}"
           class="nav-link {{ request()->routeIs('campagnes.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            Nouvelle campagne
        </a>

        <div class="nav-section-label" style="margin-top:16px;">Filtres rapides</div>

        @foreach(['DRAFT' => ['Brouillons','bi-pencil-square','badge-draft'], 'SCHEDULED' => ['Planifiées','bi-calendar-event','badge-scheduled'], 'RUNNING' => ['En cours','bi-play-circle','badge-running'], 'COMPLETED' => ['Terminées','bi-check-circle','badge-completed'], 'CANCELLED' => ['Annulées','bi-x-circle','badge-cancelled']] as $statut => [$label, $icon, $badge])
        <a href="{{ route('campagnes.index', ['statut' => $statut]) }}"
           class="nav-link {{ request('statut') === $statut ? 'active' : '' }}">
            <i class="bi {{ $icon }}"></i>
            {{ $label }}
        </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div>Version 1.0.0</div>
        <div style="color: var(--teal); margin-top:4px;">● Système actif</div>
    </div>
</aside>

<!--  Topbar  -->
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

<!-- Contenu principal -->
<main class="main-wrap">
    <div class="page-content fade-in">

        {{-- Alertes flash --}}
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
    // Sidebar mobile
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const burger   = document.getElementById('burgerBtn');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    burger?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // Auto-dismiss alerts
    document.querySelectorAll('.lam-alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .4s, transform .4s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>
