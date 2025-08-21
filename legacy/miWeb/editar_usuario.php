<?php
include 'conexion.php';

// Verificamos si hay un ID por GET
if (!isset($_GET['id'])) {
    echo "ID de usuario no especificado.";
    exit;
}

$id = $_GET['id'];

// Consultamos los datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = $id";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit;
}

$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
