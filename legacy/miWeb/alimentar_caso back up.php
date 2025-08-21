
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
    $fecha_hora = $_POST['fecha_hora'];
    $coordenadas = $_POST['coordenadas'];
    $circunstancias = $conn->real_escape_string($_POST['circunstancias']);
    $entrevistas = implode(" | ", $_POST['entrevistas']);
    $actividades = implode(" | ", $_POST['actividades']);
    $reporta = $conn->real_escape_string($_POST['reporta']);

    $sql = "INSERT INTO detalle_caso (caso_id, verificacion, codigo_ecu, zona, subzona, distrito, circuito, subcircuito, espacio, area, lugar_hecho,
        fecha_hora, coordenadas, circunstancias, entrevistas, actividades, reporta)
        VALUES ('$id', '$verificacion', '$codigo_ecu', '$zona', '$subzona', '$distrito', '$circuito', '$subcircuito', '$espacio', '$area', '$lugar_hecho',
        '$fecha_hora', '$coordenadas', '$circunstancias', '$entrevistas', '$actividades', '$reporta')";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Información almacenada correctamente. <a href='ver_casos.php'>Volver</a>";
        exit();
    } else {
        echo "Error: " . $conn->error;
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
        label { display: block; margin-top: 8px; }
    </style>
    <script>
        let marker;

        function agregarCampo(nombre) {
            const contenedor = document.getElementById(nombre + "_contenedor");
            const textarea = document.createElement("textarea");
            textarea.name = nombre + "[]";
            textarea.rows = 3;
            textarea.cols = 100;
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




<h2>Formulario Completo para Alimentar Caso ID: <?php echo $id; ?></h2>
<form method="POST">
    <h3>1. Verificación del Evento</h3>
    <select name="verificacion">
        <option>DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO</option>
    </select>
    Código ECU 911: <input type="text" name="codigo_ecu" value="36953"><br>

    <h3>2. Ubicación Geográfica</h3>
    ZONA: <select name="zona"><option>4</option></select>
    SUBZONA: <select name="subzona"><option>Manabí</option></select>
    DISTRITO: <select name="distrito"><option>Portoviejo</option></select>
    CIRCUITO: <select name="circuito"><option>Guabito</option></select>
    SUBCIRCUITO: <select name="subcircuito"><option>Guabito 1</option></select>
    ESPACIO: <select name="espacio"><option>Público</option></select>
    ÁREA: <select name="area"><option>Urbana</option></select><br>
    LUGAR DEL HECHO: <input type="text" name="lugar_hecho" value="camino viejo"><br>
    FECHA/HORA DEL HECHO: <input type="datetime-local" name="fecha_hora"><br>

    <h4>Seleccione en el mapa:</h4>
    <div id="map" style="height: 300px;"></div>
    Coordenadas: <input type="text" name="coordenadas" id="coordenadas" readonly><br><br>

    <h3>3. Circunstancias</h3>
    <textarea name="circunstancias" rows="5" cols="100">19-07-2025. M.V DOBLE GUABITO PORTOVIEJO</textarea><br>

    <h3>4. Entrevistas Realizadas</h3>
    <div id="entrevistas_contenedor">
        <textarea name="entrevistas[]" rows="3" cols="100">Se entrevista con moradores del sector...</textarea><br>
    </div>
    <button type="button" onclick="agregarCampo('entrevistas')">+ Añadir otra entrevista</button><br>

    <h3>5. Actividades Realizadas</h3>
    <div id="actividades_contenedor">
        <textarea name="actividades[]" rows="3" cols="100">Entrevista con moradores...</textarea><br>
    </div>
    <button type="button" onclick="agregarCampo('actividades')">+ Añadir otra actividad</button><br>

    <h3>6. Reporta</h3>
    <input type="text" name="reporta" value="DINASED SZ MANABÍ-PORTOVIEJO" size="60"><br><br>

    <input type="submit" value="Guardar Información">
</form>


<style>
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
        font-family: Arial, sans-serif;
    }
    .logout-btn:hover {
        background-color: #e74c3c;
    }
</style>

<a href="logout.php" class="logout-btn">⎋ Cerrar sesión</a>
</body>
</html>
