@extends('layouts.app')
@section('content')
<h1>Crear Caso</h1>
<form method="POST" action="{{ route('casos.store') }}">
  @csrf
  <label>Número de Caso</label>
  <input name="numero_caso" value="{{ $numero }}" readonly>

  <label>Fecha</label>
  <input type="date" name="fecha" value="{{ $fecha }}">

  <label>Descripción (label)</label>
  <input name="label" value="{{ old('label') }}">

  <label>Cédula creador</label>
  <input name="cedula" value="{{ old('cedula') }}">

  <button>Guardar y Alimentar Detalle</button>
</form>
@endsection

