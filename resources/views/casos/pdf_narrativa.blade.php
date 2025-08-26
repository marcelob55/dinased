@php
    /** @var \App\Models\Caso $caso */
    $d = $caso->detalle;
    $fmt = fn($v) => $v && trim($v) !== '' ? $v : '—';

    // Fecha/hora del hecho (unimos si existen)
    $fHecho = optional($d->fecha_hecho)->format('d/m/Y');
    $hHecho = $d->hora_hecho ?? null;
    $fechaHoraHecho = trim(($fHecho ?: '').', '.($hHecho ?: ''));
    if ($fechaHoraHecho === ',') $fechaHoraHecho = '—';

    // (Opcional) Si tienes fecha/hora de levantamiento, agrega columna en BD y casteo en el modelo DetalleCaso como fecha_levantamiento + hora_levantamiento.
    $fLev = property_exists($d,'fecha_levantamiento') ? optional($d->fecha_levantamiento)->format('d/m/Y') : null;
    $hLev = property_exists($d,'hora_levantamiento') ? $d->hora_levantamiento : null;
    $fechaHoraLev = trim(($fLev ?: '').', '.($hLev ?: ''));
    if ($fechaHoraLev === ',') $fechaHoraLev = '—';

    // Agrupar víctimas
    $occisos = $caso->victimas->where('tipo','occiso')->sortBy('etiqueta');
    $heridos = $caso->victimas->where('tipo','herido')->sortBy('etiqueta');

    // Fila víctima en formato “OCCISO A: APELLIDOS NOMBRES …”
    $victimaLinea = function($v){
        // Orden “APELLIDOS NOMBRES” (ajusta si prefieres “Nombres Apellidos”)
        $nombre = trim(($v->apellidos ? mb_strtoupper($v->apellidos) : '').' '.($v->nombres ? mb_strtoupper($v->nombres) : ''));
        $cedula = $v->cedula ?: '—';
        $edad   = $v->edad ? ($v->edad.' años') : '—';
        $alias  = $v->alias ?: 'Se desconoce';
        $nac    = $v->nacionalidad ?: '—';
        $prof   = $v->profesion_ocupacion ?: 'Se desconoce';
        $mov    = $v->movilizacion ?: 'Se desconoce';
        $ant    = isset($v->antecedentes) ? ($v->antecedentes ? 'Si' : 'No') : '—';
        $satje  = isset($v->sajte_judicatura) ? ($v->sajte_judicatura ? 'SI' : 'NO') : '—';
        $notDel = isset($v->noticia_del_delito_fiscalia) ? ($v->noticia_del_delito_fiscalia ? 'SI' : 'NO') : '—';
        $perGAO = isset($v->pertenece_gao) ? ($v->pertenece_gao ? 'SI' : 'NO') : '—';
        $cargo  = $v->gao_cargo_funcion ?: 'Se desconoce';

        return [
            'titulo' => mb_strtoupper($v->tipo).' '.($v->etiqueta ?: '—').': '.$nombre,
            'cedula' => $cedula,
            'edad'   => $edad,
            'alias'  => $alias,
            'nac'    => $nac,
            'prof'   => $prof,
            'mov'    => $mov,
            'ant'    => $ant,
            'satje'  => $satje,
            'notDel' => $notDel,
            'perGAO' => $perGAO,
            'cargo'  => $cargo,
        ];
    };

    // Indicios: si sólo tienes “Sí/No”, imprimimos eso; si agregas lista en detalle.indicios_detalle, la mostramos debajo.
    $indiciosYN = $d->indicios ?: '—';
    $indiciosTxt = property_exists($d,'indicios_detalle') && $d->indicios_detalle ? $d->indicios_detalle : null;
@endphp
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>CASO {{ $caso->numero_caso }}</title>
<style>
  @page { margin: 20mm 18mm; }
  body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color:#111; }
  h1{ font-size: 18px; margin:0 0 10px 0; }
  .b{ font-weight:700; }
  .mt{ margin-top:10px; }
  .mb{ margin-bottom:10px; }
  .blk{ margin:8px 0; }
  .sp{ height:6px; }
</style>
</head>
<body>

<h1>VERIFICACIÓN {{ $fmt($d->verificacion ?? null) }}</h1>

<div class="blk"><span class="b">CÓDIGO ÚNICO:</span><br>{{ $fmt($d->codigo_ecu ?? null) }}</div>

<div class="blk"><span class="b">ZONA:</span> {{ $fmt($d->zona ?? null) }}</div>
<div class="blk"><span class="b">DISTRITO:</span> {{ $fmt($d->distrito ?? null) }}</div>
<div class="blk"><span class="b">CIRCUITO:</span> {{ $fmt($d->circuito ?? null) }}</div>
<div class="blk"><span class="b">SUBCIRCUITO:</span> {{ $fmt($d->subcircuito ?? null) }}</div>

<div class="blk"><span class="b">FECHA/HORA DEL HECHO:</span><br>{{ $fechaHoraHecho ?: '—' }} {{ $hHecho ? ' Aproximadamente.' : '' }}</div>

