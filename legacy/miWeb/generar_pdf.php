<?php
require 'libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if (!isset($_GET['id'])) {
    die("ID de caso no proporcionado.");
}

$caso_id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "sistema_casos");
$conn->set_charset("utf8");

// Obtener datos del caso
$sql = "SELECT * FROM detalle_caso WHERE caso_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $caso_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
// Convertir las imágenes a base64
$logoPolicia = 'data:image/jpeg;base64,' . base64_encode(file_get_contents('img/logoPolicia.jpg'));
$logoMdi = 'data:image/jpeg;base64,' . base64_encode(file_get_contents('img/logoMdi.jpg'));

// Datos adicionales
$parteNumero = $caso_id;
$fechaHoraImpresion = date("d/m/Y H:i");

// Comenzar a construir el HTML
ob_start();
?>

<!-- ENCABEZADO CON LOGOS Y DATOS -->
<?php
$parteNumero = $caso_id;  // Usa el mismo ID del caso
$fechaHoraImpresion = date("d/m/Y H:i");
?>

<style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
    h2, h3 { text-align: center; margin-bottom: 5px; }
    .seccion { margin-top: 10px; }
    .seccion strong { display: block; margin-top: 8px; }
    .header-table { width: 100%; border-bottom: 1px solid #000; margin-bottom: 10px; }
</style>

<table width="100%" style="border-bottom: 1px solid #000; font-family: Arial, sans-serif;">
    <tr>
        <td width="15%" align="left">
            <img src="img/logoMdi.jpg" width="70">
        </td>
        <td width="70%" align="center" style="font-size: 14px;">
            <div style="font-weight: bold;">REPÚBLICA DEL ECUADOR MINISTERIO DEL INTERIOR</div>
            <div style="font-size: 13px;">NOTICIA DEL INCIDENTE</div>
        </td>
        <td width="15%" align="right">
            <img src="img/logoPolicia.jpg" width="70">
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size: 12px; padding-top: 5px;">
            <div style="float: left;"><strong>Parte No.</strong> <?= $parteNumero ?></div>
            <div style="float: right;"><strong>Fecha y hora de impresión:</strong> <?= $fechaHoraImpresion ?></div>
        </td>
    </tr>
</table>

<style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
    h2, h3 { text-align: center; margin-bottom: 5px; }
    .seccion { margin-top: 10px; }
    .seccion strong { display: block; margin-top: 8px; }
</style>

<style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
    h2, h3 { text-align: center; margin-bottom: 5px; }
    .seccion { margin-top: 10px; }
    .seccion strong { display: block; margin-top: 8px; }
</style>

<h2>DINASED UDCV MV SZ MANABÍ</h2>
<h3>VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO</h3>

<div class="seccion">
    <strong>CÓDIGO ÚNICO ECU 911:</strong> <?= htmlspecialchars($row['codigo_ecu'] ?? 'No registrado') ?>
    <strong>ZONA:</strong> <?= htmlspecialchars($row['zona'] ?? 'No registrada') ?>
    <strong>SUBZONA:</strong> <?= htmlspecialchars($row['subzona'] ?? 'No registrada') ?>
    <strong>DISTRITO:</strong> <?= htmlspecialchars($row['distrito'] ?? 'No registrado') ?>
    <strong>CIRCUITO:</strong> <?= htmlspecialchars($row['circuito'] ?? 'No registrado') ?>
    <strong>SUBCIRCUITO:</strong> <?= htmlspecialchars($row['subcircuito'] ?? 'No registrado') ?>
    <strong>ESPACIO / ÁREA:</strong> <?= htmlspecialchars($row['espacio'] ?? '') ?> / <?= htmlspecialchars($row['area'] ?? '') ?>
    <strong>LUGAR DEL HECHO:</strong> <?= htmlspecialchars($row['lugar_hecho'] ?? 'No registrado') ?>
    <strong>FECHA DEL HECHO:</strong> <?= isset($row['fecha_hecho']) ? date('d/m/Y', strtotime($row['fecha_hecho'])) : 'No registrada' ?>
    <strong>HORA DEL HECHO:</strong> <?= isset($row['hora_hecho']) ? date('H:i', strtotime($row['hora_hecho'])) : 'No registrada' ?>
    <strong>COORDENADAS:</strong> <?= htmlspecialchars($row['coordenadas'] ?? 'No registradas') ?>
</div>

<div class="seccion">
    <strong>ASISTE CRIMINALÍSTICA:</strong> <?= htmlspecialchars($row['criminalistica'] ?? 'No registrada') ?>
    <strong>TIPO DE ARMA:</strong> <?= htmlspecialchars($row['tipo_arma'] ?? 'No registrada') ?>
    <strong>INDICIOS:</strong> <?= htmlspecialchars($row['indicios'] ?? 'No registrados') ?>
    <strong>TIPO DE DELITO:</strong> <?= htmlspecialchars($row['tipo_delito'] ?? 'No registrado') ?>
    <strong>MOTIVACIÓN:</strong> <?= htmlspecialchars($row['motivacion'] ?? 'No registrada') ?>
    <strong>JUSTIFICACIÓN DE LA MOTIVACIÓN:</strong><br>
    <?= nl2br(htmlspecialchars($row['justificacion_motivacion'] ?? 'No registrada')) ?>
</div>

<div class="seccion">
    <strong>INTERFECTOS:</strong><br>
    <?= nl2br(htmlspecialchars($row['interfectos'] ?? 'No registrados')) ?>
</div>

<div class="seccion">
    <strong>HERIDOS:</strong><br>
    <?= nl2br(htmlspecialchars($row['heridos'] ?? 'No registrados')) ?>
</div>

<div class="seccion">
    <strong>CIRCUNSTANCIAS DE LOS HECHOS:</strong><br>
    <?= nl2br(htmlspecialchars($row['circunstancias'] ?? 'No registradas')) ?>
</div>

<div class="seccion">
    <strong>ENTREVISTAS REALIZADAS:</strong><br>
    <?= nl2br(htmlspecialchars($row['entrevistas'] ?? 'No registradas')) ?>
</div>

<div class="seccion">
    <strong>ACTIVIDADES REALIZADAS:</strong><br>
    <?= nl2br(htmlspecialchars($row['actividades'] ?? 'No registradas')) ?>
</div>

<div class="seccion">
    <strong>ESTADO DEL CASO:</strong> <?= htmlspecialchars($row['estado_caso'] ?? 'No registrado') ?>
    <br><strong>REPORTA:</strong> <?= htmlspecialchars($row['reporta'] ?? 'No registrado') ?>
</div>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("detalle_caso_$caso_id.pdf", ["Attachment" => false]);
exit;
