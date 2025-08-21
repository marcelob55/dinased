<?php // demo_mapa_v5_1.php ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Mapa: Zona (01–09), Provincia, Distrito, Circuito y Subcircuito</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
  <p>Haga clic en el mapa para obtener Zona (01–09), Provincia, Distrito, Circuito y Subcircuito.</p>
  <div id="map"></div>
  <div class="hint">
    Usando: <code>Polygons_Subci_FeaturesToJSO.geojson</code> + <code>Points_ExportF_FeaturesToJSO.json</code> (opcional: <code>provincias.geojson</code>, <code>zonas_policiales.geojson</code>)
  </div>

  <div class="row">
    <div class="field"><label>Coordenadas</label><input id="coord" readonly></div>
    <div class="field"><label>Zona (01–09)</label><input id="zona" readonly></div>
  </div>
  <div class="row">
    <div class="field"><label>Subzona / Provincia</label><input id="prov" readonly></div>
    <div class="field"><label>Distrito</label><input id="dist" readonly></div>
  </div>
  <div class="row">
    <div class="field"><label>Circuito</label><input id="circ" readonly></div>
    <div class="field"><label>Subcircuito</label><input id="subc" readonly></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>

<script>
const SUBC_FILE = 'Polygons_Subci_FeaturesToJSO.geojson';
const PTS_FILE  = 'Points_ExportF_FeaturesToJSO.json';
const PROV_FILE = 'provincias.geojson';          // opcional
const ZONAS_FILE= 'zonas_policiales.geojson';    // opcional