<div class="blk"><span class="b">LUGAR DEL HECHO:</span><br>{{ $fmt($d->lugar_hecho ?? null) }}</div>

<div class="blk"><span class="b">ESPACIO:</span> {{ $fmt($d->espacio ?? null) }}</div>
<div class="blk"><span class="b">ÁREA:</span> {{ $fmt($d->area ?? null) }}</div>

<div class="blk"><span class="b">FECHA/HORA LEVANTAMIENTO:</span><br>{{ $fechaHoraLev }}</div>

<div class="blk"><span class="b">COORDENADAS:</span><br>{{ $fmt($d->coordenadas ?? null) }}</div>

<div class="blk">
  <span class="b">ASISTE CRIMINALÍSTICA:</span> {{ $fmt($d->criminalistica ?? null) }}
  {{-- Si quieres formatear en varias líneas (UCM / nombre / cc / cel) coloca todo en el campo y se imprimirá tal cual --}}
</div>

<div class="blk">
  <span class="b">INDICIOS:</span> {{ $indiciosYN }}
  @if($indiciosTxt)
    <br>{!! nl2br(e($indiciosTxt)) !!}
  @endif
</div>

<div class="blk"><span class="b">TIPO DE ARMA:</span> {{ $fmt($d->tipo_arma ?? null) }}</div>
<div class="blk"><span class="b">TIPO DE DELITO:</span> {{ $fmt($d->tipo_delito ?? null) }}</div>

<div class="blk">
  <span class="b">ESTADO DE CASO:</span><br>
  {{-- Si solo tienes un estado general en d->estado_caso, se imprime; si gestionas banderas, reemplaza por tu lógica --}}
  {{ $fmt($d->estado_caso ?? null) }}
</div>

<div class="blk"><span class="b">MOTIVACIÓN:</span><br>{{ $fmt($d->motivacion ?? null) }}</div>

<div class="blk"><span class="b">JUSTIFICACIÓN DE LA MOTIVACIÓN:</span><br>{{ $fmt($d->justificacion ?? null) }}</div>

{{-- OCCISOS --}}
@if($occisos->count())
  @foreach($occisos as $v)
    @php $L = $victimaLinea($v); @endphp
    <div class="blk">
      <div class="b">{{ $L['titulo'] }}</div>
      CÉDULA: {{ $L['cedula'] }}<br>
      EDAD: {{ $L['edad'] }}<br>
      ALIAS: {{ $L['alias'] }}<br>
      NACIONALIDAD: {{ $L['nac'] }}<br>
      PROFESIÓN/OCUPACIÓN: {{ $L['prof'] }}<br>
      MOVILIZACIÓN: {{ $L['mov'] }}<br>
      ANTECEDENTES: {{ $L['ant'] }}<br>
      SATJE JUDICATURA: {{ $L['satje'] }}<br>
      NOTICIA DEL DELITO (FISCALÍA): {{ $L['notDel'] }}<br>
      PERTENECE A UN GAO / CARGO-FUNCIÓN: {{ $L['perGAO']=='SI' ? $L['cargo'] : 'No' }}
    </div>
  @endforeach
@endif

{{-- (Opcional) Heridos si quisieras listarlos con el mismo formato --}}
@if($heridos->count())
  @foreach($heridos as $v)
    @php $L = $victimaLinea($v); @endphp
    <div class="blk">
      <div class="b">{{ $L['titulo'] }}</div>
      CÉDULA: {{ $L['cedula'] }}<br>
      EDAD: {{ $L['edad'] }}<br>
      ALIAS: {{ $L['alias'] }}<br>
      NACIONALIDAD: {{ $L['nac'] }}<br>
      PROFESIÓN/OCUPACIÓN: {{ $L['prof'] }}<br>
      MOVILIZACIÓN: {{ $L['mov'] }}<br>
      ANTECEDENTES: {{ $L['ant'] }}<br>
      SATJE JUDICATURA: {{ $L['satje'] }}<br>
      NOTICIA DEL DELITO (FISCALÍA): {{ $L['notDel'] }}<br>
      PERTENECE A UN GAO / CARGO-FUNCIÓN: {{ $L['perGAO']=='SI' ? $L['cargo'] : 'No' }}
    </div>
  @endforeach
@endif

<div class="blk">
  <span class="b">CIRCUNSTANCIAS DE LOS HECHOS</span><br>
  {!! nl2br(e($fmt($d->circunstancias ?? null))) !!}
</div>

<div class="blk">
  <span class="b">ENTREVISTAS REALIZADAS</span><br>
  @if(is_array($d->entrevistas) && count($d->entrevistas))
    {!! nl2br(e(implode("\n", $d->entrevistas))) !!}
  @else
    —
  @endif
</div>

<div class="blk">
  <span class="b">ACTIVIDADES REALIZADAS:</span><br>
  @if(is_array($d->actividades) && count($d->actividades))
    @foreach($d->actividades as $a)
      - {{ $a }}<br>
    @endforeach
  @else
    —
  @endif
</div>

<div class="blk">
  <span class="b">Reporta:</span><br>
  {{ $fmt($d->reporta ?? null) }}
</div>

</body>
</html>
