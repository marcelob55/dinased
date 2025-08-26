@extends('layouts.app')

@section('content')
<h1>Alimentar detalle — {{ $caso->numero_caso }}</h1>

<form method="POST" action="{{ route('detalle.store', $caso) }}">
  @csrf

  @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
    <style>
      .form-grid{display:grid;gap:.75rem}
      .cols-2{grid-template-columns:1fr 1fr}
      .cols-3{grid-template-columns:1fr 1fr 1fr}
      .field{display:flex;flex-direction:column;gap:.35rem}
      .field>label{font-size:.9rem;color:#111}
      .field>input,.field>select,.field>textarea{
        padding:.55rem .65rem;border:1px solid #cfcfcf;border-radius:.5rem;font-size:.95rem
      }
      .section{margin-top:1.25rem}
      .section>h3{margin:0 0 .5rem 0;font-size:1.05rem}
      #map{height:300px;border-radius:.75rem;border:1px solid #cfcfcf}
      @media (min-width: 720px){ #map{height:360px} }
    </style>
  @endpush

  {{-- 1. Verificación --}}
<div class="section">
  <h3>1. Verificación del evento</h3>
  <div class="form-grid">
    <div class="field">
      <label>Verificación</label>
      <input type="text" name="verificacion"
             value="{{ old('verificacion', $detalle->verificacion ?? '') }}"
             placeholder="Describa la verificación del evento">
    </div>
    <div class="field">
      <label>Código ECU 911</label>
      <input type="text" name="codigo_ecu"
             value="{{ old('codigo_ecu', $detalle->codigo_ecu ?? '') }}"
             placeholder="p.ej. 38953">
    </div>
  </div>
</div>


  {{-- 2. Ubicación + mapa --}}
  <div class="section">
    <h3>2. Ubicación geográfica</h3>

    <div class="form-grid cols-3">
      <div class="field">
        <label>ESPACIO</label>
        @php $esp = old('espacio', $detalle->espacio ?? 'Público'); @endphp
        <select name="espacio">
          <option {{ $esp=='Público'?'selected':'' }}>Público</option>
          <option {{ $esp=='Privado'?'selected':'' }}>Privado</option>
        </select>
      </div>
      <div class="field">
        <label>ÁREA</label>
        @php $ar = old('area', $detalle->area ?? 'Urbana'); @endphp
        <select name="area">
          <option {{ $ar=='Urbana'?'selected':'' }}>Urbana</option>
          <option {{ $ar=='Rural'?'selected':'' }}>Rural</option>
        </select>
      </div>
      <div class="field">
        <label>Fecha del hecho</label>
        <input type="date" name="fecha_hecho" value="{{ old('fecha_hecho', optional($detalle->fecha_hecho ?? null)->format('Y-m-d')) }}">
      </div>
    </div>

    <div class="form-grid cols-2" style="margin-top:.75rem">
      <div class="field">
        <label>Hora del hecho</label>
        <input type="time" name="hora_hecho" value="{{ old('hora_hecho', $detalle->hora_hecho ?? '') }}">
      </div>
      <div class="field">
        <label>Lugar del hecho</label>
        <input type="text" name="lugar_hecho" value="{{ old('lugar_hecho', $detalle->lugar_hecho ?? '') }}" placeholder="p.ej. Av. Abraham Calazacón, Zona Rosa">
      </div>
    </div>

    <div class="field" style="margin-top:.75rem">
      <label>Seleccione en el mapa:</label>
      <div id="map"></div>
    </div>
    <div class="field" style="margin-top:.5rem">
      <label>Coordenadas (lat,lng)</label>
      <input id="coord" type="text" name="coordenadas"
            value="{{ old('coordenadas', $detalle->coordenadas ?? '') }}"
            placeholder="-0.239389, -79.165556">
    </div>

    {{-- Autocompletado territorial (readonly) --}}
    <div class="form-grid cols-3" style="margin-top:.75rem">
      <div class="field">
        <label>ZONA (autocompletado)</label>
        <input id="input-zona" name="zona" value="{{ old('zona',$detalle->zona??'') }}" readonly>
      </div>
      <div class="field">
        <label>SUBZONA</label>
        <input id="input-subzona" name="subzona" value="{{ old('subzona',$detalle->subzona??'') }}" readonly>
      </div>
      <div class="field">
        <label>DISTRITO</label>
        <input id="input-distrito" name="distrito" value="{{ old('distrito',$detalle->distrito??'') }}" readonly>
      </div>
    </div>

    <div class="form-grid cols-2" style="margin-top:.75rem">
      <div class="field">
        <label>CIRCUITO</label>
        <input id="input-circuito" name="circuito" value="{{ old('circuito',$detalle->circuito??'') }}" readonly>
      </div>
      <div class="field">
        <label>SUBCIRCUITO</label>
        <input id="input-subcircuito" name="subcircuito" value="{{ old('subcircuito',$detalle->subcircuito??'') }}" readonly>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
          integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
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

    // --- util: "ZONA 6" -> "06"
    function normalizaZona(zStr){
      if(!zStr) return "";
      const m = (zStr+"").match(/\d+/);
      return m ? m[0].padStart(2,"0") : "";
    }

    // --- parsea la tabla HTML del PopupInfo (ESRI) a {clave:valor}
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

    // --- mapea claves del popup a nombres "bonitos"
    function popupToAttrs(p){
      const zonaTxt  = p["ZONA"] || "";
      return {
        zona_num    : normalizaZona(zonaTxt), // "06"
        zona        : zonaTxt,                // "ZONA 6"
        provincia   : p["PROVINCIA"] || "",  // si existe en tu popup
        distrito    : p["NOMBRE_DIS"] || "",
        circuito    : p["NOMBRE_CIR"] || "",
        cod_subcir  : p["COD_SUBCIR"] || "",
        subcircuito : p["NOMBRE_SUB"] || ""
      };
    }

    // -------- mapa --------
    const parsed = ($coord.value || "-0.239389, -79.165556").split(',').map(s => parseFloat(s.trim()));
    const start  = (parsed.length===2 && parsed.every(n=>!isNaN(n))) ? parsed : [-0.239389,-79.165556];

    const map = L.map('map').setView(start, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
    const marker = L.marker(start, {draggable:true}).addTo(map);

    // --- cargar lookup primero (para enriquecer)
    window._TERR = {};
    fetch('{{ asset('geo/territorial_lookup.json') }}')
      .then(r => r.ok ? r.json() : Promise.reject()).then(data => { window._TERR = data || {}; })
      .catch(()=>{ window._TERR = {}; });

    // --- cargar polígonos (solo visual)
    fetch('{{ asset('geo/Polygons_Subci_FeaturesToJSO.geojson') }}')
      .then(r => r.ok ? r.json() : Promise.reject()).then(geo => {
        L.geoJSON(geo, {
          style:{ color:'#3b82f6', weight:1, fillOpacity:.05 },
          onEachFeature:(f, layer)=>{
            const p = f.properties || {};
            layer.bindTooltip(p.Name || p.NOMBRE_SUB || p.SUBCIRCUITO || 'Subcircuito');
          }
        }).addTo(map);
      }).catch(()=>{});

    // --- cargar puntos con parseo de PopupInfo + enriquecimiento por lookup
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

          // Enriquecer con lookup si hay código:
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

    // --- autocompletar por punto más cercano
    function autocompleteByNearest(latlng){
      if (!pointsFC || !pointsFC.features.length) return;
      const clicked = turf.point([latlng.lng, latlng.lat]);
      const nearest = turf.nearestPoint(clicked, pointsFC);
      const a = nearest?.properties || {};
      $zona.value  = a.zona_num || a.zona || "";
      $subz.value  = a.provincia || "";      // si no viene en popup, se completa por lookup
      $dist.value  = a.distrito || "";
      $circ.value  = a.circuito || "";
      $subc.value  = a.subcircuito || "";
    }

    // eventos
    map.on('click', e => {
      marker.setLatLng(e.latlng);
      setCoord(e.latlng);
      autocompleteByNearest(e.latlng);
    });
    marker.on('dragend', () => {
      const ll = marker.getLatLng();
      setCoord(ll);
      autocompleteByNearest(ll);
    });

    // intento inicial
    autocompleteByNearest(marker.getLatLng());
  })();
  </script>
  
  
  

<script>
(function(){
  // estilo uniforme a inputs
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

  function nextLetter(idx){ return String.fromCharCode(65 + idx); } // A,B,C,...

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
            <option value="">–</option>
            <option value="M">M</option>
            <option value="F">F</option>
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

  // delegación: borrar y alternar “Más”
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



  @endpush

  {{-- 3. Tipificación y pericias --}}
  <h3 style="margin-top:24px">Tipificación y pericias</h3>
  <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px">
    <div>
      <label>¿Asiste Criminalística?</label>
      <input type="text" name="criminalistica" placeholder="Sí / No + nombre" value="{{ old('criminalistica', $detalle->criminalistica ?? '') }}">
    </div>
    <div>
      <label>Tipo de arma</label>
      <input type="text" name="tipo_arma" value="{{ old('tipo_arma', $detalle->tipo_arma ?? '') }}">
    </div>
    <div>
      <label>¿Indicios? (Sí/No)</label>
      <input type="text" name="indicios" placeholder="Sí / No" value="{{ old('indicios', $detalle->indicios ?? '') }}">
    </div>
    <div>
      <label>Tipo de delito</label>
      <input type="text" name="tipo_delito" value="{{ old('tipo_delito', $detalle->tipo_delito ?? '') }}">
    </div>
    <div>
      <label>Estado del caso</label>
      <input type="text" name="estado_caso" value="{{ old('estado_caso', $detalle->estado_caso ?? '') }}">
    </div>
  </div>

  <label>Motivación</label>
  <input type="text" name="motivacion" value="{{ old('motivacion', $detalle->motivacion ?? '') }}">

  <label>Justificación</label>
  <textarea name="justificacion" rows="3">{{ old('justificacion', $detalle->justificacion ?? '') }}</textarea>




{{-- ===== 2.1 Fallecidos (Occisos / Interfectos) ===== --}}
<h3 style="margin-top:24px">Fallecidos (Occisos / Interfectos)</h3>
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
    @php
      // si vienes de validación fallida
      $fallecidosOld = old('fallecidos', isset($fallecidos)? $fallecidos->toArray() : []);
    @endphp

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
            <div><label>Antecedentes</label>
              <select class="fld" name="fallecidos[{{ $i }}][antecedentes]">
                @php $ant = $v['antecedentes'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $ant=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $ant=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>SATJE / Judicatura</label>
              <select class="fld" name="fallecidos[{{ $i }}][sajte]">
                @php $sat = $v['sajte'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $sat=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $sat=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>Noticia del delito (Fiscalía)</label>
              <select class="fld" name="fallecidos[{{ $i }}][noticia_fiscalia]">
                @php $nf = $v['noticia_fiscalia'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $nf=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $nf=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>GAO / Cargo-Función</label>
              <select class="fld" name="fallecidos[{{ $i }}][gao]">
                @php $gao = $v['gao'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $gao=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $gao=='No'?'selected':'' }}>No</option>
              </select>
            </div>
          </div>
        </td>
      </tr>
    @empty
    @endforelse
  </tbody>
</table>
<button type="button" id="btn-add-fallecido" style="margin-top:.5rem">+ Agregar occiso/interfecto</button>

{{-- ===== 2.2 Heridos ===== --}}
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
    @php
      $heridosOld = old('heridos', isset($heridos)? $heridos->toArray() : []);
    @endphp

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
            <div><label>Antecedentes</label>
              <select class="fld" name="heridos[{{ $i }}][antecedentes]">
                @php $ant = $v['antecedentes'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $ant=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $ant=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>SATJE / Judicatura</label>
              <select class="fld" name="heridos[{{ $i }}][sajte]">
                @php $sat = $v['sajte'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $sat=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $sat=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>Noticia del delito (Fiscalía)</label>
              <select class="fld" name="heridos[{{ $i }}][noticia_fiscalia]">
                @php $nf = $v['noticia_fiscalia'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $nf=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $nf=='No'?'selected':'' }}>No</option>
              </select>
            </div>
            <div><label>GAO / Cargo-Función</label>
              <select class="fld" name="heridos[{{ $i }}][gao]">
                @php $gao = $v['gao'] ?? ''; @endphp
                <option value="">–</option>
                <option value="Sí" {{ $gao=='Sí'?'selected':'' }}>Sí</option>
                <option value="No" {{ $gao=='No'?'selected':'' }}>No</option>
              </select>
            </div>
          </div>
        </td>
      </tr>
    @empty
    @endforelse
  </tbody>
</table>
<button type="button" id="btn-add-herido" style="margin-top:.5rem">+ Agregar herido</button>








  <label>Circunstancias</label>
  <textarea name="circunstancias" rows="5">{{ old('circunstancias', $detalle->circunstancias ?? '') }}</textarea>

  {{-- Entrevistas --}}
  <h3 style="margin-top:24px">Entrevistas</h3>
  @php
    $entrevistas = old('entrevistas', $detalle->entrevistas ?? []);
    if (!is_array($entrevistas)) { $entrevistas = json_decode($entrevistas, true) ?: []; }
  @endphp
  <div id="entrevistas">
    @forelse($entrevistas as $e)
      <input type="text" name="entrevistas[]" value="{{ $e }}" style="margin-bottom:6px">
    @empty @endforelse
    <input type="text" name="entrevistas[]" placeholder="Nueva entrevista" style="margin-bottom:6px">
  </div>

  {{-- Actividades --}}
  <h3 style="margin-top:24px">Actividades</h3>
  @php
    $actividades = old('actividades', $detalle->actividades ?? []);
    if (!is_array($actividades)) { $actividades = json_decode($actividades, true) ?: []; }
  @endphp
  <div id="actividades">
    @forelse($actividades as $a)
      <input type="text" name="actividades[]" value="{{ $a }}" style="margin-bottom:6px">
    @empty @endforelse
    <input type="text" name="actividades[]" placeholder="Nueva actividad" style="margin-bottom:6px">
  </div>

  <label style="margin-top:16px">Reporta</label>
  <input type="text" name="reporta" value="{{ old('reporta', $detalle->reporta ?? '') }}">

  <button type="submit">Guardar detalle</button>
</form>

<hr style="margin:28px 0">
<p style="opacity:.7">Próximos módulos: víctimas / heridos / sospechosos / indicios (1:N) / fotos.</p>
@endsection
