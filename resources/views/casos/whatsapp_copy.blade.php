{{-- resources/views/casos/whatsapp_copy.blade.php --}}
@php
  use Carbon\Carbon;
  $D = (object)($detalle ?? []);
  $F = fn($v)=>($v?Carbon::parse($v)->format('d/m/Y'):null);
  $H = fn($v)=>($v?Carbon::parse($v)->format('H:i'):null);

  // fechas
  $fh_fecha = $F($D->fecha_hecho ?? ($D->fecha_hora_del_hecho ?? $D->fecha_hora_hecho ?? null));
  $fh_hora  = $H($D->hora_hecho ?? ($D->fecha_hora_del_hecho ?? $D->fecha_hora_hecho ?? null));
  $hecho    = trim(($fh_fecha ?? '').($fh_hora ? '; '.$fh_hora : ''));

  $lev_dt   = $D->fecha_hora_levantamiento ?? null;
  $lev_f    = $F($D->fecha_levantamiento ?? $lev_dt);
  $lev_h    = $H($D->hora_levantamiento  ?? $lev_dt);
  $lev      = ($lev_f || $lev_h) ? trim(($lev_f ?? '').($lev_h?'-'.$lev_h:'')) . ' aproximadamente' : '—';

  // ubigeo
  $zona = $D->zona ?? '';
  $zonaNum = ltrim((string)$zona, '0');

  // indicios (bullet)
  $indTxt = trim($D->indicios_detalle ?? '');
  $inds = [];
  if ($indTxt !== '') {
    foreach (preg_split('/\r\n|\r|\n|;|•/u', $indTxt) as $row) {
      $row = trim(ltrim($row, '-–• '));
      if ($row !== '') $inds[] = $row;
    }
  }

  // entrevistas / actividades
  $entre = collect(is_string($D->entrevistas ?? null) ? preg_split('/\r\n|\r|\n/u',$D->entrevistas) : ($D->entrevistas ?? []))
           ->map(fn($s)=>trim($s))->filter()->values();
  $acts  = collect(is_string($D->actividades ?? null) ? preg_split('/\r\n|\r|\n/u',$D->actividades) : ($D->actividades ?? []))
           ->map(fn($s)=>trim(ltrim($s,'-• ')))->filter()->values();

  $bold = fn($label, $val) => $val !== '' && $val !== null ? "*{$label}* {$val}" : null;
