<?php
// demo_mapa_v4.php
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Demo: Zonas, Distrito, Circuito y Subcircuito</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
  />
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial}
    .wrap{max-width:1100px;margin:14px auto;padding:0 10px}
    #map{height:520px;border:2px solid #e5e7eb;border-radius:10px}
    .row{display:flex;gap:12px;flex-wrap:wrap;margin-top:10px}
    .field{flex:1 1 240px;display:flex;flex-direction:column}
    .field label{font-size:.9rem;color:#374151;margin-bottom:4px}
    .field input{padding:10px;border:1px solid #d1d5db;border-radius:8px;background:#f9fafb}
    .hint{font-size:.85rem;color:#6b7280;margin:6px 0}
  </style>
</head>
<body>
<div class="wrap">
  <p>Haga clic en el mapa para obtener Zona, Distrito, Circuito y Subcircuito.</p>
  <div id="map"></div>
  <div class="hint">
    Usando: <code>Polygons_Subci_FeaturesToJSO.geojson</code> + <code>Points_ExportF_FeaturesToJSO.json</code>
  </div>

  <div class="row">
    <div class="field">
      <label>Coordenadas</label>
      <input id="coord" readonly>
    </div>
    <div class="field">
      <label>Zona (01–09)</label>
      <input id="zona" readonly>
    </div>
  </div>
  <div class="row">
    <div class="field">
      <label>Subzona / Provincia</label>
      <input id="prov" readonly>
    </div>
    <div class="field">
      <label>Distrito</label>
      <input id="dist" readonly>
    </div>
  </div>
  <div class="row">
    <div class="field">
      <label>Circuito</label>
      <input id="circ" readonly>
    </div>
    <div class="field">
      <label>Subcircuito</label>
      <input id="subc" readonly>
    </div>
  </div>
</div>

<script
  src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
  crossorigin="">
</script>
<!-- Turf para pruebas de punto-en-polígono -->
<script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>

<script>
const MAP_FILE = 'Polygons_Subci_FeaturesToJSO.geojson';          // subcircuitos (polígonos)
const PTS_FILE = 'Points_ExportF_FeaturesToJSO.json';              // infraestructura (puntos) con ZONA en PopupInfo

const map = L.map('map').setView([-1.83,-78.18], 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18, attribution: '&copy; OpenStreetMap'
}).addTo(map);

const coordEl = document.getElementById('coord');
const zonaEl  = document.getElementById('zona');
const provEl  = document.getElementById('prov');
const distEl  = document.getElementById('dist');
const circEl  = document.getElementById('circ');
const subcEl  = document.getElementById('subc');

let polyLayer;           // capa de subcircuitos
let pointsFC = null;     // FeatureCollection de puntos (para extraer "ZONA")

// --- utilidades ---

// extrae texto de un campo dentro del HTML de PopupInfo
function fromPopup(html, etiqueta){
  if(!html) return null;
  // Coincide <td>ETIQUETA</td><td>VALOR</td>, ignorando mayúsculas/minúsculas
  const re = new RegExp(`<td>\\s*${etiqueta}\\s*</td>\\s*<td>(.*?)</td>`, 'i');
  const m = html.match(re);
  if(m && m[1]!=null){
    // limpia entidades/espacios
    return m[1].replace(/<[^>]*>/g,'').replace(/&nbsp;/g,' ').trim();
  }
  return null;
}

// Obtiene zona como 2 dígitos a partir de Name: ej. "09D06" -> "09"
function zoneFromName(name){
  const m = (name||'').match(/^(\d{2})/);
  if(!m) return 'N/A';
  return m[1]; // ya trae 08, 09, etc.
}

// Convierte un GeoJSON de Leaflet a un objeto turf
function toTurfPolygon(lFeature){
  return {
    type:'Feature',
    geometry: lFeature.geometry,
    properties: lFeature.properties || {}
  };
}

// Busca un punto del JSON de infraestructura DENTRO del subcircuito y devuelve su “ZONA”
function findProvinceZoneForPolygon(polyFeat){
  if(!pointsFC) return null;
  const poly = toTurfPolygon(polyFeat);
  for(const pt of pointsFC.features){
    if(!pt.geometry) continue;
    try{
      if(turf.booleanPointInPolygon(pt, poly)){
        const z = fromPopup(pt.attributes?.PopupInfo || pt.properties?.PopupInfo, 'ZONA');
        if(z && z.length) return z;
      }
    }catch(e){}
  }
  return null;
}

// Parsea del PopupInfo del polígono los nombres limpios
function namesFromPolygon(polyProps){
  const html = polyProps?.PopupInfo || polyProps?.properties?.PopupInfo || polyProps?.popupInfo;
  const distrito   = fromPopup(html, 'NAM_DISTRI') || fromPopup(html, 'NOMBRE_DIS') || 'N/A';
  const circuito   = fromPopup(html, 'NAM_CIRCUI') || fromPopup(html, 'NOMBRE_CIR') || 'N/A';
  const subcircuito= fromPopup(html, 'NAM_SUBCIR') || fromPopup(html, 'NOMBRE_SUB') || 'N/A';
  return {distrito, circuito, subcircuito};
}

// --- carga de datos ---

// Carga puntos (formato del export de ArcGIS)
fetch(PTS_FILE).then(r=>r.json()).then(json=>{
  // Normalizamos a FeatureCollection de puntos con atributos en .attributes
  // (viene como ArcGIS JSON exportado)
  if(json.features && json.features.length){
    pointsFC = {
      type:'FeatureCollection',
      features: json.features.map(f => ({
        type:'Feature',
        geometry:{type:'Point', coordinates:[f.geometry.x, f.geometry.y]},
        attributes: f.attributes
      }))
    };
  }
});

// Carga polígonos
fetch(MAP_FILE).then(r=>r.json()).then(geojson=>{
  polyLayer = L.geoJSON(geojson, {
    style: {color:'#111', weight:1, fillOpacity:0},
    onEachFeature: (feature, layer) => {
      const code = feature.properties?.Name || '';
      layer.bindTooltip(code, {permanent:false});
    }
  }).addTo(map);

  map.fitBounds(polyLayer.getBounds());
});

// --- interacción ---

let clickMarker = null;
let highlight   = null;

map.on('click', (e)=>{
  const {lat, lng} = e.latlng;
  coordEl.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

  // Quitar resaltado/marker previos
  if(clickMarker){ map.removeLayer(clickMarker); clickMarker=null; }
  if(highlight){ map.removeLayer(highlight); highlight=null; }

  clickMarker = L.marker([lat,lng]).addTo(map);

  // Encontrar el subcircuito clickeado
  let foundFeature = null;
  polyLayer.eachLayer(l=>{
    if(!l.getBounds) return;
    if(!l.getBounds().contains(e.latlng)) return;
    // chequeo fino con Leaflet: pointInLayer
    const inside = leafletPip.pointInLayer([lng,lat], l, true).length>0;
    if(inside){ foundFeature = l.feature; }
  });

  if(!foundFeature){
    zonaEl.value = distEl.value = circEl.value = subcEl.value = '';
    provEl.value = 'N/A';
    return;
  }

  // Resaltar polígono
  highlight = L.geoJSON(foundFeature, {style:{color:'#2b7', weight:3, fillOpacity:.2}}).addTo(map);

  // ZONA (dos dígitos, 01–09)
  const z = zoneFromName(foundFeature.properties?.Name);
  zonaEl.value = z;

  // Nombres desde el polígono (sin códigos)
  const {distrito, circuito, subcircuito} = namesFromPolygon(foundFeature.properties || {});
  distEl.value = distrito || 'N/A';
  circEl.value = circuito || 'N/A';
  subcEl.value = subcircuito || 'N/A';

  // Subzona/Provincia desde puntos dentro del polígono (campo ZONA del PopupInfo del punto)
  const prov = findProvinceZoneForPolygon(foundFeature);
  provEl.value = prov || 'N/A';

  // Pop breve con el código visible
  const code = foundFeature.properties?.Name || '';
  clickMarker.bindPopup(code).openPopup();
});

/*!
 * leaflet-pip minimal (point in polygon for Leaflet vector layers)
 * Fuente: adaptación simple usando turf.booleanPointInPolygon
 */
const leafletPip = {
  pointInLayer: function(point, layer, first){ // point [lng,lat]
    const res = [];
    const test = (feat) => {
      try{
        if(turf.booleanPointInPolygon({type:'Feature',geometry:{type:'Point',coordinates:point}}, feat)){
          res.push(layer);
        }
      }catch(e){}
    };
    if(layer && layer.feature){
      test(layer.feature);
    }
    return first ? res.slice(0,1) : res;
  }
};
</script>
</body>
</html>
