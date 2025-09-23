@php
    /** @var \App\Models\Caso $caso */
    $d = $caso->detalle;

    // Helper robusto: maneja null/arrays/objetos
    $fmt = function($v) {
        if ($v === null) return '—';
        if (is_array($v)) {
            $v = array_map(fn($x) => is_scalar($x) ? (string)$x : json_encode($x, JSON_UNESCAPED_UNICODE), $v);
            $v = array_filter(array_map('trim', $v), fn($s)=>$s!=='');
            return count($v) ? implode(', ', $v) : '—';
        }
        if (is_object($v) && !method_exists($v,'__toString')) return '—';
        $s = trim((string)$v);
        return $s !== '' ? $s : '—';
    };



// ===== TÍTULO sin duplicar "VERIFICACIÓN" (soporta NBSP y marcas invisibles) =====
$raw = (string)($d->verificacion ?? '');

// 1) Limpia marcas de dirección y NBSP al inicio
$clean = preg_replace('/^[\x{200E}\x{200F}\x{202A}-\x{202E}\x{2066}-\x{2069}\x{00A0}\s]+/u', '', $raw);

// 2) Normaliza espacios
$norm = trim(preg_replace('/\s+/u', ' ', $clean));

// 3) Quita 1+ repeticiones de "verificación" al inicio (con o sin acento, tolera NBSP)
$sin  = preg_replace('/^(?:[\x{200E}\x{200F}\x{00A0}\s]*verificaci[oó]n[\x{200E}\x{200F}\x{00A0}\s]*)+/iu', '', $norm);
$sin  = trim($sin);

$titulo = $sin !== ''
    ? 'VERIFICACIÓN ' . mb_strtoupper($sin)
    : 'VERIFICACIÓN DE UNA PERSONA FALLECIDA POR ARMA DE FUEGO';




    /* ========= FECHAS / HORAS (DATE + TIME separados) ========= */
    // Hecho
    $fechaHecho = $d->fecha_hecho ? \Carbon\Carbon::parse($d->fecha_hecho)->format('d/m/Y') : null;
    $horaHecho  = $d->hora_hecho ? \Carbon\Carbon::parse($d->hora_hecho)->format('H:i') : null;
    $fechaHoraHecho = $fechaHecho && $horaHecho ? "$fechaHecho $horaHecho" : ($fechaHecho ?: ($horaHecho ?: '—'));

    // Levantamiento
    $fechaLev = $d->fecha_levantamiento ? \Carbon\Carbon::parse($d->fecha_levantamiento)->format('d/m/Y') : null;
    $horaLev  = $d->hora_levantamiento ? \Carbon\Carbon::parse($d->hora_levantamiento)->format('H:i') : null;
    $fechaHoraLev = $fechaLev && $horaLev ? "$fechaLev $horaLev" : ($fechaLev ?: ($horaLev ?: '—'));

    /* ========= Víctimas ========= */
    $occisos = $caso->victimas->where('tipo','occiso')->sortBy('etiqueta');
    $heridos = $caso->victimas->where('tipo','herido')->sortBy('etiqueta');