@endphp

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>WhatsApp — Caso {{ $caso->numero_caso }}</title>
<style>
  body{font-family:ui-monospace,Menlo,Consolas,monospace;padding:16px}
  .wrap{white-space:pre-wrap}
  .tools{margin-bottom:10px}
  button{padding:.45rem .7rem;border:1px solid #ccc;border-radius:6px;background:#fff;cursor:pointer}
</style>
</head>
<body>
<div class="tools">
  <button onclick="copyText()">Copiar todo</button>
</div>

<div id="wtext" class="wrap">
*{{ strtoupper(trim($D->verificacion ?? 'REPORTE')) }}*

*{{ 'CÓDIGO ÚNICO:' }}* {{ $caso->numero_caso }}

*DINASED Z{{ $zonaNum ?: '—' }}*
*ZONA:* {{ $D->zona ?? '—' }}
*SUBZONA:* {{ $D->subzona ?? '—' }}
*DISTRITO:* {{ $D->distrito ?? '—' }}
*CIRCUITO:* {{ $D->circuito ?? '—' }}
*SUBCIRCUITO:* {{ $D->subcircuito ?? '—' }}

*ESPACIO:* {{ $D->espacio ?? '—' }}
*ÁREA:* {{ $D->area ?? '—' }}

*FECHA/HORA DEL HECHO:* {{ $hecho ? $hecho.' aproximadamente.' : '—' }}
*FECHA/HORA DEL LEVANTAMIENTO:* {{ $lev }}
*LUGAR DEL HECHO:* {{ ($D->lugar_del_hecho ?? $D->lugar_hecho) ?: '—' }}
*TIPO DE DELITO:* {{ $D->tipo_delito ?? '—' }}

*COORDENADAS:* {{ ($D->coordenadas ?? ($D->lat && $D->lng ? "{$D->lat}, {$D->lng}" : null)) ?? '—' }}

*ESTADO DE CASO:* {{ strtoupper($D->estado_caso ?? '—') }}
@if(!empty($D->motivacion))
*{{ 'MOTIVACIÓN:' }}* {{ $D->motivacion }}
@endif
@if(!empty($D->justificacion))
*{{ 'JUSTIFICACIÓN DE LA MOTIVACIÓN:' }}* {{ $D->justificacion }}
@endif
@if(!empty($D->ucm))
*UCM:* {{ $D->ucm }}
@endif

*INDICIOS*
@forelse($inds as $i) • {{ $i }}
@empty — 
@endforelse

@foreach($fallecidos as $idx => $v)
*INTERFECTO{{ $idx?(' '.chr(65+$idx)) : '' }}:* {{ trim(($v->apellidos ?? '').' '.($v->nombres ?? '')) ?: ($v->nombre ?? '—') }}
*CÉDULA:* {{ $v->cedula ?? '—' }}
*NACIONALIDAD:* {{ $v->nacionalidad ?? 'Se desconoce' }}
*EDAD:* {{ $v->edad ?? '—' }}
*ALIAS:* {{ $v->alias ?? 'Se desconoce' }}
*PROFESIÓN/OCUPACIÓN:* {{ $v->profesion_ocupacion ?? $v->ocupacion ?? 'Se desconoce' }}
*MOVILIZACIÓN:* {{ $v->movilizacion ?? 'Se desconoce' }}
*ANTECEDENTES SIIPNE:* {{ $v->antecedentes ?? '—' }}
*SATJE JUDICATURA:* {{ $v->sajte_det ?? ($v->sajte_judicatura ?? '—') }}
*NOTICIA DEL DELITO (FISCALÍA):* {{ $v->noticia_fiscalia_det ?? ($v->noticia_del_delito_fiscalia ?? '—') }}
@php
  $txtGao = trim(($v->gao_det ?? '').' '.($v->gao_cargo_funcion ?? $v->cargo ?? ''));
  if($txtGao==='') $txtGao = ($v->pertenece_gao ?? null) ? ($v->gao_cargo_funcion ?? 'Sí') : 'No';
@endphp
*PERTENECE A UN GAO / CARGO-FUNCIÓN:* {{ $txtGao }}

@endforeach

@if(($heridos ?? collect())->count())
*HERIDOS:*
@foreach($heridos as $h)
• {{ trim(($h->apellidos ?? '').' '.($h->nombres ?? '')) ?: ($h->nombre ?? '—') }}@if(!empty($h->observacion)) — {{ $h->observacion }}@endif
@endforeach
@endif

@if(!empty($D->circunstancias))
*{{ 'CIRCUNSTANCIAS DE LOS HECHOS:' }}* {{ $D->circunstancias }}
@endif

@if($entre->count())
*ENTREVISTAS REALIZADAS:*
@foreach($entre as $e) • {{ $e }}
@endforeach
@endif

@if($acts->count())
*ACTIVIDADES REALIZADAS:*
@foreach($acts as $a) • {{ $a }}
@endforeach
@endif

@if(!empty($D->reporta) || !empty($D->reporta_otro))
*REPORTA:* {{ $D->reporta === 'OTRO' ? ($D->reporta_otro ?? '') : ($D->reporta ?? '') }}
@endif
</div>

<script>
function copyText(){
  const el = document.getElementById('wtext');
  const r = document.createRange(); r.selectNodeContents(el);
  const s = window.getSelection(); s.removeAllRanges(); s.addRange(r);
  try { document.execCommand('copy'); alert('Texto copiado. Pégalo en WhatsApp.'); }
  catch(e){ alert('Selecciona y copia manualmente (Ctrl+C).'); }
  s.removeAllRanges();
}
</script>
</body>
</html>
