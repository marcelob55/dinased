<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'generador') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_caso = $_POST['numero_caso'];
    $label = $_POST['label'];
    $fecha = $_POST['fecha'];
    $cedula = $_SESSION['cedula']; // cedula del usuario que genera el caso

    $stmt = $conn->prepare("INSERT INTO casos (numero_caso, label, fecha, cedula) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $numero_caso, $label, $fecha, $cedula);

    if ($stmt->execute()) {
        echo "<p>Caso registrado correctamente.</p>";
        echo '<p><a href="generar_caso.php">Generar otro caso</a></p>';
        echo '<p><a href="logout.php">Cerrar sesión</a></p>';
    } else {
        echo "Error al registrar el caso: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: generar_caso.php");
    exit;
}
?>
