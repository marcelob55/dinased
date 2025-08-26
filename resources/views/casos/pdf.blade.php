@php
    /** @var \App\Models\Caso $caso */
    $detalle = $caso->detalle;
    $fmt = fn($v) => $v ? $v : '—';
@endphp
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Informe — {{ $caso->numero_caso }}</title>
<style>
  @page { margin: 28mm 18mm; }
  body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 12px; color:#111; }
  h1 { font-size: 18px; margin: 0 0 8px 0; }
  h2 { font-size: 14px; margin: 18px 0 6px; }
  .muted { color:#444; }
  .grid2 { display: table; width:100%; }
  .row { display: table-row; }
  .c { display: table-cell; width:50%; vertical-align: top; padding: 2px 8px 2px 0; }
  .lab { font-weight: 700; display:block; }
  ul { margin: 4px 0 0 18px; padding:0; }
  .hr { height:1px; background:#ddd; margin:10px 0; }
  .block { margin: 6px 0 10px; }
</style>
</head>
<body>

<h1>VERIFICACIÓN {{ $fmt($detalle->verificacion ?? null) }}</h1>
<div class="muted">Caso {{ $caso->numero_caso }}</div>

<div class="grid2 block">
  <div class="row">
    <div class="c">
      <span class="lab">CÓDIGO ECU:</span>
      {{ $fmt($detalle->codigo_ecu ?? null) }}
    </div>
    <div class="c">
      <span class="lab">Fecha/Hora del hecho:</span>
      @php
        $f = optional($detalle->fecha_hecho)->format('d/m/Y');
        $h = $detalle->hora_hecho ?? null;
      @endphp
      {{ trim(($f ?: '').' '.($h ?: '')) ?: '—' }}
    </div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">ZONA:</span> {{ $fmt($detalle->zona ?? null) }}</div>
    <div class="c"><span class="lab">Subzona:</span> {{ $fmt($detalle->subzona ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Distrito:</span> {{ $fmt($detalle->distrito ?? null) }}</div>
    <div class="c"><span class="lab">Circuito:</span> {{ $fmt($detalle->circuito ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Subcircuito:</span> {{ $fmt($detalle->subcircuito ?? null) }}</div>
    <div class="c"><span class="lab">Espacio:</span> {{ $fmt($detalle->espacio ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Área:</span> {{ $fmt($detalle->area ?? null) }}</div>
    <div class="c"><span class="lab">Lugar del hecho:</span> {{ $fmt($detalle->lugar_hecho ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Coordenadas:</span> {{ $fmt($detalle->coordenadas ?? null) }}</div>
    <div class="c"><span class="lab">¿Indicios?:</span> {{ $fmt($detalle->indicios ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Criminalística:</span> {{ $fmt($detalle->criminalistica ?? null) }}</div>
    <div class="c"><span class="lab">Tipo de arma:</span> {{ $fmt($detalle->tipo_arma ?? null) }}</div>
  </div>

  <div class="row">
    <div class="c"><span class="lab">Estado del caso:</span> {{ $fmt($detalle->estado_caso ?? null) }}</div>
    <div class="c"><span class="lab">Tipo de delito:</span> {{ $fmt($detalle->tipo_delito ?? null) }}</div>
  </div>
</div>

<div class="block">
  <span class="lab">Motivación:</span>
  <div>{{ $fmt($detalle->motivacion ?? null) }}</div>
</div>

<div class="block">
  <span class="lab">Justificación:</span>
  <div>{{ $fmt($detalle->justificacion ?? null) }}</div>
</div>

<div class="block">
  <span class="lab">Circunstancias:</span>
  <div>{{ $fmt($detalle->circunstancias ?? null) }}</div>
</div>

<div class="hr"></div>

<h2>Fallecidos</h2>
@if(($fallecidos ?? collect())->count())
  <ul>
    @foreach($fallecidos as $v)
      <li>
        <strong>{{ $v->etiqueta ? ($v->etiqueta.': ') : '' }}</strong>
        {{ trim($v->nombres.' '.$v->apellidos) ?: '—' }}
        @if($v->cedula) ({{ $v->cedula }}) @endif
        @if($v->edad) — {{ $v->edad }} años @endif
        @if($v->sexo) , {{ $v->sexo }} @endif
      </li>
    @endforeach
  </ul>
@else
  — 
@endif

<h2>Heridos</h2>
@if(($heridos ?? collect())->count())
  <ul>
    @foreach($heridos as $v)
      <li>
        <strong>{{ $v->etiqueta ? ($v->etiqueta.': ') : '' }}</strong>
        {{ trim($v->nombres.' '.$v->apellidos) ?: '—' }}
        @if($v->cedula) ({{ $v->cedula }}) @endif
        @if($v->edad) — {{ $v->edad }} años @endif
        @if($v->sexo) , {{ $v->sexo }} @endif
      </li>
    @endforeach
  </ul>
@else
  — 
@endif

<h2>Entrevistas</h2>
@if(is_array($detalle->entrevistas) && count($detalle->entrevistas))
  <ul>
    @foreach($detalle->entrevistas as $e)
      <li>{{ $e }}</li>
    @endforeach
  </ul>
@else
  —
@endif

<h2>Actividades</h2>
@if(is_array($detalle->actividades) && count($detalle->actividades))
  <ul>
    @foreach($detalle->actividades as $a)
      <li>{{ $a }}</li>
    @endforeach
  </ul>
@else
  —
@endif

<div class="block">
  <span class="lab">Reporta:</span>
  <div>{{ $fmt($detalle->reporta ?? null) }}</div>
</div>

</body>
</html>
