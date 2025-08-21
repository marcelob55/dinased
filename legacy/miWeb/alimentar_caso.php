<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'editor') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema_casos");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    $res = $conn->query("SELECT id, numero_caso FROM casos");
    echo "<h3>Seleccione un caso para alimentar:</h3><ul>";
    while ($row = $res->fetch_assoc()) {
        echo "<li><a href='alimentar_caso.php?id={$row['id']}'>Caso {$row['numero_caso']} (ID: {$row['id']})</a></li>";
    }
    echo "</ul>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verificacion = $_POST['verificacion'];
    $codigo_ecu = $_POST['codigo_ecu'];
    $zona = $_POST['zona'];
    $subzona = $_POST['subzona'];
    $distrito = $_POST['distrito'];
    $circuito = $_POST['circuito'];
    $subcircuito = $_POST['subcircuito'];
    $espacio = $_POST['espacio'];
    $area = $_POST['area'];
    $lugar_hecho = $_POST['lugar_hecho'];
    $fecha_hecho = $_POST['fecha_hecho'];
    $hora_hecho = $_POST['hora_hecho'];
    $coordenadas = $_POST['coordenadas'];
    $circunstancias = $conn->real_escape_string($_POST['circunstancias']);
    $entrevistas = implode(" | ", $_POST['entrevistas']);
    $actividades = implode(" | ", $_POST['actividades']);
    $reporta = $conn->real_escape_string($_POST['reporta']);

    $sql = "INSERT INTO detalle_caso (caso_id, verificacion, codigo_ecu, zona, subzona, distrito, circuito, subcircuito, espacio, area, lugar_hecho,
        fecha_hecho, hora_hecho, coordenadas, circunstancias, entrevistas, actividades, reporta)
        VALUES ('$id', '$verificacion', '$codigo_ecu', '$zona', '$subzona', '$distrito', '$circuito', '$subcircuito', '$espacio', '$area', '$lugar_hecho',
        '$fecha_hecho', '$hora_hecho', '$coordenadas', '$circunstancias', '$entrevistas', '$actividades', '$reporta')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='mensaje-exito'>✅ Información almacenada correctamente. <a href='ver_casos.php'>Volver</a></div>";
        exit();
    } else {
        echo "<div class='mensaje-error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alimentar Caso</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }

        h2, h3, h4 {
            color: #2c3e50;
        }

        form {
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            max-width: 900px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], input[type="time"], select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            margin-top: 10px;
            padding: 8px 14px;
            border: none;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3498db;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #c0392b;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #e74c3c;
        }

        .mensaje-exito, .mensaje-error {
            padding: 10px;
            margin: 20px auto;
            text-align: center;
            max-width: 800px;
            font-weight: bold;
        }

        .mensaje-exito {
            color: green;
            background-color: #ecf9ec;
            border: 1px solid green;
        }

        .mensaje-error {
            color: red;
            background-color: #fdeaea;
            border: 1px solid red;
        }
    </style>
    <script>
        let marker;
        function agregarCampo(nombre) {
            const contenedor = document.getElementById(nombre + "_contenedor");
            const textarea = document.createElement("textarea");
            textarea.name = nombre + "[]";
            textarea.rows = 3;
            contenedor.appendChild(textarea);
            contenedor.appendChild(document.createElement("br"));
        }

        function actualizarCoordenadas(e) {
            const coordInput = document.getElementById("coordenadas");
            const coords = e.latlng.lat.toFixed(6) + "," + e.latlng.lng.toFixed(6);
            coordInput.value = coords;
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        }

        let map;
        window.onload = function() {
            map = L.map('map').setView([-1.080561, -80.437014], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            map.on('click', actualizarCoordenadas);
        };
    </script>
</head>
<body>

<a href="logout.php" class="logout-btn">⎋ Cerrar sesión</a>

<h2>Formulario para Alimentar Caso ID: <?php echo $id; ?></h2>
<form method="POST">
    <h3>1. Verificación del Evento</h3>
    <select name="verificacion">
        <option>DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO</option>
    </select>
    <label>Código ECU 911:</label>
    <input type="text" name="codigo_ecu" value="36953">

    <h3>2. Ubicación Geográfica</h3>
    <label>ZONA:</label>
<select name="zona">
  <option value="" disabled selected>Seleccione zona</option>
  <option value="1">Zona 1</option>
  <option value="2">Zona 2</option>
  <option value="3">Zona 3</option>
  <option value="4">Zona 4</option>
  <option value="5">Zona 5</option>
  <option value="6">Zona 6</option>
  <option value="7">Zona 7</option>
  <option value="8">Zona 8</option>
  <option value="8">Zona 9</option>
  
</select>

<label>SUBZONA:</label><select name="subzona"><option>Manabí</option></select>
    <label>DISTRITO:</label><select name="distrito"><option>Portoviejo</option></select>
    <label>CIRCUITO:</label><select name="circuito"><option>Guabito</option></select>
    <label>SUBCIRCUITO:</label><select name="subcircuito"><option>Guabito 1</option></select>
    <label>ESPACIO:</label><select name="espacio"><option>Público</option></select>
    <label>ÁREA:</label><select name="area"><option>Urbana</option></select>
    <label>LUGAR DEL HECHO:</label>
    <input type="text" name="lugar_hecho" value="camino viejo">

    <label>Fecha del hecho:</label>
    <input type="date" name="fecha_hecho" required>
    <label>Hora del hecho:</label>
    <input type="time" name="hora_hecho" required>

    <h4>Seleccione en el mapa:</h4>
    <div id="map" style="height: 300px;"></div>
    <label>Coordenadas:</label>
    <input type="text" name="coordenadas" id="coordenadas" readonly>

    <h3>3. Circunstancias</h3>
    <textarea name="circunstancias" rows="5">19-07-2025. M.V DOBLE GUABITO PORTOVIEJO</textarea>

    <h3>4. Entrevistas Realizadas</h3>
    <div id="entrevistas_contenedor">
        <textarea name="entrevistas[]" rows="3">Se entrevista con moradores del sector...</textarea>
    </div>
    <button type="button" onclick="agregarCampo('entrevistas')">+ Añadir otra entrevista</button>

    <h3>5. Actividades Realizadas</h3>
    <div id="actividades_contenedor">
        <textarea name="actividades[]" rows="3">Entrevista con moradores...</textarea>
    </div>
    <button type="button" onclick="agregarCampo('actividades')">+ Añadir otra actividad</button>

    <h3>6. Reporta</h3>
    <input type="text" name="reporta" value="DINASED SZ MANABÍ-PORTOVIEJO">

    <br><br><input type="submit" value="Guardar Información">
</form>

</body>
</html>
