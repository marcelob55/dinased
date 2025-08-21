<?php
// demo_mapa.php
// Archivo de demostración que muestra cómo se carga el GeoJSON de Subcircuitos
// y se rellenan automáticamente los campos al hacer clic en el mapa.
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Demo Mapa - Subcircuitos</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#fafbfc;color:#1f2937}
    header{padding:12px 16px;border-bottom:1px solid #e5e7eb;background:#fff}
    h1{font-size:18px;margin:0}
    main{padding:16px;max-width:1100px;margin:0 auto}
    #map{height:440px;border:1px solid #e5e7eb;border-radius:10px}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:12px}
    label{display:flex;flex-direction:column;font-size:13px;gap:6px}
    input[readonly]{background:#f8fafc}
    input,select{padding:9px 10px;border:1px solid #cfd7e3;border-radius:10px;font-size:14px}
    .legend{margin-top:8px;font-size:12px;color:#6b7280}
  </style>
</head>
<body>
  <header><h1>Demo: Selección de Subcircuitos en el Mapa</h1></header>
  <main>
    <h3>Haga clic en el mapa para ver los datos</h3>
    <div id="map"></div>
    <div class="legend">Usando: <b>Polygons_Subci_FeaturesToJSO.geojson</b></div>

    <div class="grid">
      <label>Coordenadas
        <input type="text" id="coordenadas" readonly>
      </label>
      <label>Zona
        <input type="text" id="zona" readonly>
      </label>
      <label>Subzona / Provincia
        <input type="text" id="subzona" readonly>
      </label>
      <label>Distrito
        <input type="text" id="distrito" readonly>
      </label>
      <label>Circuito
        <input type="text" id="circuito" readonly>
      </label>
      <label>Subcircuito
        <input type="text" id="subcircuito" readonly>
      </label>
    </div>
  </main>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>
  <script>
    const map = L.map('map').setView([-1.83, -78.18], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);

    const URL = 'Polygons_Subci_FeaturesToJSO.geojson';
    let capa = null, marcador=null, seleccionado=null;

    function marcar(latlng){
      if(!marcador) marcador = L.marker(latlng).addTo(map);
      else marcador.setLatLng(latlng);
      document.getElementById('coordenadas').value = latlng.lat.toFixed(6)+', '+latlng.lng.toFixed(6);
    }
    function resaltar(layer){
      if(seleccionado) seleccionado.setStyle({weight:1,color:'#ff7f00',fillOpacity:.15});
      seleccionado = layer;
      seleccionado.setStyle({weight:3,color:'#111827',fillOpacity:.25});
    }
    function prop(p,keys){for(const k of keys){if(k in p && p[k]) return p[k];}return 'N/A';}
    function escribir(p){
      document.getElementById('zona').value       = prop(p,['ZONA','REGION']);
      document.getElementById('subzona').value    = prop(p,['DPA_DESPRO','PROVINCIA','SUBZONA']);
      document.getElementById('distrito').value   = prop(p,['NOMBRE_DISTRITO','DISTRITO']);
      document.getElementById('circuito').value   = prop(p,['NOMBRE_CIRCUITO','CIRCUITO']);
      document.getElementById('subcircuito').value= prop(p,['NOMBRE_SUBCIRCUITO','SUBCIRCUITO','Name']);
    }

    fetch(URL).then(r=>r.json()).then(geo=>{
      capa = L.geoJSON(geo, {
        style:{color:'#ff7f00',weight:1,fillOpacity:.15},
        onEachFeature:(f,l)=>{
          l.on('click',()=>{
            const c=l.getBounds().getCenter();
            marcar(c); resaltar(l); escribir(f.properties||{});
            l.bindPopup('<b>'+prop(f.properties||{},['NOMBRE_SUBCIRCUITO','SUBCIRCUITO','Name'],'Subcircuito')+'</b>').openPopup();
          });
        }
      }).addTo(map);
      map.fitBounds(capa.getBounds());
    });

    map.on('click',e=>{
      marcar(e.latlng);
      if(!capa) return;
      const pt=turf.point([e.latlng.lng,e.latlng.lat]);
      let encontrado=null, props={};
      capa.eachLayer(l=>{
        if(encontrado) return;
        const f=l.feature;
        if(f && turf.booleanPointInPolygon(pt,f)){
          encontrado=l; props=f.properties||{};
        }
      });
      if(encontrado){resaltar(encontrado); escribir(props);}
      else{escribir({});}
    });
  </script>
</body>
</html>
