@extends('layouts.app') {{-- o el layout que uses en login --}}
@section('content')

<div class="container" style="max-width: 720px;">
  <h3 class="mb-3">Registrar usuario</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

 <form method="POST" action="{{ route('registro.store') }}" class="row g-4 registro-form" novalidate>

    @csrf

    <div class="col-md-6">
      <label class="form-label">Nombres</label>
      <input name="nombres" class="form-control" value="{{ old('nombres') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Apellidos</label>
      <input name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nickname</label>
      <input name="nickname" class="form-control" value="{{ old('nickname') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Cédula</label>
      <input name="cedula" class="form-control" value="{{ old('cedula') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Celular</label>
      <input name="celular" class="form-control" value="{{ old('celular') }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Correo</label>
      <input type="email" name="correo" class="form-control" value="{{ old('correo') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Agencia</label>
      <input name="agencia" class="form-control" value="{{ old('agencia') }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Equipo</label>
      <input name="equipo" class="form-control" value="{{ old('equipo') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Rol</label>
      <select name="rol" class="form-select" required>
        @foreach($roles as $value => $label)
          <option value="{{ $value }}" @selected(old('rol')===$value)>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6"></div>

    <div class="col-md-6">
      <label class="form-label">Contraseña</label>
      <input type="password" name="contrasena" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Confirmar contraseña</label>
      <input type="password" name="contrasena_confirmation" class="form-control" required>
    </div>

    <div class="col-12 d-flex justify-content-between">
      <a href="{{ route('login') }}" class="btn btn-outline-secondary">Volver al login</a>
      <button class="btn btn-success">Crear cuenta</button>
    </div>
  </form>
</div>

@push('styles')
<style>
  /* Fuerza inputs a ocupar todo el ancho de su columna */
  .registro-form .form-control,
  .registro-form .form-select {
    width: 100% !important;
    max-width: 100% !important;
    min-height: 46px;        /* altura cómoda */
  }
  .registro-form .input-group > .form-control { flex: 1 1 auto; }
  /* Espaciado y tipografía consistentes en etiquetas */
  .registro-form .form-label{
    text-transform: uppercase;
    letter-spacing: .02em;
    font-size: .82rem;
    color:#6c757d;
  }
</style>


@push('scripts')
<script>
  document.querySelectorAll('.toggle-password').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const el = document.getElementById(btn.dataset.target);
      if(!el) return;
      el.type = el.type === 'password' ? 'text' : 'password';
      btn.textContent = el.type === 'password' ? 'Ver' : 'Ocultar';
    });
  });
</script>


@endpush

@endpush






@endsection
