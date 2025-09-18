@extends('layouts.app')
@section('title','Ingreso al sistema')

@section('content')
<div class="auth">
  <div class="card auth__card">
    <h1 class="h3">Ingreso al sistema</h1>

    @if ($errors->any())
      <div class="alert alert--error">
        <strong>Revisa:</strong>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li> {{-- muestra el mensaje tal cual --}}
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="form">
      @csrf

      <label class="field">
        <span class="field__label">Usuario (cédula o nickname)</span>
        <input class="input @error('cedula') is-invalid @enderror"
               type="text" name="cedula" value="{{ old('cedula') }}"
               autocomplete="username" required>
      </label>
      @error('cedula') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

      <label class="field">
        <span class="field__label">Clave</span>
        <div class="input-group">
          <input class="input input--ghost @error('contrasena') is-invalid @enderror"
                 id="pwd" type="password" name="contrasena"
                 autocomplete="current-password" required>
          <button type="button" class="btn btn--ghost" data-toggle-password="#pwd"
                  aria-label="Mostrar/ocultar clave">
            <span class="i i-eye"></span>
          </button>
        </div>
      </label>
      @error('contrasena') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

      <label class="check">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <span>Recordarme</span>
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

{{-- Toggle ver/ocultar contraseña --}}
<script>
document.querySelectorAll('[data-toggle-password]').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const input = document.querySelector(btn.getAttribute('data-toggle-password'));
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.querySelector('.i')?.classList.toggle('i-eye');
    btn.querySelector('.i')?.classList.toggle('i-eye-off');
  });
});
</script>
@endsection
