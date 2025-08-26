<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Sistema Casos DINASED</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root { --mxw: 1100px; }
    * { box-sizing: border-box; }
    body { font-family: system-ui, Arial, sans-serif; margin:0; color:#111; }
    header { background:#f7f7f7; border-bottom:1px solid #e5e5e5; }
    .wrap { max-width: var(--mxw); margin:0 auto; padding:14px 16px; }

    nav a { margin-right: 12px; color:#0366d6; text-decoration:none; }
    nav a:hover { text-decoration:underline; }
    nav form { display:inline; }

    main.wrap { padding-top:18px; padding-bottom:28px; }

    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd; padding:8px; }
    th { background:#f5f5f5; text-align:left; }
    .alert { background:#e7f7ec; border:1px solid #b8e0c6; color:#155724; padding:8px 12px; margin:12px 0; border-radius:6px; }

    label { display:block; margin-top:10px; }
    input, textarea, select { width:100%; padding:8px; border:1px solid #cfcfcf; border-radius:6px; }
    button { margin-top:12px; padding:10px 16px; cursor:pointer; border:1px solid #cfcfcf; border-radius:8px; background:#fff; }
    button:hover { background:#f6f6f6; }
  </style>

  {{-- Estilos que empujan las vistas (Leaflet CSS, etc.) --}}
  @stack('styles')
</head>
<body>

  <header>
    <div class="wrap">
      <nav>
        <a href="{{ route('casos.index') }}">Casos</a>

        @auth
          @if(auth()->user()->rol === 'generador')
            <a href="{{ route('casos.create') }}">Nuevo caso</a>
          @endif

          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Salir</button>
          </form>
        @endauth
      </nav>
    </div>
  </header>

  <main class="wrap">
    @if(session('ok'))
      <div class="alert">{{ session('ok') }}</div>
    @endif

    @yield('content')
  </main>

  {{-- Scripts que empujan las vistas (Leaflet JS, etc.) --}}
  @stack('scripts')
</body>
</html>
