<?php
session_start();
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'generador') {
    header("Location: login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "sistema_casos");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$cedula_usuario = $_SESSION['cedula'];

$sql = "SELECT * FROM casos WHERE usuario_cedula = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $cedula_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Casos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h2 { text-align: center; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; background-color: white; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #005b8f; color: white; }
        .volver { margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #34495e; color: white; text-decoration: none; border-radius: 6px; }
        .volver:hover { background-color: #2c3e50; }
    </style>
</head>
<body>

    <h2>Mis Casos Generados</h2>

    <table>
        <tr>
            <th>Número de Caso</th>
            <th>Etiqueta</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['numero_caso']); ?></td>
                <td><?php echo htmlspecialchars($fila['label']); ?></td>
                <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                <td><a href="formulario_caso.php?numero_caso=<?php echo urlencode($fila['numero_caso']); ?>">Ver/Editar</a></td>
            </tr>
        <?php } ?>
    </table>

    <div style="text-align:center;">
        <a href="logout.php" class="volver">Cerrar sesión</a>
    </div>

</body>
</html>
