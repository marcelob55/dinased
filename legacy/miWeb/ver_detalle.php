<?php
require_once("conexion.php");

$caso_id = $_GET['caso_id'] ?? $_GET['id'] ?? null;
if (!$caso_id) {
    echo "ID de caso no proporcionado.";
    exit;
}

$sql = "SELECT * FROM detalle_caso WHERE caso_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $caso_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No se encontró información del caso.";
    exit();
}

$row = $result->fetch_assoc();
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte de Novedad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 40px;
            color: #000;
        }
        h2, h3 {
            text-align: center;
            margin: 5px 0;
        }
        .bloque {
            margin-top: 25px;
        }
        .etiqueta {
            font-weight: bold;
            display: inline-block;
            width: 240px;
            vertical-align: top;
        }
        .contenido {
            display: inline-block;
        }
        .bloque p {
            margin: 3px 0;
        }
        .subtitulo {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
        }
        .texto-multilinea {
            white-space: pre-wrap;
            margin-top: 5px;
        }
        .botones {
            text-align: center;
            margin-top: 30px;
        }
        .botones a {
            text-decoration: none;
            margin: 0 10px;
            padding: 10px 18px;
            border-radius: 5px;
            color: white;
            background-color: #2c3e50;
        }
        .botones a:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

<h2>REPÚBLICA DEL ECUADOR - MINISTERIO DEL INTERIOR</h2>
<h3>UNIDADES NACIONALES ESPECIALIZADAS</h3>

<div class="bloque">
    <p><span class="etiqueta">Código ECU 911:</span> <?= htmlspecialchars($row['codigo_ecu']) ?></p>
    <p><span class="etiqueta">Zona:</span> <?= htmlspecialchars($row['zona']) ?></p>
    <p><span class="etiqueta">Subzona:</span> <?= htmlspecialchars($row['subzona']) ?></p>
    <p><span class="etiqueta">Distrito:</span> <?= htmlspecialchars($row['distrito']) ?></p>
    <p><span class="etiqueta">Circuito:</span> <?= htmlspecialchars($row['circuito']) ?></p>
    <p><span class="etiqueta">Subcircuito:</span> <?= htmlspecialchars($row['subcircuito']) ?></p>
    <p><span class="etiqueta">Espacio:</span> <?= htmlspecialchars($row['espacio']) ?> | <strong>Área:</strong> <?= htmlspecialchars($row['area']) ?></p>
    <p><span class="etiqueta">Lugar del hecho:</span> <?= htmlspecialchars($row['lugar_hecho']) ?></p>
    <p><span class="etiqueta">Fecha del hecho:</span> <?= date('d/m/Y', strtotime($row['fecha_hecho'])) ?></p>
    <p><span class="etiqueta">Hora del hecho:</span> <?= date('H:i', strtotime($row['hora_hecho'])) ?></p>
    <p><span class="etiqueta">Coordenadas:</span> <?= htmlspecialchars($row['coordenadas']) ?></p>
</div>

<div class="bloque">
    <div class="subtitulo">Circunstancias del hecho:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['circunstancias'])) ?></div>

    <div class="subtitulo">Entrevistas realizadas:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['entrevistas'])) ?></div>

    <div class="subtitulo">Actividades realizadas:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['actividades'])) ?></div>

    <div class="subtitulo">Interfectos:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['interfectos'] ?? 'No registrados')) ?></div>

    <div class="subtitulo">Heridos:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['heridos'] ?? 'No registrados')) ?></div>

    <div class="subtitulo">Tipo de arma:</div>
    <div class="texto-multilinea"><?= htmlspecialchars($row['tipo_arma']) ?></div>

    <div class="subtitulo">Indicios:</div>
    <div class="texto-multilinea"><?= htmlspecialchars($row['indicios']) ?></div>

    <div class="subtitulo">Tipo de delito:</div>
    <div class="texto-multilinea"><?= htmlspecialchars($row['tipo_delito']) ?></div>

    <div class="subtitulo">Motivación:</div>
    <div class="texto-multilinea"><?= htmlspecialchars($row['motivacion']) ?></div>

    <div class="subtitulo">Justificación de la motivación:</div>
    <div class="texto-multilinea"><?= nl2br(htmlspecialchars($row['justificacion_motivacion'])) ?></div>

    <div class="subtitulo">Estado del caso:</div>
    <div class="texto-multilinea"><?= htmlspecialchars($row['estado_caso']) ?></div>

    <p><span class="etiqueta">Reporta:</span> <?= htmlspecialchars($row['reporta']) ?></p>
</div>

<div class="botones">
    <a href="admin_panel.php">← Volver</a>
    <a href="generar_pdf.php?id=<?= $caso_id ?>" target="_blank">📄 Generar PDF</a>
</div>

</body>
</html>

<?php
$html = ob_get_clean();
echo $html;
?>
