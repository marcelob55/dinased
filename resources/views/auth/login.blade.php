@extends('layouts.app')

@section('content')
<h2>Ingreso al sistema</h2>

@if($errors->any())
  <div style="color:#b00020; margin-bottom:8px;">
    {{ $errors->first() }}
  </div>
@endif

<form method="POST" action="{{ route('login.post') }}" style="max-width:420px">
  @csrf

  <label>Usuario (cédula o nickname)</label>
  <input type="text" name="usuario" value="{{ old('usuario') }}" required>

  <label>Clave</label>
  <input type="password" name="clave" required>

  <label style="display:flex;align-items:center;gap:6px;margin-top:8px;">
    <input type="checkbox" name="remember"> Recordarme
  </label>

  <button type="submit" style="margin-top:12px;">Ingresar</button>
</form>
@endsection
