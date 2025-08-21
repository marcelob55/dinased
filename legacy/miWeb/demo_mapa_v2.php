<?php
// demo_mapa_v2.php
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Demo: Subcircuitos desde GeoJSON (parseando PopupInfo)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Leaflet -->
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
  />
  <script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
  ></script>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif}
    .wrap{max-width:1100px;margin:16px auto;padding:8px}
    #map{height:520px;border:1px solid #ddd;border-radius:8px}
    .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:12px}
    .grid .row{display:grid;grid-template-columns:180px 1fr;gap:8px;align-items:center}
    input{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px}
    .hint{margin-top:8px;color:#666;font-size:12px}
    .pill{display:inline-block;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:999px;padding:2px 10px;margin-top:6px}
  </style>
</head>
<body>
<div class="wrap">
  <h3>Demo: Selección de Subcircuitos en el Mapa</h3>
  <p>Haga clic en el mapa para obtener Zona, Distrito, Circuito y Subcircuito.</p>
  <div id="map"></div>
  <div class="hint">Usando: <span class="pill">Polygons_Subci_FeaturesToJSO.geojson</span></div>

  <div class="grid">
    <div class="row"><label>Coordenadas</label><input id="coor" readonly></div>
    <div class="row"><label>Zona</label><input id="zona" readonly></div>
    <div class="row"><label>Subzona / Provincia</label><input id="subzona" readonly></div>
    <div class="row"><label>Distrito</label><input id="distrito" readonly></div>
    <div class="row"><label>Circuito</label><input id="circuito" readonly></div>
    <div class="row"><label>Subcircuito</label><input id="subcircuito" readonly></div>
  </div>
</div>

<script>
  // 1) Mapa base
  const map = L.map('map').setView([-1.83, -78.18], 6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20, attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  // 2) Cargar GeoJSON
  let capaSubcircuitos = null;

  // Utilitario: parsea el HTML embebido en "PopupInfo" y devuelve un diccionario
  function parsePopupInfo(html) {
    const out = {};
    try {
      const doc = new DOMParser().parseFromString(html, 'text/html');
      // En el HTML vienen pares TD (etiqueta, valor)
      const tds = doc.querySelectorAll('td');
      for (let i = 0; i < tds.length - 1; i += 2) {
        const k = tds[i].textContent.trim();
        const v = tds[i + 1].textContent.trim();
        if (k) out[k] = v;
      }
    } catch (e) {
      console.warn('No se pudo parsear PopupInfo:', e);
    }
    return out;
  }

  // Estilo simple
  function estilo(feature) {
    return { color:'#111', weight:1, fillOpacity:0.15 };
  }

  // Cargar archivo (debe estar en la misma carpeta de este PHP)
  fetch('Polygons_Subci_FeaturesToJSO.geojson')
    .then(r => r.json())
    .then(geojson => {
      // Pre-parseamos atributos útiles para acceso rápido
      geojson.features.forEach(f => {
        const p = f.properties || {};
        const parsed = parsePopupInfo(p.PopupInfo || '');
        // Campos que SÍ existen en tu archivo:
        // - p.Name           → código circuito (ej. 17D05)
        // - parsed.COD_SUBCIR
        // - parsed.NAM_DISTRI
        // - parsed.NAM_CIRCUI
        // - parsed.NAM_SUBCIR
        f.properties.__parsed = {
          codigoCircuito: p.Name || '',
          codigoSubcircuito: parsed['COD_SUBCIR'] || '',
          distrito: parsed['NAM_DISTRI'] || '',
          circuitoNombre: parsed['NAM_CIRCUI'] || '',
          subcircuitoNombre: parsed['NAM_SUBCIR'] || ''
        };
      });

      capaSubcircuitos = L.geoJSON(geojson, { style: estilo }).addTo(map);
      map.fitBounds(capaSubcircuitos.getBounds());
    });

  // 3) Al hacer clic: localizar el polígono que contenga el punto
  const pin = L.marker([0,0], {opacity:0});
  pin.addTo(map);

  map.on('click', (e) => {
    document.getElementById('coor').value =
      e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);

    if (!capaSubcircuitos) return;

    let match = null;
    capaSubcircuitos.eachLayer((lyr) => {
      // chequeo rápido por bbox
      if (!lyr.getBounds().contains(e.latlng)) return;
      // chequeo preciso de punto en polígono
      if (leafletPointInPolygon(e.latlng, lyr)) {
        match = lyr;
      }
    });

    if (!match) {
      pin.setLatLng(e.latlng).setOpacity(0.7);
      // Limpiar
      setFields({
        zona: 'N/A', subzona: 'N/A',
        distrito: 'N/A', circuito: 'N/A', subcircuito: 'N/A'
      });
      return;
    }

    // Resaltar y mover pin
    pin.setLatLng(e.latlng).setOpacity(0.9);
    capaSubcircuitos.resetStyle();
    match.setStyle({ color:'#0ea5e9', weight:2, fillOpacity:0.25 });

    const props = match.feature.properties || {};
    const px = props.__parsed || {};

    // ZONA: tomamos los 2 primeros dígitos del código (ej. "17" o "09")
    const zona = (px.codigoCircuito || '').substring(0,2) || 'N/A';

    // SUBZONA: tu GeoJSON no trae este dato → “N/A”
    // (Si luego lo agregas como campo, lo mapeamos aquí.)
    const subzona = 'N/A';

    setFields({
      zona: zona,
      subzona: subzona,
      distrito: px.distrito || 'N/A',
      circuito: `${px.codigoCircuito || ''} - ${px.circuitoNombre || ''}`.trim().replace(/^ - /,''),
      subcircuito: `${px.codigoSubcircuito || ''} - ${px.subcircuitoNombre || ''}`.trim().replace(/^ - /,'')
    });

    // Popup simple con el código
    const label = px.codigoCircuito || 'Circuito';
    match.bindPopup(label).openPopup();
  });

  function setFields(vals){
    document.getElementById('zona').value        = vals.zona || '';
    document.getElementById('subzona').value     = vals.subzona || '';
    document.getElementById('distrito').value    = vals.distrito || '';
    document.getElementById('circuito').value    = vals.circuito || '';
    document.getElementById('subcircuito').value = vals.subcircuito || '';
  }

  // --- Punto-en-polígono con Leaflet (sin dependencias) ---
  // Soporta Polígonos y MultiPolígonos del GeoJSON
  function leafletPointInPolygon(latlng, layer) {
    const pt = [latlng.lng, latlng.lat]; // GeoJSON order [x,y] = [lng,lat]
    const gj = layer.feature.geometry;
    const polys = gj.type === 'Polygon' ? [gj.coordinates] :
                  gj.type === 'MultiPolygon' ? gj.coordinates : [];
    for (const poly of polys) {
      if (rayCasting(pt, poly[0])) {       // exterior
        // si hay huecos (anillos interiores), asegurar que NO está dentro de un hueco
        let insideHole = false;
        for (let i = 1; i < poly.length; i++) {
          if (rayCasting(pt, poly[i])) { insideHole = true; break; }
        }
        if (!insideHole) return true;
      }
    }
    return false;
  }
  // Algoritmo ray-casting sobre un anillo [ [lng,lat], ... ]
  function rayCasting(point, vs) {
    const x = point[0], y = point[1];
    let inside = false;
    for (let i = 0, j = vs.length - 1; i < vs.length; j = i++) {
      const xi = vs[i][0], yi = vs[i][1];
      const xj = vs[j][0], yj = vs[j][1];
      const intersect = ((yi > y) !== (yj > y)) &&
                        (x < (xj - xi) * (y - yi) / ((yj - yi) || 1e-12) + xi);
      if (intersect) inside = !inside;
    }
    return inside;
  }
</script>
</body>
</html>
