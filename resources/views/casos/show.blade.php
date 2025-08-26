@extends('layouts.app')

@section('content')

{{-- Encabezado + botón PDF --}}

<div style="display:flex;justify-content:space-between;align-items:center;margin:8px 0 16px">
  <h2 style="margin:0">Caso {{ $caso->numero_caso }}</h2>

  <div style="display:flex;gap:8px">
    <a href="{{ route('casos.pdf', $caso) }}"
       target="_blank"
       style="text-decoration:none;padding:8px 12px;border-radius:6px;background:#111;color:#fff;display:inline-block">
       Descargar PDF
    </a>
	
	<a class="btn btn-sm btn-primary" target="_blank"
   href="{{ route('casos.pdf', $caso) }}">
  PDF (narrativa)
</a>

	
	
	
    {{-- opcional: regresar al listado --}}
    <a href="{{ route('casos.index') }}"
       style="text-decoration:none;padding:8px 12px;border:1px solid #ddd;border-radius:6px;color:#111;display:inline-block">
       Volver
    </a>
  </div>
</div>




<h1>Caso {{ $caso->numero_caso }}</h1>

<p><b>Label:</b> {{ $caso->label }}</p>
<p><b>Fecha:</b> {{ $caso->fecha }}</p>
<p><b>Generador (cédula):</b> {{ $caso->cedula }}</p>

@if($caso->detalle)
  <h3 style="margin-top:18px">Detalle del caso</h3>

  <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
    <p><b>Verificación:</b> {{ $caso->detalle->verificacion }}</p>
    <p><b>Código ECU:</b> {{ $caso->detalle->codigo_ecu }}</p>

    <p><b>Zona:</b> {{ $caso->detalle->zona }}</p>
    <p><b>Subzona:</b> {{ $caso->detalle->subzona }}</p>

    <p><b>Distrito:</b> {{ $caso->detalle->distrito }}</p>
    <p><b>Circuito:</b> {{ $caso->detalle->circuito }}</p>

    <p><b>Subcircuito:</b> {{ $caso->detalle->subcircuito }}</p>
    <p><b>Espacio:</b> {{ $caso->detalle->espacio }}</p>

    <p><b>Área:</b> {{ $caso->detalle->area }}</p>
    <p><b>Fecha/Hora del hecho:</b> {{ $caso->detalle->fecha_hecho }} {{ $caso->detalle->hora_hecho }}</p>

    <p style="grid-column:1/-1"><b>Lugar del hecho:</b> {{ $caso->detalle->lugar_hecho }}</p>
    <p style="grid-column:1/-1"><b>Coordenadas:</b> {{ $caso->detalle->coordenadas }}</p>

    <p><b>Criminalística:</b> {{ $caso->detalle->criminalistica }}</p>
    <p><b>Tipo de arma:</b> {{ $caso->detalle->tipo_arma }}</p>

    <p><b>¿Indicios?</b> {{ $caso->detalle->indicios }}</p>
    <p><b>Tipo de delito:</b> {{ $caso->detalle->tipo_delito }}</p>

    <p style="grid-column:1/-1"><b>Estado del caso:</b> {{ $caso->detalle->estado_caso }}</p>
    <p style="grid-column:1/-1"><b>Motivación:</b> {{ $caso->detalle->motivacion }}</p>
    <p style="grid-column:1/-1"><b>Justificación:</b><br>{!! nl2br(e($caso->detalle->justificacion)) !!}</p>
    <p style="grid-column:1/-1"><b>Circunstancias:</b><br>{!! nl2br(e($caso->detalle->circunstancias)) !!}</p>
  </div>

  {{-- Entrevistas / Actividades (arrays JSON) --}}
  
<h3>Fallecidos</h3>
@forelse($fallecidos as $v)
  <div>- {{ $v->etiqueta }}: {{ $v->nombres }} {{ $v->apellidos }} ({{ $v->cedula }}) — {{ $v->edad }} años, {{ $v->sexo }}</div>
@empty
  <div>—</div>
@endforelse

<h3>Heridos</h3>
@forelse($heridos as $v)
  <div>- {{ $v->etiqueta }}: {{ $v->nombres }} {{ $v->apellidos }} ({{ $v->cedula }}) — {{ $v->edad }} años, {{ $v->sexo }}</div>
@empty
  <div>—</div>
@endforelse





  
  <h3 style="margin-top:18px">Entrevistas</h3>
  @php
    $ent = $caso->detalle->entrevistas ?? [];
    if (!is_array($ent)) { $ent = json_decode($ent, true) ?: []; }
  @endphp
  @if(count($ent))
    <ul>
      @foreach($ent as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  @else
    <p>—</p>
  @endif

  <h3 style="margin-top:18px">Actividades</h3>
  @php
    $act = $caso->detalle->actividades ?? [];
    if (!is_array($act)) { $act = json_decode($act, true) ?: []; }
  @endphp
  @if(count($act))
    <ul>
      @foreach($act as $a) <li>{{ $a }}</li> @endforeach
    </ul>
  @else
    <p>—</p>
  @endif

@else
  <p>Este caso aún no tiene detalle.</p>
@endif

<p style="margin-top:18px">
  <a href="{{ route('detalle.edit', $caso) }}">Alimentar / Editar detalle</a>
</p>
@endsection
