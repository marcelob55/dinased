<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Sistema Casos DINASED</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <nav>
    <a href="{{ route('casos.index') }}">Casos</a>
    <a href="{{ route('casos.create') }}">Nuevo</a>
  </nav>
  <main class="container">
    @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
    @yield('content')
  </main>
</body>
</html>
