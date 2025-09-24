@extends('layouts.app')
@section('title','Alimentar detalle')

@section('content')

<h1>Alimentar detalle — {{ $caso->numero_caso }}</h1>

<form method="POST" action="{{ route('detalle.store', $caso) }}">
  @csrf

  @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
    <style>
      /* toque suave para inputs, selects y textareas */
      .section{margin-top:18px}
      .form-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px}
      .field{display:flex;flex-direction:column}
      .field > label{font-weight:700;color:#4b5563;margin-bottom:4px;font-size:.9rem}
      .field input,.field select,.field textarea{padding:.65rem .75rem;border:1px solid #e5e7eb;border-radius:10px}
      .col-12{grid-column:span 12}
      .col-8{grid-column:span 12}
      .col-6{grid-column:span 12}
      .col-4{grid-column:span 12}
      .col-3{grid-column:span 12}
      @media(min-width:900px){
        .col-8{grid-column:span 8}.col-6{grid-column:span 6}
        .col-4{grid-column:span 4}.col-3{grid-column:span 3}
      }
      #map{height:320px;border-radius:12px;border:1px solid #e5e7eb}
      .textarea-l{min-height:120px}.textarea-xl{min-height:180px}
      table th, table td{padding:6px 8px;border-bottom:1px solid #eee}
      .more-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
      .btn-toggle-more,.btn-del-row{padding:.35rem .6rem;border:1px solid #ddd;border-radius:8px;background:#fff}
    </style>
  @endpush

  @php
    // Helpers para selects con “OTRO”
    $val = fn($k,$d=null)=> old($k, $detalle->$k ?? $d);

    // Verificación (con OTRO)
    $VERIF_OPTS = [
	
	'VERIFICACIÓN DE UNA PERSONA FALLECIDA POR ARMA DE FUEGO',
	'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE TRES PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE CUATRO PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE CINCO PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE SEIS PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE SIETE PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE OCHO PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	'VERIFICACIÓN DE NUEVE PERSONAS FALLECIDAS POR ARMA DE FUEGO',
	
	'VERIFICACIÓN DE UNA PERSONA FALLECIDA POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE TRES PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE CUATRO PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE CINCO PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE SEIS PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE SIETE PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE OCHO PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
	'VERIFICACIÓN DE NUEVE PERSONAS FALLECIDAS POR ARMA DE FUEGO CON HERIDOS',
		
	
	];
	
    $verificacion     = $val('verificacion','');
    $verifKnown       = in_array($verificacion,$VERIF_OPTS);
    $verifSelectValue = $verifKnown ? $verificacion : ($verificacion ? 'OTRO' : '');
    $verifOtroValue   = $verifKnown ? '' : ($verificacion ?? '');

    // Espacio / Área (sin “otro”)
    $espacio   = $val('espacio','Público');
    $area      = $val('area','Urbana');

    // ¿Criminalística? sí/no
    $criminalistica = $val('criminalistica','');

    // Tipo de arma (con OTRO)
    $ARMA_OPTS = ['ARMA DE FUEGO','ARMA BLANCA','CONTUNDENTE','EXPLOSIVO'];
    $tipo_arma     = $val('tipo_arma','');
    $armaKnown     = in_array($tipo_arma,$ARMA_OPTS);
    $armaSelectVal = $armaKnown ? $tipo_arma : ($tipo_arma ? 'OTRO' : '');
    $armaOtroVal   = $armaKnown ? '' : ($tipo_arma ?? '');

    // ¿Indicios?
    $indicios  = $val('indicios','');

    // Tipo de delito (con OTRO)
    $DELITO_OPTS = ['ASESINATO','HOMICIDIO','FEMICIDIO','SICARIATO'];
    $tipo_delito     = $val('tipo_delito','');
    $delKnown        = in_array($tipo_delito,$DELITO_OPTS);
    $delSelectVal    = $delKnown ? $tipo_delito : ($tipo_delito ? 'OTRO' : '');
    $delOtroVal      = $delKnown ? '' : ($tipo_delito ?? '');

    // Estado del caso (con OTRO)
    $ESTADO_OPTS = ['INVESTIGACIÓN','LÍNEA INVESTIGATIVA','RESUELTO','IDENTIFICADO  SOSPECHOSO'];
    $estado_caso     = $val('estado_caso','');
    $estKnown        = in_array($estado_caso,$ESTADO_OPTS);
    $estSelectVal    = $estKnown ? $estado_caso : ($estado_caso ? 'OTRO' : '');
    $estOtroVal      = $estKnown ? '' : ($estado_caso ?? '');

    // Motivación (con OTRO)
    $MOTIV_OPTS = [
      'VIOLENCIA CRIMINAL ',
      'DELINCUENCIA COMÚN',
      'AMENAZA / VENGANZA',
      'MICROTRAFICO',
      'VIOLENCIA INTRAFAMILIAR',
      'VIOLENCIA INTERPERSONAL'
    ];

    $motivacion     = $val('motivacion','');
    $motKnown       = in_array($motivacion,$MOTIV_OPTS);
    $motSelectVal   = $motKnown ? $motivacion : ($motivacion ? 'OTRO' : '');
    $motOtroVal     = $motKnown ? '' : ($motivacion ?? '');

    // Reporta (con OTRO)
    $REPORTA_OPTS = [
	'DINASED PORTOVIEJO',
	'DINASED MANTA',
	'DINASED CHONE',
	'DINASED SUCRE',
	'DINASED PEDERNALES',
	'DINASED EL CARMEN'
	
	
	];
    $reporta       = $val('reporta','');
    $repKnown      = in_array($reporta,$REPORTA_OPTS);
    $repSelectVal  = $repKnown ? $reporta : ($reporta ? 'OTRO' : '');
    $repOtroVal    = $repKnown ? '' : ($reporta ?? '');
  @endphp



{{-- 1. Verificación --}}
<div class="section">
  <h3>1. Verificación del evento</h3>
  <div class="form-grid">
    <div class="field col-8">
      <label>Verificación</label>
      <input type="text" name="verificacion" class="ctrl"
             value="{{ old('verificacion', $detalle->verificacion ?? '') }}"
             placeholder="VERIFICACIÓN DE UNA PERSONA FALLECIDA POR ARMA DE FUEGO">
    </div>

    <div class="field col-4">
      <label>Código ECU 911</label>
      <input type="text" name="codigo_ecu"
             value="{{ old('codigo_ecu', $detalle->codigo_ecu ?? '') }}"
             placeholder="p.ej. 52794">
    </div>
  </div>
</div>




  {{-- 2. Ubicación + mapa --}}
  <div class="section">
    <h3>2. Ubicación geográfica</h3>

    <div class="form-grid">
      <div class="field col-4">
        <label>ESPACIO</label>
        @php $esp = $espacio; @endphp
        <select name="espacio" class="ctrl">
          <option {{ $esp=='Público'?'selected':'' }}>Público</option>
          <option {{ $esp=='Privado'?'selected':'' }}>Privado</option>
        </select>
      </div>

      <div class="field col-4">
        <label>ÁREA</label>
        @php $ar = $area; @endphp
        <select name="area" class="ctrl">
          <option {{ $ar=='Urbana'?'selected':'' }}>Urbana</option>
          <option {{ $ar=='Rural'?'selected':'' }}>Rural</option>
        </select>
      </div>

      <div class="field col-4">
        <label>Fecha del hecho</label>
        <input type="date" name="fecha_hecho"
               value="{{ old('fecha_hecho', optional($detalle->fecha_hecho ?? null)->format('Y-m-d')) }}">
      </div>



<div class="field col-6">
  <label>Hora del hecho</label>
  @php
      // Acepta valores guardados como "07:01:00", "07:01" o instancias Carbon
      $horaRaw = old('hora_hecho', $detalle->hora_hecho ?? null);
      $horaVal = null;

      if ($horaRaw !== null && $horaRaw !== '') {
          try {
              // Normaliza siempre a HH:MM:SS
              $horaVal = \Carbon\Carbon::parse($horaRaw)->format('H:i:s');
          } catch (\Throwable $e) {
              $str = (string) $horaRaw;
              if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $str)) {
                  $horaVal = $str;
              } elseif (preg_match('/^\d{2}:\d{2}$/', $str)) {
                  $horaVal = $str . ':00';
              } else {
                  $horaVal = null; // valor inválido → input vacío
              }
          }
      }
  @endphp
  <input type="time" name="hora_hecho" step="1" value="{{ $horaVal }}">
</div>


      <div class="field col-6">
        <label>Lugar del hecho</label>
        <input type="text" name="lugar_hecho"
               value="{{ old('lugar_hecho', $detalle->lugar_hecho ?? '') }}"
               placeholder="p.ej. Av. Abraham Calazacón, Zona Rosa">
      </div>
	  
	  
	  
	  
	  <div class="field col-6">
  <label>Fecha de levantamiento</label>
  <input type="date" name="fecha_levantamiento"
         value="{{ old('fecha_levantamiento', optional($detalle->fecha_levantamiento ?? null)->format('Y-m-d')) }}">
</div>

<div class="field col-6">
  <label>Hora de levantamiento</label>
  @php
    $hlRaw = old('hora_levantamiento', $detalle->hora_levantamiento ?? null);
    $hlVal = null;
    if($hlRaw!==null && $hlRaw!==''){
      try { $hlVal = \Carbon\Carbon::parse($hlRaw)->format('H:i:s'); }
      catch(\Throwable $e){
        $s=(string)$hlRaw;
        if(preg_match('/^\d{2}:\d{2}:\d{2}$/',$s)) $hlVal=$s;
        elseif(preg_match('/^\d{2}:\d{2}$/',$s))   $hlVal=$s.':00';
      }
    }
  @endphp
  <input type="time" name="hora_levantamiento" step="1" value="{{ $hlVal }}">
</div>

      <div class="field col-12">
        <label>Seleccione en el mapa:</label>
        <div id="map"></div>
      </div>



<div class="field col-12">
  <label>Coordenadas (lat,lng)</label>
  <div style="display:flex; gap:.5rem; align-items:center">
    <input id="coord" type="text" name="coordenadas"
           value="{{ old('coordenadas', $detalle->coordenadas ?? '') }}"
           placeholder="-0.239389, -79.165556" style="flex:1">
    <button type="button" id="btn-go-coord"  style="white-space:nowrap">Ir a coord.</button>
    <button type="button" id="btn-geolocate" style="white-space:nowrap">Ubicación actual</button>
  </div>
  <small style="color:#64748b">Formato: <code>LAT, LNG</code> (ej. <code>-0.239389, -79.165556</code>). Acepta coma o espacio como separador.</small>
</div>


      {{-- autocompletados --}}
      <div class="field col-4">
        <label>ZONA (autocompletado)</label>
        <input id="input-zona" name="zona" value="{{ old('zona',$detalle->zona??'') }}" readonly>
      </div>
      <div class="field col-4">
        <label>SUBZONA</label>
        <input id="input-subzona" name="subzona" value="{{ old('subzona',$detalle->subzona??'') }}" readonly>
      </div>
      <div class="field col-4">
        <label>DISTRITO</label>
        <input id="input-distrito" name="distrito" value="{{ old('distrito',$detalle->distrito??'') }}" readonly>
      </div>

      <div class="field col-6">
        <label>CIRCUITO</label>
        <input id="input-circuito" name="circuito" value="{{ old('circuito',$detalle->circuito??'') }}" readonly>
      </div>
      <div class="field col-6">
        <label>SUBCIRCUITO</label>
        <input id="input-subcircuito" name="subcircuito" value="{{ old('subcircuito',$detalle->subcircuito??'') }}" readonly>
      </div>
    </div>
  </div>

  {{-- 3. Tipificación y pericias --}}
  <div class="section">
    <h3>3. Tipificación y pericias</h3>

    <div class="form-grid">
      <div class="field col-6">
        <label>¿Asiste Criminalística?</label>
        <select name="criminalistica" class="ctrl">
          <option value="">— Seleccione —</option>
          <option value="SI" {{ strtoupper($criminalistica)==='SI'?'selected':'' }}>SI</option>
          <option value="NO" {{ strtoupper($criminalistica)==='NO'?'selected':'' }}>NO</option>
        </select>
      </div>

      <div class="field col-3">
        <label>Tipo de arma</label>
        <select name="tipo_arma" class="ctrl" data-other="#tipo_arma_otro">
          <option value="">— Seleccione —</option>
          @foreach($ARMA_OPTS as $o)
            <option value="{{ $o }}" {{ $armaSelectVal===$o?'selected':'' }}>{{ $o }}</option>
          @endforeach
          <option value="OTRO" {{ $armaSelectVal==='OTRO'?'selected':'' }}>OTRO</option>
        </select>
        <input id="tipo_arma_otro" type="text" class="ctrl" name="tipo_arma_otro"
               placeholder="Especifique otro…" value="{{ $armaOtroVal }}"
               style="margin-top:6px; {{ $armaSelectVal==='OTRO'?'':'display:none' }}">
      </div>

      <div class="field col-3">
        <label>¿Indicios? (Sí/No)</label>
        <select name="indicios" class="ctrl">
          <option value="">— Seleccione —</option>
          <option value="SI" {{ strtoupper($indicios)==='SI'?'selected':'' }}>SI</option>
          <option value="NO" {{ strtoupper($indicios)==='NO'?'selected':'' }}>NO</option>
        </select>
      </div>

      <div class="field col-4">
        <label>Tipo de delito</label>
        <select name="tipo_delito" class="ctrl" data-other="#tipo_delito_otro">
          <option value="">— Seleccione —</option>
          @foreach($DELITO_OPTS as $o)
            <option value="{{ $o }}" {{ $delSelectVal===$o?'selected':'' }}>{{ $o }}</option>
          @endforeach
          <option value="OTRO" {{ $delSelectVal==='OTRO'?'selected':'' }}>OTRO</option>
        </select>
        <input id="tipo_delito_otro" type="text" class="ctrl" name="tipo_delito_otro"
               placeholder="Especifique otro…" value="{{ $delOtroVal }}"
               style="margin-top:6px; {{ $delSelectVal==='OTRO'?'':'display:none' }}">
      </div>

      <div class="field col-4">
        <label>Estado del caso</label>
        <select name="estado_caso" class="ctrl" data-other="#estado_caso_otro">
          <option value="">— Seleccione —</option>
          @foreach($ESTADO_OPTS as $o)
            <option value="{{ $o }}" {{ $estSelectVal===$o?'selected':'' }}>{{ $o }}</option>
          @endforeach
          <option value="OTRO" {{ $estSelectVal==='OTRO'?'selected':'' }}>OTRO</option>
        </select>
        <input id="estado_caso_otro" type="text" class="ctrl" name="estado_caso_otro"
               placeholder="Especifique otro…" value="{{ $estOtroVal }}"
               style="margin-top:6px; {{ $estSelectVal==='OTRO'?'':'display:none' }}">
      </div>

      <div class="field col-4">
        <label>Motivación</label>
        <select name="motivacion" class="ctrl" data-other="#motivacion_otro">
          <option value="">— Seleccione —</option>
          @foreach($MOTIV_OPTS as $o)
            <option value="{{ $o }}" {{ $motSelectVal===$o?'selected':'' }}>{{ $o }}</option>
          @endforeach
          <option value="OTRO" {{ $motSelectVal==='OTRO'?'selected':'' }}>OTRO</option>
        </select>
        <input id="motivacion_otro" type="text" class="ctrl" name="motivacion_otro"
               placeholder="Especifique otro…" value="{{ $motOtroVal }}"
               style="margin-top:6px; {{ $motSelectVal==='OTRO'?'':'display:none' }}">
      </div>

      <div class="field col-12">
        <label>Justificación</label>
        <textarea name="justificacion" class="textarea-l">{{ old('justificacion', $detalle->justificacion ?? '') }}</textarea>
      </div>
    </div>
  </div>

  {{-- ===== 4 Fallecidos ===== --}}
  <h3 style="margin-top:24px">4. Fallecidos (Occisos / Interfectos)</h3>
  <table id="tbl-fallecidos" style="width:100%;border-collapse:collapse;margin-top:.5rem">
    <thead>
      <tr>
        <th style="text-align:left">Etiqueta</th>
        <th style="text-align:left">Nombres</th>
        <th style="text-align:left">Apellidos</th>
        <th style="text-align:left">Cédula</th>
        <th style="text-align:left">Edad</th>
        <th style="text-align:left">Sexo</th>
        <th style="text-align:left">Observación</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @php $fallecidosOld = old('fallecidos', isset($fallecidos)? $fallecidos->toArray() : []); @endphp
      @forelse($fallecidosOld as $i => $v)
        <tr class="row-basic">
          <td><input class="fld" name="fallecidos[{{ $i }}][etiqueta]" value="{{ $v['etiqueta'] ?? chr(65+$i) }}" readonly></td>
          <td><input class="fld" name="fallecidos[{{ $i }}][nombres]" value="{{ $v['nombres'] ?? '' }}"></td>
          <td><input class="fld" name="fallecidos[{{ $i }}][apellidos]" value="{{ $v['apellidos'] ?? '' }}"></td>
          <td><input class="fld" name="fallecidos[{{ $i }}][cedula]" value="{{ $v['cedula'] ?? '' }}"></td>
          <td><input class="fld" name="fallecidos[{{ $i }}][edad]" value="{{ $v['edad'] ?? '' }}" style="max-width:70px"></td>
          <td>
            @php $sx = $v['sexo'] ?? ''; @endphp
            <select class="fld" name="fallecidos[{{ $i }}][sexo]">
              <option value="">–</option>
              <option value="M" {{ $sx=='M'?'selected':'' }}>M</option>
              <option value="F" {{ $sx=='F'?'selected':'' }}>F</option>
            </select>
          </td>
          <td><input class="fld" name="fallecidos[{{ $i }}][observacion]" value="{{ $v['observacion'] ?? '' }}"></td>
          <td style="white-space:nowrap">
            <button type="button" class="btn-toggle-more">Más</button>
            <button type="button" class="btn-del-row">✕</button>
          </td>
        </tr>
        <tr class="row-more" style="display:none">
          <td colspan="8">
            <div class="more-grid">
              <div><label>Alias</label><input class="fld" name="fallecidos[{{ $i }}][alias]" value="{{ $v['alias'] ?? '' }}"></div>
              <div><label>Nacionalidad</label><input class="fld" name="fallecidos[{{ $i }}][nacionalidad]" value="{{ $v['nacionalidad'] ?? '' }}"></div>
              <div><label>Profesión/Ocupación</label><input class="fld" name="fallecidos[{{ $i }}][ocupacion]" value="{{ $v['ocupacion'] ?? '' }}"></div>
              <div><label>Movilización</label><input class="fld" name="fallecidos[{{ $i }}][movilizacion]" value="{{ $v['movilizacion'] ?? '' }}"></div>
              
			  
		<div>
		  <label>Antecedentes</label>
		  @php $ant = $v['antecedentes'] ?? ''; @endphp
		  <select class="fld has-detail"
				  data-target="#f-ante-{{ $i }}"
				  name="fallecidos[{{ $i }}][antecedentes]">
			<option value="">–</option>
			<option value="Sí" {{ $ant=='Sí'?'selected':'' }}>Sí</option>
			<option value="No" {{ $ant=='No'?'selected':'' }}>No</option>
		  </select>

		  <input id="f-ante-{{ $i }}"
				 class="fld"
				 name="fallecidos[{{ $i }}][antecedentes_det]"
				 placeholder="Detalle (p. ej. 2015 ASESINATO)"
				 value="{{ $v['antecedentes_det'] ?? '' }}"
				 style="{{ ($ant==='Sí') ? '' : 'display:none' }}">
		</div>

			  
			  
			  
			  
			  {{-- SATJE / Judicatura --}}
<div>
  <label>SATJE / Judicatura</label>
  @php $sat = $v['sajte'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#f-satje-{{ $i }}"
          name="fallecidos[{{ $i }}][sajte]">
    <option value="">–</option>
    <option value="Sí" {{ $sat=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $sat=='No'?'selected':'' }}>No</option>
  </select>
  <input id="f-satje-{{ $i }}"
         class="fld"
         name="fallecidos[{{ $i }}][sajte_det]"
         placeholder="Detalle (p. ej. 2015 PROCESO…)"
         value="{{ $v['sajte_det'] ?? '' }}"
         style="{{ ($sat==='Sí') ? '' : 'display:none' }}">
</div>

{{-- Noticia del delito (Fiscalía) --}}
<div>
  <label>Noticia del delito (Fiscalía)</label>
  @php $nf = $v['noticia_fiscalia'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#f-nf-{{ $i }}"
          name="fallecidos[{{ $i }}][noticia_fiscalia]">
    <option value="">–</option>
    <option value="Sí" {{ $nf=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $nf=='No'?'selected':'' }}>No</option>
  </select>
  <input id="f-nf-{{ $i }}"
         class="fld"
         name="fallecidos[{{ $i }}][noticia_fiscalia_det]"
         placeholder="Detalle (p. ej. 2015 ASESINATO)"
         value="{{ $v['noticia_fiscalia_det'] ?? '' }}"
         style="{{ ($nf==='Sí') ? '' : 'display:none' }}">
</div>

{{-- GAO / Cargo-Función --}}
<div>
  <label>GAO / Cargo-Función</label>
  @php $gao = $v['gao'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#f-gao-{{ $i }}"
          name="fallecidos[{{ $i }}][gao]">
    <option value="">–</option>
    <option value="Sí" {{ $gao=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $gao=='No'?'selected':'' }}>No</option>
  </select>
  <input id="f-gao-{{ $i }}"
         class="fld"
         name="fallecidos[{{ $i }}][gao_det]"
         placeholder="Detalle (p. ej. CHONERO / SICARIO)"
         value="{{ $v['gao_det'] ?? '' }}"
         style="{{ ($gao==='Sí') ? '' : 'display:none' }}">
</div>

			
			
			
			
			</div>
          </td>
        </tr>
      @empty
      @endforelse
    </tbody>
  </table>
  <button type="button" id="btn-add-fallecido" style="margin-top:.5rem">+ Agregar occiso/interfecto</button>

  {{-- ===== Heridos ===== --}}
  <h3 style="margin-top:24px">Heridos</h3>
  <table id="tbl-heridos" style="width:100%;border-collapse:collapse;margin-top:.5rem">
    <thead>
      <tr>
        <th style="text-align:left">Etiqueta</th>
        <th style="text-align:left">Nombres</th>
        <th style="text-align:left">Apellidos</th>
        <th style="text-align:left">Cédula</th>
        <th style="text-align:left">Edad</th>
        <th style="text-align:left">Sexo</th>
        <th style="text-align:left">Observación</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @php $heridosOld = old('heridos', isset($heridos)? $heridos->toArray() : []); @endphp
      @forelse($heridosOld as $i => $v)
        <tr class="row-basic">
          <td><input class="fld" name="heridos[{{ $i }}][etiqueta]" value="{{ $v['etiqueta'] ?? chr(65+$i) }}" readonly></td>
          <td><input class="fld" name="heridos[{{ $i }}][nombres]" value="{{ $v['nombres'] ?? '' }}"></td>
          <td><input class="fld" name="heridos[{{ $i }}][apellidos]" value="{{ $v['apellidos'] ?? '' }}"></td>
          <td><input class="fld" name="heridos[{{ $i }}][cedula]" value="{{ $v['cedula'] ?? '' }}"></td>
          <td><input class="fld" name="heridos[{{ $i }}][edad]" value="{{ $v['edad'] ?? '' }}" style="max-width:70px"></td>
          <td>
            @php $sx = $v['sexo'] ?? ''; @endphp
            <select class="fld" name="heridos[{{ $i }}][sexo]">
              <option value="">–</option>
              <option value="M" {{ $sx=='M'?'selected':'' }}>M</option>
              <option value="F" {{ $sx=='F'?'selected':'' }}>F</option>
            </select>
          </td>
          <td><input class="fld" name="heridos[{{ $i }}][observacion]" value="{{ $v['observacion'] ?? '' }}"></td>
          <td style="white-space:nowrap">
            <button type="button" class="btn-toggle-more">Más</button>
            <button type="button" class="btn-del-row">✕</button>
          </td>
        </tr>
        <tr class="row-more" style="display:none">
          <td colspan="8">
            <div class="more-grid">
              <div><label>Alias</label><input class="fld" name="heridos[{{ $i }}][alias]" value="{{ $v['alias'] ?? '' }}"></div>
              <div><label>Nacionalidad</label><input class="fld" name="heridos[{{ $i }}][nacionalidad]" value="{{ $v['nacionalidad'] ?? '' }}"></div>
              <div><label>Profesión/Ocupación</label><input class="fld" name="heridos[{{ $i }}][ocupacion]" value="{{ $v['ocupacion'] ?? '' }}"></div>
              <div><label>Movilización</label><input class="fld" name="heridos[{{ $i }}][movilizacion]" value="{{ $v['movilizacion'] ?? '' }}"></div>
             


<div>
  <label>Antecedentes</label>
  @php $ant = $v['antecedentes'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#h-ante-{{ $i }}"
          name="heridos[{{ $i }}][antecedentes]">
    <option value="">–</option>
    <option value="Sí" {{ $ant=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $ant=='No'?'selected':'' }}>No</option>
  </select>

  <input id="h-ante-{{ $i }}"
         class="fld"
         name="heridos[{{ $i }}][antecedentes_det]"
         placeholder="Detalle (p. ej. 2015 ASESINATO)"
         value="{{ $v['antecedentes_det'] ?? '' }}"
         style="{{ ($ant==='Sí') ? '' : 'display:none' }}">
</div>





{{-- SATJE / Judicatura --}}
<div>
  <label>SATJE / Judicatura</label>
  @php $sat = $v['sajte'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#h-satje-{{ $i }}"
          name="heridos[{{ $i }}][sajte]">
    <option value="">–</option>
    <option value="Sí" {{ $sat=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $sat=='No'?'selected':'' }}>No</option>
  </select>
  <input id="h-satje-{{ $i }}"
         class="fld"
         name="heridos[{{ $i }}][sajte_det]"
         placeholder="Detalle (p. ej. 2015 PROCESO…)"
         value="{{ $v['sajte_det'] ?? '' }}"
         style="{{ ($sat==='Sí') ? '' : 'display:none' }}">
</div>

{{-- Noticia del delito (Fiscalía) --}}
<div>
  <label>Noticia del delito (Fiscalía)</label>
  @php $nf = $v['noticia_fiscalia'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#h-nf-{{ $i }}"
          name="heridos[{{ $i }}][noticia_fiscalia]">
    <option value="">–</option>
    <option value="Sí" {{ $nf=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $nf=='No'?'selected':'' }}>No</option>
  </select>
  <input id="h-nf-{{ $i }}"
         class="fld"
         name="heridos[{{ $i }}][noticia_fiscalia_det]"
         placeholder="Detalle (p. ej. 2015 ASESINATO)"
         value="{{ $v['noticia_fiscalia_det'] ?? '' }}"
         style="{{ ($nf==='Sí') ? '' : 'display:none' }}">
</div>

{{-- GAO / Cargo-Función --}}
<div>
  <label>GAO / Cargo-Función</label>
  @php $gao = $v['gao'] ?? ''; @endphp
  <select class="fld has-detail"
          data-target="#h-gao-{{ $i }}"
          name="heridos[{{ $i }}][gao]">
    <option value="">–</option>
    <option value="Sí" {{ $gao=='Sí'?'selected':'' }}>Sí</option>
    <option value="No" {{ $gao=='No'?'selected':'' }}>No</option>
  </select>
  <input id="h-gao-{{ $i }}"
         class="fld"
         name="heridos[{{ $i }}][gao_det]"
         placeholder="Detalle (p. ej. CHONERO / SICARIO)"
         value="{{ $v['gao_det'] ?? '' }}"
         style="{{ ($gao==='Sí') ? '' : 'display:none' }}">
</div>

            
			
			
			
			
			</div>
          </td>
        </tr>
      @empty
      @endforelse
    </tbody>
  </table>
  <button type="button" id="btn-add-herido" style="margin-top:.5rem">+ Agregar herido</button>

  {{-- 5. Circunstancias --}}
  <div class="section">
    <h3>5. Circunstancias</h3>
    <div class="form-grid">
      <div class="field col-12">
        <textarea name="circunstancias" class="textarea-xl">{{ old('circunstancias', $detalle->circunstancias ?? '') }}</textarea>
      </div>
    </div>
  </div>

  {{-- 6. Entrevistas --}}
  <div class="section">
    <h3>6. Entrevistas</h3>
    <div class="form-grid">
      <div class="field col-12">
        <textarea name="entrevistas[]" class="textarea-l"
          placeholder="Puedes pegar varias entrevistas en este cuadro…">{{ old('entrevistas.0', isset($detalle->entrevistas)? (is_array($detalle->entrevistas)? implode("\n• ", $detalle->entrevistas) : $detalle->entrevistas) : '') }}</textarea>
      </div>
    </div>
  </div>

  {{-- 7. Actividades --}}
  <div class="section">
    <h3>7. Actividades</h3>
    <div class="form-grid">
      <div class="field col-12">
        <textarea name="actividades[]" class="textarea-l"
          placeholder="- Verificación de cámaras…&#10;- Entrevista a familiares…">{{ old('actividades.0', isset($detalle->actividades)? (is_array($detalle->actividades)? implode("\n- ", $detalle->actividades) : $detalle->actividades) : '') }}</textarea>
      </div>
    </div>
  </div>
  
{{-- 8. Indicios --}}
<div class="section">
  <h3>8. Indicios</h3>
  <div class="form-grid">
    <div class="field col-12">
      <textarea name="indicios_detalle" class="textarea-l"
        placeholder="- 42 VAINAS 9MM; - 01 BALA DEFORMADA">{{ old('indicios_detalle', $detalle->indicios_detalle ?? '') }}</textarea>
    </div>
  </div>
</div>


{{-- 9. Reporta --}}
<div class="section">
  <h3>9. Reporta</h3>
  <div class="form-grid">
    <div class="field col-12">
      @php
        $agencias      = config('agencias.dinased');
        $repSelectVal  = old('reporta', $detalle->reporta ?? '');
        // si lo guardado no está en la lista, tratarlo como "OTRO"
        $isOther       = $repSelectVal && !in_array($repSelectVal, $agencias, true);
        $selectValue   = $isOther ? 'OTRO' : $repSelectVal;
        $repOtroVal    = old('reporta_otro', $isOther ? $repSelectVal : '');
      @endphp

      <select name="reporta" class="ctrl" data-other="#reporta_otro">
        <option value="">— Seleccione —</option>
        @foreach($agencias as $o)
          <option value="{{ $o }}" {{ $selectValue === $o ? 'selected' : '' }}>{{ $o }}</option>
        @endforeach
        <option value="OTRO" {{ $selectValue === 'OTRO' ? 'selected' : '' }}>OTRO</option>
      </select>

      <input id="reporta_otro" type="text" class="ctrl" name="reporta_otro"
             placeholder="Especifique otro…"
             value="{{ $repOtroVal }}"
             style="margin-top:6px; {{ $selectValue==='OTRO' ? '' : 'display:none' }}">
    </div>
  </div>
</div>


  <button type="submit" style="margin-top:.5rem">Guardar detalle</button>
</form>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
          integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>

  <script>
  // ------ Mostrar cajita "OTRO" cuando el select tenga data-other ------
  (function(){
    document.addEventListener('change', function(e){
      const sel = e.target.closest('select[data-other]');
      if(!sel) return;
      const target = document.querySelector(sel.dataset.other);
      if(!target) return;
      target.style.display = (sel.value === 'OTRO') ? '' : 'none';
      if(sel.value !== 'OTRO') target.value = '';
      else if(!target.value) target.focus();
    });
  })();
  </script>

  
  
  <script>
(function(){
  // -------- helpers UI --------
  const $coord = document.getElementById('coord');
  const $zona  = document.getElementById('input-zona');
  const $subz  = document.getElementById('input-subzona');
  const $dist  = document.getElementById('input-distrito');
  const $circ  = document.getElementById('input-circuito');
  const $subc  = document.getElementById('input-subcircuito');

  function setCoord(latlng){
    $coord.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
  }

  function normalizaZona(zStr){
    if(!zStr) return "";
    const m = (zStr+"").match(/\d+/);
    return m ? m[0].padStart(2,"0") : "";
  }
  function parsePopupInfo(html){
    const out = {};
    if(!html) return out;
    const re = /<td>([^<]+)<\/td>\s*<td>([^<]*)<\/td>/gi;
    let m;
    while((m = re.exec(html))!==null){
      const k = m[1].trim().toUpperCase();
      const v = m[2].trim();
      out[k] = v;
    }
    return out;
  }
  function popupToAttrs(p){
    const zonaTxt  = p["ZONA"] || "";
    return {
      zona_num    : normalizaZona(zonaTxt),
      zona        : zonaTxt,
      provincia   : p["PROVINCIA"] || "",
      distrito    : p["NOMBRE_DIS"] || "",
      circuito    : p["NOMBRE_CIR"] || "",
      cod_subcir  : p["COD_SUBCIR"] || "",
      subcircuito : p["NOMBRE_SUB"] || ""
    };
  }

  // punto inicial (acepta coma, punto y coma o espacio)
  const parsed = ($coord.value || "-0.239389, -79.165556")
                  .replace(/;/g, ',')
                  .split(/[,\s]+/)
                  .map(s => parseFloat(s));
  const start  = (parsed.length>=2 && parsed.slice(0,2).every(n=>Number.isFinite(n)))
                  ? [parsed[0], parsed[1]] : [-0.239389,-79.165556];

  const map = L.map('map').setView(start, 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '&copy; OpenStreetMap'
  }).addTo(map);
  const marker = L.marker(start, {draggable:true}).addTo(map);

  // Cargar datos de apoyo
  window._TERR = {};
  fetch('{{ asset('geo/territorial_lookup.json') }}')
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(j => { window._TERR = j || {}; })
    .catch(()=>{ window._TERR = {}; });

  fetch('{{ asset('geo/Polygons_Subci_FeaturesToJSO.geojson') }}')
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(geo => {
      L.geoJSON(geo, {
        style:{ color:'#3b82f6', weight:1, fillOpacity:.05 },
        onEachFeature:(f, layer)=> layer.bindTooltip(f.properties?.Name || f.properties?.NOMBRE_SUB || 'Subcircuito')
      }).addTo(map);
    }).catch(()=>{});

  let pointsFC = null;
  fetch('{{ asset('geo/Points_ExportF_FeaturesToJSO.json') }}')
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(data => {
      const feats = (data.features || []).map(f => {
        const x = f.geometry?.x ?? f.geometry?.coordinates?.[0];
        const y = f.geometry?.y ?? f.geometry?.coordinates?.[1];
        if (typeof x !== 'number' || typeof y !== 'number') return null;

        const rawPopup = f.attributes?.PopupInfo || "";
        const kv = parsePopupInfo(rawPopup);
        let attrs = popupToAttrs(kv);

        if (attrs.cod_subcir && window._TERR[attrs.cod_subcir]) {
          const t = window._TERR[attrs.cod_subcir];
          attrs.distrito    = attrs.distrito    || t.distrito    || "";
          attrs.circuito    = attrs.circuito    || t.circuito    || "";
          attrs.subcircuito = attrs.subcircuito || t.subcircuito || "";
          attrs.zona_num    = attrs.zona_num    || (t.zona ? (t.zona+"").padStart(2,"0") : "");
          attrs.provincia   = attrs.provincia   || t.provincia   || "";
        }

        return turf.point([x, y], attrs);
      }).filter(Boolean);

      pointsFC = turf.featureCollection(feats);
    })
    .catch(() => { pointsFC = null; });

  function autocompleteByNearest(latlng){
    if (!pointsFC || !pointsFC.features.length) return;
    const clicked = turf.point([latlng.lng, latlng.lat]);
    const nearest = turf.nearestPoint(clicked, pointsFC);
    const a = nearest?.properties || {};
    $zona.value  = a.zona_num || a.zona || "";
    $subz.value  = a.provincia || "";
    $dist.value  = a.distrito || "";
    $circ.value  = a.circuito || "";
    $subc.value  = a.subcircuito || "";
  }

  function goTo(latlng, zoom=16){
    marker.setLatLng(latlng);
    map.setView(latlng, zoom);
    setCoord(latlng);
    autocompleteByNearest(latlng);
  }

  // Click en mapa / drag marker
  map.on('click', e => goTo(e.latlng));
  marker.on('dragend', () => goTo(marker.getLatLng()));

  // ---------- Botón: Ir a coord. ----------
  document.getElementById('btn-go-coord')?.addEventListener('click',()=>{
    const txt = ($coord.value || '').trim().replace(/;/g, ',');
    const parts = txt.split(/[,\s]+/).filter(Boolean).map(Number);
    if(parts.length>=2 && parts.slice(0,2).every(n=>Number.isFinite(n))){
      const ll = L.latLng(parts[0], parts[1]);
      goTo(ll);
    } else {
      alert('Coordenadas inválidas.\nUsa: LAT, LNG (ej: -1.23, -78.51)');
    }
  });

  // ---------- Botón: Ubicación actual ----------
  document.getElementById('btn-geolocate')?.addEventListener('click',()=>{
    // Requisito del navegador: HTTPS o localhost
    if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
      alert('Para usar la ubicación actual, abre el sitio en HTTPS o en localhost.');
      return;
    }
    if(!navigator.geolocation){
      alert('Geolocalización no disponible en este navegador.');
      return;
    }
    navigator.geolocation.getCurrentPosition(
      pos=>{
        const ll = L.latLng(pos.coords.latitude, pos.coords.longitude);
        goTo(ll);
        const acc = pos.coords.accuracy || 0;
        if (acc && acc < 1000){
          L.circle(ll, {radius: acc}).addTo(map);
        }
      },
      err=>{
        let msg = 'No se pudo obtener tu ubicación.';
        if (err.code===1) msg = 'Permiso de ubicación denegado. Habilítalo en el navegador.';
        if (err.code===2) msg = 'Posición no disponible.';
        if (err.code===3) msg = 'Tiempo de espera agotado.';
        alert(msg);
      },
      { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
  });

  // autocompletar inicial
  autocompleteByNearest(marker.getLatLng());
})();
</script>

  
  
  <script>
  (function(){
    // estilo uniforme a inputs de tablas
    function styleInputs(scope){
      (scope || document).querySelectorAll('table .fld').forEach(el=>{
        el.style.width = '100%';
        el.style.padding = '.45rem .55rem';
        el.style.border  = '1px solid #cfcfcf';
        el.style.borderRadius = '.5rem';
        el.style.boxSizing = 'border-box';
      });
    }
    styleInputs();

    function nextLetter(idx){ return String.fromCharCode(65 + idx); }

    function buildRow(prefix, idx){
      const letter = nextLetter(idx);
      return `
        <tr class="row-basic">
          <td><input class="fld" name="${prefix}[${idx}][etiqueta]" value="${letter}" readonly></td>
          <td><input class="fld" name="${prefix}[${idx}][nombres]"></td>
          <td><input class="fld" name="${prefix}[${idx}][apellidos]"></td>
          <td><input class="fld" name="${prefix}[${idx}][cedula]"></td>
          <td><input class="fld" name="${prefix}[${idx}][edad]" style="max-width:70px"></td>
          <td>
            <select class="fld" name="${prefix}[${idx}][sexo]">
              <option value="">–</option><option value="M">M</option><option value="F">F</option>
            </select>
          </td>
          <td><input class="fld" name="${prefix}[${idx}][observacion]"></td>
          <td style="white-space:nowrap">
            <button type="button" class="btn-toggle-more">Más</button>
            <button type="button" class="btn-del-row">✕</button>
          </td>
        </tr>
        <tr class="row-more" style="display:none">
          <td colspan="8">
            <div class="more-grid">
              <div><label>Alias</label><input class="fld" name="${prefix}[${idx}][alias]"></div>
              <div><label>Nacionalidad</label><input class="fld" name="${prefix}[${idx}][nacionalidad]"></div>
              <div><label>Profesión/Ocupación</label><input class="fld" name="${prefix}[${idx}][ocupacion]"></div>
              <div><label>Movilización</label><input class="fld" name="${prefix}[${idx}][movilizacion]"></div>
              <div><label>Antecedentes</label>
                <select class="fld" name="${prefix}[${idx}][antecedentes]">
                  <option value="">–</option><option>Sí</option><option>No</option>
                </select>
              </div>
              <div><label>SATJE / Judicatura</label>
                <select class="fld" name="${prefix}[${idx}][sajte]">
                  <option value="">–</option><option>Sí</option><option>No</option>
                </select>
              </div>
              <div><label>Noticia del delito (Fiscalía)</label>
                <select class="fld" name="${prefix}[${idx}][noticia_fiscalia]">
                  <option value="">–</option><option>Sí</option><option>No</option>
                </select>
              </div>
              <div><label>GAO / Cargo-Función</label>
                <select class="fld" name="${prefix}[${idx}][gao]">
                  <option value="">–</option><option>Sí</option><option>No</option>
                </select>
              </div>
            </div>
          </td>
        </tr>
      `;
    }

    function addRow(tbody, prefix){
      const idx = tbody.querySelectorAll('tr.row-basic').length;
      const tmp = document.createElement('tbody');
      tmp.innerHTML = buildRow(prefix, idx);
      while(tmp.firstElementChild){ tbody.appendChild(tmp.firstElementChild); }
      styleInputs(tbody);
    }

    const tbF = document.querySelector('#tbl-fallecidos tbody');
    const tbH = document.querySelector('#tbl-heridos tbody');

    document.getElementById('btn-add-fallecido').addEventListener('click', ()=> addRow(tbF, 'fallecidos'));
    document.getElementById('btn-add-herido')   .addEventListener('click', ()=> addRow(tbH, 'heridos'));

    document.addEventListener('click', e=>{
      if(e.target.classList.contains('btn-del-row')){
        const basic = e.target.closest('tr.row-basic');
        const more  = basic?.nextElementSibling;
        more?.remove(); basic?.remove();
      }
      if(e.target.classList.contains('btn-toggle-more')){
        const basic = e.target.closest('tr.row-basic');
        const more  = basic?.nextElementSibling;
        if(more) more.style.display = (more.style.display==='none'||!more.style.display)?'table-row':'none';
      }
    });
  })();
  </script>

  <script>
    // Semillas para que la UI de víctimas arranque con lo ya guardado
    window._SEED_FALLECIDOS = @json($fallecidos ?? []);
    window._SEED_HERIDOS    = @json($heridos ?? []);
  </script>
  
  <script>
  // Toggle de inputs "detalle" cuando un <select.has-detail> cambie
  (function(){
    function toggleDetail(sel){
      const target = document.querySelector(sel.dataset.target);
      if(!target) return;
      const v = (sel.value || '').toLowerCase();
      const isYes = v === 'sí' || v === 'si' || v === 'sí' || v === 'si '; // contempla acento
      target.style.display = isYes ? '' : 'none';
      if(!isYes) target.value = '';
    }
    // inicial y on-change
    document.querySelectorAll('select.has-detail').forEach(toggleDetail);
    document.addEventListener('change', e=>{
      const sel = e.target.closest('select.has-detail');
      if(sel) toggleDetail(sel);
    });
  })();
</script>

  
  
  
  
@endpush

@endsection
