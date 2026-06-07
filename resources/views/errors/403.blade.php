<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso denegado | DeportBeca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #1A5276 0%, #0e3550 100%);
            padding: 24px;
        }
        .card {
            background: #fff; color: #1f2d3d; border-radius: 20px;
            padding: 48px 40px; max-width: 480px; width: 100%; text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .icon {
            width: 90px; height: 90px; border-radius: 50%;
            background: rgba(192,57,43,.12); color: #C0392B;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
        }
        h1 { font-size: 64px; color: #C0392B; line-height: 1; margin-bottom: 4px; }
        h2 { font-size: 22px; margin-bottom: 12px; color: #1A5276; }
        p { color: #5a6b7b; font-size: 15px; line-height: 1.6; margin-bottom: 28px; }
        .btn {
            display: inline-block; background: linear-gradient(135deg, #1A5276, #0e3550);
            color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 10px;
            font-weight: 600; font-size: 14px; transition: transform .15s, box-shadow .15s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(26,82,118,.4); }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
            </svg>
        </div>
        <h1>403</h1>
        <h2>Acceso denegado</h2>
        <p>{{ $exception->getMessage() ?: 'No tienes permiso para acceder a esta seccion. Si crees que es un error, contacta al administrador del sistema.' }}</p>
        <a href="{{ url('/home') }}" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
