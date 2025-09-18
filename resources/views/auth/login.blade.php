@extends('layouts.app')
@section('title','Ingreso al sistema')

@section('content')
<div class="auth">
  <div class="card auth__card">
    <h1 class="h3">Ingreso al sistema</h1>
    @if ($errors->any())
      <div class="alert alert--error">
        <strong>Revisa:</strong>
        <ul>
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif



<form method="POST" action="{{ route('login') }}" class="form">
  @csrf

  <label class="field">
    <span class="field__label">Usuario (cédula o nickname)</span>
    {{-- ANTES: name="email" --}}
    <input class="input" type="text" name="usuario" autocomplete="username" required>
  </label>

  <label class="field">
    <span class="field__label">Clave</span>
    <div class="input-group">
      {{-- ANTES: name="password" --}}
      <input class="input input--ghost" id="pwd" type="password" name="clave" autocomplete="current-password" required>
      <button type="button" class="btn btn--ghost" data-toggle-password="#pwd" aria-label="Mostrar/ocultar clave">
        <span class="i i-eye"></span>
      </button>
    </div>
  </label>

  <label class="check">
    <input type="checkbox" name="remember"> <span>Recordarme</span>
  </label>

  <button class="btn btn--primary w-full" type="submit">Ingresar</button>
</form>

<div class="mt-3 text-center">
  ¿No tienes cuenta? <a href="{{ route('registro.create') }}">Regístrate</a>
</div>
@if (session('ok'))
  <div class="alert alert-success mt-2">{{ session('ok') }}</div>
@endif

	
	
  </div>
</div>
@endsection
