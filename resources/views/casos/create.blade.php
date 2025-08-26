@extends('layouts.app')

@section('content')
<h1>Crear caso</h1>

<form method="POST" action="{{ route('casos.store') }}">
  @csrf

  <label>Número de caso</label>
  <input name="numero_caso" value="{{ $numero }}" readonly>

  <label>Fecha</label>
  <input type="date" name="fecha" value="{{ $fecha }}">

  <label>Descripción (label)</label>
  <input name="label" value="{{ old('label') }}">
  @error('label') <small style="color:#b00020">{{ $message }}</small> @enderror

  <label>Cédula del generador</label>
  <input name="cedula" value="{{ old('cedula') }}">
  @error('cedula') <small style="color:#b00020">{{ $message }}</small> @enderror

  <button type="submit">Guardar y alimentar detalle</button>
</form>
@endsection
