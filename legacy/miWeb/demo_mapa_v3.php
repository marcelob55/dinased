<?php
// demo_mapa_v2.php
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Demo: Zonas, Distrito, Circuito y Subcircuito</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Turf.js para point-in-polygon -->
  <script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>

  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
    #map { height: 480px; border-radius: 10px; border: 1px solid #ddd; }
    .wrap { max-width: 1100px; margin: 18px auto; padding: 0 12px; }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
    .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-top: 12px; }
    label { font-size: 14px; color: #444; margin-bottom: 4px; display: block; }
    input[readonly] { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #ccc; background: #f9fafb; }
    .hint { margin-top: 8px; color: #777; font-size: 12px; }
  </style>
</head>
<body>
<div class="wrap">
  <h2>Haga clic en el mapa para obtener Zona, Distrito, Circuito y Subcircuito.</h2>
  <div id="map"></div>
  <div class="hint">Usando: <code>Polygons_Subci_FeaturesToJSO.geojson</code> + <code>Points_ExportF_FeaturesToJSO.json</code></div>

  <div class="row">
    <div>
      <label>Coordenadas</label>
      <input id="coord" readonly>
    </div>
    <div>
      <label>Zona (01–09)</label>
      <input id="zona" readonly>
    </div>
  </div>

  <div class="row-3">
    <div>
      <label>Subzona / Provincia</label>
      <input id="provincia" readonly>
    </div>
    <div>
      <label>Distrito</label>
      <input id="distrito" readonly>
    </div>
    <div>
      <label>Circuito</label>
      <input id="circuito" readonly>
    </div>
  </div>

  <div class="row">
    <div>
      <label>Subcircuito</label>
      <input id="subcircuito" readonly>
    </div>
  </div>
</div>

<script>
const MAP_CENTER = [-1.8, -78.7]; // Ecuador aprox.
const POLY_URL   = 'Polygons_Subci_FeaturesToJSO.geojson';
const POINTS_URL = 'Points_ExportF_FeaturesToJSO.json';

const map = L.map('map').setView(MAP_CENTER, 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19, attribution: '&copy; OpenStreetMap'
}).addTo(map);

// Helpers
const $ = sel => document.querySelector(sel);
const fmtLonLat = (lat, lon) =>
  `${Number(lat).toFixed(6)}, ${Number(lon).toFixed(6)}`;

// Quita prefijos tipo "14D02C04S01 - PABLO SEXTO 1" -> "PABLO SEXTO 1"
const sinCodigo = (s='') => {
  if (!s) return 'N/A';
  const parts = s.split(' - ');
  return parts.length > 1 ? parts.pop().trim() : s.trim();
};

// Extrae pares clave/valor de la tabla HTML guardada en PopupInfo
function parsePopupTable(html='') {
  const out = {};
  try {
    const el = document.createElement('div');
    el.innerHTML = html;
    const tds = el.querySelectorAll('td');
    for (let i = 0; i < tds.length - 1; i += 2) {
      const k = tds[i].textContent.trim();
      const v = tds[i+1].textContent.trim();
      if (k) out[k] = v;
    }
  } catch(e) {}
  return out;
}

// Normaliza valor de zona a 01..09, si no corresponde => "N/A"
function normalizarZona(zStr='') {
  const m = (zStr || '').match(/\d+/);
  if (!m) return 'N/A';
  const n = Number(m[0]);
  if (n >= 1 && n <= 9) return String(n).padStart(2,'0');
  return 'N/A';
}

// Capas cargadas
let polyLayer;       // GeoJSON de subcircuitos (polígonos)
let pointsFeatures;  // Array de puntos (esriJSON convertido)

// Carga de puntos (esriJSON)
async function loadPoints() {
  const data = await fetch(POINTS_URL).then(r => r.json());
  // Normalizamos a un arreglo sencillo: {lng,lat, popupKV}
  pointsFeatures = (data.features || []).map(f => {
    const kv = parsePopupTable(f.attributes?.PopupInfo || '');
    return {
      lng: f.geometry?.x,
      lat: f.geometry?.y,
      kv
    };
  });
}

// Busca dentro del polígono algún punto para leer ZONA y Provincia
function pickPointDataInsidePolygon(turfPoly) {
  if (!pointsFeatures || !turfPoly) return null;
  for (const p of pointsFeatures) {
    const pt = turf.point([p.lng, p.lat]);
    if (turf.booleanPointInPolygon(pt, turfPoly)) {
      return {
        zona: normalizarZona(p.kv['ZONA'] || ''),
        provincia: (p.kv['NOMBRE_DIS'] || 'N/A').trim()
      };
    }
  }
  return null;
}

// Estilo de polígonos
function styleDefault() {
  return { color: '#111', weight: 1, fillColor: '#6aa3ff', fillOpacity: 0.15 };
}
function styleHighlight() {
  return { color: '#111', weight: 2, fillColor: '#00c853', fillOpacity: 0.35 };
}

// Carga polígonos y setea evento de click global
async function init() {
  await loadPoints();

  const gj = await fetch(POLY_URL).then(r => r.json());

  polyLayer = L.geoJSON(gj, {
    style: styleDefault,
    onEachFeature: (feature, layer) => {
      // guardamos la geometría Turf para pruebas rápidas
      const turfPoly = turf.polygon(feature.geometry.coordinates);
      layer._turf = turfPoly;
    }
  }).addTo(map);

  map.fitBounds(polyLayer.getBounds());

  let lastHighlighted = null;
  let marker = null;

  map.on('click', (e) => {
    const latlng = e.latlng;
    $('#coord').value = fmtLonLat(latlng.lat, latlng.lng);

    // Reseteo inputs
    $('#zona').value        = '';
    $('#provincia').value   = '';
    $('#distrito').value    = '';
    $('#circuito').value    = '';
    $('#subcircuito').value = '';

    // Encuentra el polígono clicado (point-in-polygon con Turf)
    let foundLayer = null;
    polyLayer.eachLayer(ly => {
      if (!foundLayer && turf.booleanPointInPolygon(turf.point([latlng.lng, latlng.lat]), ly._turf)) {
        foundLayer = ly;
      }
    });

    if (!foundLayer) {
      if (marker) { map.removeLayer(marker); marker = null; }
      if (lastHighlighted) { lastHighlighted.setStyle(styleDefault()); lastHighlighted = null; }
      return;
    }

    // Resalta selección
    if (lastHighlighted) lastHighlighted.setStyle(styleDefault());
    foundLayer.setStyle(styleHighlight());
    lastHighlighted = foundLayer;

    // Información desde el polígono (PopupInfo)
    const props = foundLayer.feature.properties || {};
    const kv = parsePopupTable(props.PopupInfo || '');

    // Nombres limpios (sin códigos)
    const distrito    = (kv['NAM_DISTRI'] || 'N/A').trim();
    const circuitoRaw = (kv['NAM_CIRCUI'] || '').trim();
    const subcRaw     = (kv['NAM_SUBCIR'] || '').trim();

    $('#distrito').value    = distrito || 'N/A';
    $('#circuito').value    = sinCodigo(circuitoRaw || 'N/A');
    $('#subcircuito').value = sinCodigo(subcRaw || 'N/A');

    // Zona y Provincia desde los PUNTOS que caen dentro del polígono
    const pInfo = pickPointDataInsidePolygon(foundLayer._turf) || { zona:'N/A', provincia:'N/A' };
    $('#zona').value      = pInfo.zona;
    $('#provincia').value = pInfo.provincia;

    // Marcador
    if (marker) map.removeLayer(marker);
    marker = L.marker(latlng).addTo(map);
    const etiqueta = (props.Name || '').toString();
    marker.bindPopup(etiqueta || 'Subcircuito').openPopup();
  });
}

init();
</script>
</body>
</html>