const map = L.map('map').setView([-1.83,-78.18], 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; OpenStreetMap'}).addTo(map);

const coordEl=document.getElementById('coord');
const zonaEl =document.getElementById('zona');
const provEl =document.getElementById('prov');
const distEl =document.getElementById('dist');
const circEl =document.getElementById('circ');
const subcEl =document.getElementById('subc');

let subcLayer, pointsFC=null, provLayer=null, zonasLayer=null;

function safeJSON(path){
  return fetch(path,{cache:'no-store'}).then(r=>{
    if(!r.ok) throw new Error('No se encontró: '+path);
    return r.json();
  });
}

// ---------- utilidades ----------
function getFirstProp(obj, keys){
  if(!obj) return null;
  for(const k of keys){
    if(obj[k] !== undefined && obj[k] !== null && String(obj[k]).trim() !== '') return obj[k];
    // tolerancia por mayúsculas/minúsculas
    const k2 = Object.keys(obj).find(x=>x.toLowerCase()===k.toLowerCase());
    if(k2 && obj[k2] !== undefined && obj[k2] !== null && String(obj[k2]).trim()!=='') return obj[k2];
  }
  return null;
}
function parseFromPopup(html, etiqueta){
  if(!html) return null;
  const re = new RegExp(`<td>\\s*${etiqueta}\\s*</td>\\s*<td>(.*?)</td>`,'i');
  const m = html.match(re);
  return m && m[1] ? m[1].replace(/<[^>]*>/g,'').replace(/&nbsp;/g,' ').trim() : null;
}
function namesFromPolygon(props){
  const html = props?.PopupInfo || props?.popupinfo || '';
  const distrito    = parseFromPopup(html,'NAM_DISTRI') || parseFromPopup(html,'NOMBRE_DIS') || 'N/A';
  const circuito    = parseFromPopup(html,'NAM_CIRCUI') || parseFromPopup(html,'NOMBRE_CIR') || 'N/A';
  const subcircuito = parseFromPopup(html,'NAM_SUBCIR') || parseFromPopup(html,'NOMBRE_SUB') || 'N/A';
  return { distrito, circuito, subcircuito };
}
const toFeat = (f)=>({type:'Feature',geometry:f.geometry,properties:f.properties||{}});
const inPoly = (pt, poly)=>{ try{return turf.booleanPointInPolygon(pt,poly);}catch{ return false;} };
const zero2 = n => (parseInt(n,10)<10?'0':'')+parseInt(n,10);
function normalizeZonaString(zStr){
  if(!zStr) return null;
  const m = String(zStr).match(/(\d{1,2})/);
  if(!m) return null;
  const z = parseInt(m[1],10);
  if(!(z>=1 && z<=9)) return null;
  return zero2(z);
}

// ---------- carga ----------
safeJSON(SUBC_FILE).then(gj=>{
  subcLayer = L.geoJSON(gj,{ style:{color:'#111',weight:1,fillOpacity:0} }).addTo(map);
  try{ map.fitBounds(subcLayer.getBounds()); }catch(e){}
});
safeJSON(PTS_FILE).then(json=>{
  if(json?.features?.length){
    pointsFC = {
      type:'FeatureCollection',
      features: json.features.map(f => ({
        type:'Feature',
        geometry:{type:'Point',coordinates:[f.geometry.x, f.geometry.y]},
        properties: f.attributes || {},
        attributes: f.attributes || {}
      }))
    };
  }
}).catch(err=>console.warn(err.message));
safeJSON(PROV_FILE).then(gj=>{
  provLayer = L.geoJSON(gj);
}).catch(()=>console.warn('provincias.geojson no encontrado (Provincia quedará N/A).'));
safeJSON(ZONAS_FILE).then(gj=>{
  zonasLayer = L.geoJSON(gj);
}).catch(()=>{});

// ---------- click ----------
let pin=null, hilite=null;

map.on('click', (e)=>{
  const {lat,lng}=e.latlng;
  coordEl.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
  if(pin){ map.removeLayer(pin); pin=null; }
  if(hilite){ map.removeLayer(hilite); hilite=null; }
  pin = L.marker([lat,lng]).addTo(map);

  const pt = turf.point([lng,lat]);
  let picked=null;
  subcLayer && subcLayer.eachLayer(l=>{
    if(picked) return;
    const f = l.feature;
    if(!f?.geometry) return;
    if(inPoly(pt, toFeat(f))) picked = f;
  });

  if(!picked){
    zonaEl.value = provEl.value = distEl.value = circEl.value = subcEl.value = 'N/A';
    return;
  }

  hilite = L.geoJSON(picked,{style:{color:'#2b7',weight:3,fillOpacity:.2}}).addTo(map);
  pin.bindPopup(picked.properties?.Name || '').openPopup();

  const {distrito, circuito, subcircuito} = namesFromPolygon(picked.properties||{});
  distEl.value = distrito;
  circEl.value = circuito;
  subcEl.value = subcircuito;

  // ---- ZONA (01–09) ----
  let zona = null;
  if(pointsFC){
    for(const f of pointsFC.features){
      if(inPoly(f, picked)){
        const z1 = parseFromPopup(f.properties?.PopupInfo || f.attributes?.PopupInfo,'ZONA')
                || getFirstProp(f.properties||f.attributes, ['ZONA','zona','ZONAS','ZONA_']);
        zona = normalizeZonaString(z1);
        if(zona) break;
      }
    }
  }
  if(!zona && zonasLayer){
    zonasLayer.eachLayer(l=>{
      if(zona) return;
      const g = l.feature;
      if(!g?.geometry) return;
      if(inPoly(pt, g)){
        const z2 = getFirstProp(g.properties, ['ZONA','zona','Name','name','COD_ZONA','CODZONA']);
        zona = normalizeZonaString(z2);
      }
    });
  }
  zonaEl.value = zona || 'N/A';

  // ---- PROVINCIA ----
  let provincia = 'N/A';
  if(provLayer){
    provLayer.eachLayer(l=>{
      if(provincia !== 'N/A') return;
      const g = l.feature;
      if(!g?.geometry) return;
      if(inPoly(pt, g)){
        // acepta muchos nombres de campo típicos
        provincia = getFirstProp(g.properties, [
          'NAME','NAME_1','Provincia','PROVINCIA','provincia','DPA_DESPRO','DPA_DESPROV',
          'NOM_PROV','PROV_NAME','prov_name','prov','NAMEUNIT','ADM1_ES','ADM1_PCODE'
        ]) || 'N/A';
      }
    });
    if(provincia==='N/A') console.warn('No se encontró un campo de nombre de provincia en provincias.geojson. Revisa los nombres de atributos.');
  }
  provEl.value = provincia;
});
</script>
</body>
</html>