$victimaLinea = function($v){
    $nombre = trim(
        ($v->apellidos ? mb_strtoupper($v->apellidos) : '')
        .' '.
        ($v->nombres ? mb_strtoupper($v->nombres) : '')
    );

    // helpers Yes/No con detalle
    $yn = function($flag, $det = null) {
        if (!isset($flag)) return '—';
        if ($flag) {
            return 'SI' . (isset($det) && $det !== '' ? ' — ' . $det : '');
        }
        return 'NO';
    };

    return [
        'titulo' => mb_strtoupper($v->tipo).' '.($v->etiqueta ?: '—').': '.$nombre,
        'cedula' => $v->cedula ?: '—',
        'edad'   => $v->edad ? ($v->edad.' años') : '—',
        'alias'  => $v->alias ?: 'Se desconoce',
        'nac'    => $v->nacionalidad ?: '—',
        'prof'   => $v->profesion_ocupacion ?: 'Se desconoce',
        'mov'    => $v->movilizacion ?: 'Se desconoce',

        // Aquí van con detalle cuando es "Sí"
        'ant'    => $yn($v->antecedentes, $v->antecedentes_det ?? null),
        'satje'  => $yn($v->sajte_judicatura, $v->sajte_judicatura_det ?? null),
        'notDel' => $yn($v->noticia_del_delito_fiscalia, $v->noticia_del_delito_fiscalia_det ?? null),

        'perGAO' => isset($v->pertenece_gao) ? ($v->pertenece_gao ? 'SI' : 'NO') : '—',
        'cargo'  => $v->gao_cargo_funcion ?: 'Se desconoce',
    ];
};






    /* ====== Indicios (normalización robusta) ====== */
    $indiciosYN = $d->indicios ?: '—';
    $rawIndicios = $d->indicios_detalle ?? null;

    // Normalizador recursivo -> string (una línea por ítem)
    $normalize = null;
    $normalize = function ($v) use (&$normalize) {
        if ($v === null) return null;

        if (is_string($v)) {
            $vTrim = trim($v);
            if ($vTrim === '') return null;
            $decoded = json_decode($vTrim, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $v = $decoded;
            } else {
                return $vTrim;
            }
        }

        if (is_scalar($v)) {
            $s = trim((string)$v);
            return $s === '' ? null : $s;
        }

        if (is_array($v)) {
            $lines = [];
            foreach ($v as $item) {
                $line = $normalize($item);
                if ($line !== null) $lines[] = $line;
            }
            return count($lines) ? implode("\n", $lines) : null;
        }

        if (is_object($v)) {
            if (method_exists($v, '__toString')) {
                $s = trim((string)$v);
                return $s === '' ? null : $s;
            }
            $json = json_encode($v, JSON_UNESCAPED_UNICODE);
            return $json === false ? null : $json;
        }

        return null;
    };

    $indiciosTxt = $normalize($rawIndicios);

      /* ====== Utilidad: decodifica JSON o líneas para entrevistas/actividades (robusto) ====== */
    $toLines = function ($val) {
        $toScalarString = function ($x) {
            if (is_scalar($x) || (is_object($x) && method_exists($x, '__toString'))) {
                $s = trim((string)$x);
                return $s === '' ? null : $s;
            }
            $json = json_encode($x, JSON_UNESCAPED_UNICODE);
            if ($json === false) return null;
            $s = trim($json);
            return $s === '' ? null : $s;
        };

        $flatten = null;
        $flatten = function ($arr) use (&$flatten) {
            $out = [];
            foreach ($arr as $v) {
                if (is_array($v)) {
                    $out = array_merge($out, $flatten($v));
                } else {
                    $out[] = $v;
                }
            }
            return $out;
        };

        // 1) Null
        if ($val === null) return [];

        // 2) String
        if (is_string($val)) {
            $valTrim = trim($val);
            if ($valTrim === '') return [];
            $decoded = json_decode($valTrim, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $decoded = $flatten($decoded);
                $lines = [];
                foreach ($decoded as $item) {
                    $s = $toScalarString($item);
                    if ($s !== null) $lines[] = $s;
                }
                return $lines;
            }
            // texto plano con saltos de línea
            $parts = preg_split('/\r\n|\r|\n/u', $valTrim);
            $out = [];
            foreach ($parts as $p) {
                $s = $toScalarString($p);
                if ($s !== null) $out[] = $s;
            }
            return $out;
        }

        // 3) Array
        if (is_array($val)) {
            $val = $flatten($val);
            $out = [];
            foreach ($val as $item) {
                $s = $toScalarString($item);
                if ($s !== null) $out[] = $s;
            }
            return $out;
        }

        // 4) Escalar/objeto
        $s = $toScalarString($val);
        return $s !== null ? [$s] : [];
    };

    // Recalcular con el helper nuevo
    $entrevistasArr = $toLines($d->entrevistas ?? null);
    $actividadesArr = $toLines($d->actividades ?? null);
	
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
  .blk{ margin:8px 0; }
</style>
</head>
<body>

<h1>{{ $titulo }}</h1>

<div class="blk"><span class="b">CÓDIGO ÚNICO:</span><br>{{ $fmt($d->codigo_ecu ?? null) }}</div>

<div class="blk"><span class="b">ZONA:</span> {{ $fmt($d->zona ?? null) }}</div>
<div class="blk"><span class="b">DISTRITO:</span> {{ $fmt($d->distrito ?? null) }}</div>
<div class="blk"><span class="b">CIRCUITO:</span> {{ $fmt($d->circuito ?? null) }}</div>
<div class="blk"><span class="b">SUBCIRCUITO:</span> {{ $fmt($d->subcircuito ?? null) }}</div>

<div class="blk"><span class="b">FECHA/HORA DEL HECHO:</span><br>{{ $fechaHoraHecho }}{{ $horaHecho ? ' Aproximadamente.' : '' }}</div>
<div class="blk"><span class="b">FECHA/HORA LEVANTAMIENTO:</span><br>{{ $fechaHoraLev }}</div>

<div class="blk"><span class="b">LUGAR DEL HECHO:</span><br>{{ $fmt($d->lugar_hecho ?? null) }}</div>

<div class="blk"><span class="b">ESPACIO:</span> {{ $fmt($d->espacio ?? null) }}</div>
<div class="blk"><span class="b">ÁREA:</span> {{ $fmt($d->area ?? null) }}</div>



<div class="blk"><span class="b">COORDENADAS:</span><br>{{ $fmt($d->coordenadas ?? null) }}</div>

<div class="blk">
  <span class="b">ASISTE CRIMINALÍSTICA:</span> {{ $fmt($d->criminalistica ?? null) }}
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

{{-- HERIDOS --}}
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
  @if(count($entrevistasArr))
    {!! nl2br(e(implode("\n", $entrevistasArr))) !!}
  @else
    —
  @endif
</div>

<div class="blk">
  <span class="b">ACTIVIDADES REALIZADAS:</span><br>
  @if(count($actividadesArr))
    @foreach($actividadesArr as $a)
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
