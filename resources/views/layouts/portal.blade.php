<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal de Resultados') | DeportBeca</title>

    <!-- Favicon -->
    <link href="{{ asset('img/brand/logos.jpg') }}" rel="icon" type="image/png">
    <!-- Font Awesome -->
    <link href="{{ asset('js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --red: #dc2626;
            --red-dark: #b91c1c;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --border: #334155;
            --text-white: #f8fafc;
            --text-gray: #94a3b8;
            --green: #10b981;
            --yellow: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
        }

        /* Navbar */
        .portal-nav {
            background: rgba(15, 23, 42, 0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .portal-nav .brand {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--red) 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
        }

        .portal-nav .nav-links a {
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .portal-nav .nav-links a:hover,
        .portal-nav .nav-links a.active {
            color: var(--text-white);
            background: rgba(255,255,255,0.08);
        }

        .portal-nav .nav-links a.btn-red {
            background: var(--red);
            color: white;
        }

        .portal-nav .nav-links a.btn-red:hover {
            background: var(--red-dark);
        }

        /* Page content */
        .portal-content {
            min-height: calc(100vh - 130px);
            padding: 32px 0;
        }

        /* Card estilo portal */
        .p-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.25s;
        }

        .p-card:hover {
            border-color: var(--red);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        /* Badge estados */
        .badge-pendiente  { background: #334155; color: var(--text-gray); }
        .badge-en-curso   { background: rgba(16,185,129,0.15); color: var(--green); border: 1px solid rgba(16,185,129,0.3); }
        .badge-finalizado { background: rgba(220,38,38,0.15); color: #f87171; border: 1px solid rgba(220,38,38,0.3); }

        /* Footer */
        .portal-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border);
            padding: 20px 0;
            text-align: center;
            color: var(--text-gray);
            font-size: 0.8rem;
        }

        @yield('extra-styles')
    </style>

    @yield('styles')
</head>
<body>

<!-- Navbar -->
<nav class="portal-nav">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('portal.index') }}" class="brand">
            <i class="fas fa-trophy mr-1"></i> DeportBeca
        </a>
        <div class="nav-links d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('portal.index') }}" {{ request()->routeIs('portal.index') ? 'class=active' : '' }}>
                <i class="fas fa-home"></i> Eventos
            </a>
            <a href="{{ url('/') }}">
                <i class="fas fa-newspaper"></i> Comunicados
            </a>
            <a href="{{ route('preinscripcion.verificar.form') }}" class="btn-red">
                <i class="fas fa-qrcode"></i> Verificar inscripcion
            </a>
            @auth
            <a href="{{ route('home') }}">
                <i class="fas fa-columns"></i> Panel
            </a>
            @else
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Content -->
<main class="portal-content">
    <div class="container">
        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="portal-footer">
    <span>DeportBeca &mdash; División de Becas de Deportes &copy; {{ date('Y') }}</span>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
</body>
</html>
