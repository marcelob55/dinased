@extends('layouts.app')
@section('content')
<h1>Ingresar</h1>
<form method="POST" action="{{ route('login.do') }}">
  @csrf
  <label>Cédula</label>
  <input name="cedula" value="{{ old('cedula') }}">
  @error('cedula')<small class="text-danger">{{ $message }}</small>@enderror

  <label>Contraseña</label>
  <input name="contrasena" type="password">
  @error('contrasena')<small class="text-danger">{{ $message }}</small>@enderror

  <button>Entrar</button>
</form>
@endsection

