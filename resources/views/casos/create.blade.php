{{-- resources/views/casos/create.blade.php --}}
@extends('layouts.app')

@section('title','Crear caso')

@push('styles')
<style>
  /* Contenedor de la tarjeta */
  .form-card{
    max-width: 980px;
    margin: 2rem auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 10px 24px rgba(0,0,0,.06);
    padding: 1.25rem;
  }
  @media (min-width: 640px){
    .form-card{ padding: 2rem; }
  }

  .form-head{
    display:flex; align-items:center; gap:.75rem; margin-bottom:1rem;
  }
  .form-head h1{
    font-size: clamp(1.25rem, 2.4vw, 1.8rem);
    margin:0; font-weight:800;
  }

  /* Grid responsive: 1 col (mobile) / 2 cols (>=768px) */
  .form-grid{
    display:grid; gap:1rem;
    grid-template-columns: 1fr;
  }
  @media (min-width: 768px){
    .form-grid{
      grid-template-columns: repeat(2, minmax(0,1fr));
    }
    /* Haz que “Número de caso” ocupe 2 columnas */
    .col-span-2-md{ grid-column: span 2 / span 2; }
  }

  .field label{
    display:block; font-weight:700; margin-bottom:.35rem;
  }
  .field input[type="text"],
  .field input[type="date"]{
    width:100%;
    border:1px solid #dcdfe6;
    background:#fff;
    border-radius:12px;
    padding:.75rem .9rem;
    outline:none;
    transition: box-shadow .15s, border-color .15s;
  }
  .field input:focus{
    border-color:#3b82f6;
    box-shadow:0 0 0 4px rgba(59,130,246,.12);
  }
  .muted{ color:#6b7280; font-size:.9rem; }

  .actions{
    display:flex; gap:.75rem; justify-content:flex-end; margin-top:1rem;
  }
  .btn-primary{
    background:#22c55e; color:#fff; border:0; border-radius:12px;
    padding:.85rem 1.25rem; font-weight:700; cursor:pointer;
  }
  .btn-light{
    background:#f3f4f6; color:#111827; border:0; border-radius:12px;
    padding:.85rem 1.25rem; font-weight:600; cursor:pointer;
  }

  .alert{
    background:#fff1f2; border:1px solid #fecdd3; color:#7f1d1d;
    padding:.85rem 1rem; border-radius:12px; margin-bottom:1rem;
  }
  .badge{
    display:inline-block; font-size:.75rem; padding:.15rem .5rem;
    border-radius:9999px; background:#eef2ff; color:#3730a3; font-weight:700;
  }
</style>
@endpush

@section('content')

  <form class="form-card" action="{{ route('casos.store') }}" method="POST" autocomplete="off">
    @csrf

    <div class="form-head">
      <span class="badge">Nuevo</span>
      <h1>Crear caso</h1>
    </div>

    @if ($errors->any())
      <div class="alert">
        <strong>Revisa:</strong>
        <ul style="margin:.4rem 0 0 .8rem;">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-grid">
      {{-- Número de caso (solo lectura) --}}
      <div class="field col-span-2-md">
        <label>Número de caso</label>
        <input type="text" value="{{ $numero ?? '' }}" readonly class="muted">
        <div class="muted">Se asigna automáticamente al guardar.</div>
      </div>

      {{-- Fecha --}}
      <div class="field">
        <label>Fecha</label>
        <input type="date" name="fecha" value="{{ old('fecha', $fecha ?? now()->toDateString()) }}">
      </div>

      {{-- Cédula --}}
      <div class="field">
        <label>Cédula del generador</label>
        <input type="text" name="cedula" value="{{ old('cedula', auth()->user()->cedula ?? '') }}" placeholder="Ej. 1802709483">
      </div>

      {{-- Descripción (label) --}}
      <div class="field col-span-2-md">
        <label>Descripción (label)</label>
        <input type="text" name="label" value="{{ old('label') }}" placeholder="Ej. 26-08-2025 MUERTE MV ARMA DE FUEGO ...">
      </div>
    </div>

    <div class="actions">
      <a class="btn-light" href="{{ route('casos.index') }}">Cancelar</a>
      <button class="btn-primary" type="submit">Guardar y alimentar detalle</button>
    </div>
  </form>

@endsection
