<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Sistema de Casos DINASED')</title>

  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
  @stack('styles')
</head>
<body>
  {{-- ===== Header ===== --}}
  <header class="site-header">
    <div class="brand">
      <img src="{{ asset('assets/img/escudo-policia.jpg') }}" alt="Escudo Policía" class="logo">
      <img src="{{ asset('assets/img/dinased.jpg') }}" alt="DINASED" class="logo logo--right">
      <div class="brand__text">
        <span class="brand__title">
          DIRECCIÓN NACIONAL DE INVESTIGACIÓN DE MUERTES VIOLENTAS Y DESAPARECIDOS
        </span>
      </div>
    </div>

    {{-- Menú principal --}}
    <nav class="header-actions">
      @auth
        <a href="{{ route('casos.index') }}"
           class="btn btn--link {{ request()->routeIs('casos.index') ? 'is-active' : '' }}">Casos</a>

        <a href="{{ route('casos.create') }}"
           class="btn btn--link {{ request()->routeIs('casos.create') ? 'is-active' : '' }}">Nuevo caso</a>

        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn btn--pill btn--outline">Salir</button>
        </form>
      @endauth

      @guest
        <a class="btn btn--pill" href="{{ route('casos.index') }}">
          <span class="i i-home"></span> Inicio
        </a>
      @endguest
    </nav>
  </header>

  {{-- ===== Contenedor principal ===== --}}
  <main class="container">

    {{-- Mensajes flash / errores globales (opcionales, pero muy útiles) --}}
    @if (session('ok'))
      <div class="alert alert--success">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert--danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert--danger">
        <strong>Revisa:</strong>
        <ul style="margin: .5rem 0 0 1rem;">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>

  {{-- ===== Footer ===== --}}
  <footer class="site-footer">
    <small>© {{ date('Y') }} DINASED — Sistema de Casos</small>
  </footer>

  <script src="{{ asset('assets/js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
