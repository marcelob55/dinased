<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema_casos";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$resultado = $conn->query("SELECT casos.*, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_usuario " ."FROM casos JOIN usuarios ON casos.cedula = usuarios.cedula");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Casos Registrados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f7;
            text-align: center;
            padding: 20px;
        }
        h2 {
            color: #2c3e50;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        a.btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #34495e;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        a.btn-volver:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <h2>Casos Registrados</h2>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Número de Caso</th>
            <th>Nombre Asociado</th>
            <th>Fecha</th>
            <th>Descripción</th>
<th>Generado por</th>
<th>Opciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['numero_caso']; ?></td>
            <td><?php echo $fila['nombre_asociado']; ?></td>
            <td><?php echo $fila['fecha']; ?></td>
            <td><?php echo $fila['descripcion']; ?></td>
            <td><?php echo $fila['nombre_usuario']; ?></td>
            <td>
                <a href='editar_caso.php?id=<?php echo $fila["id"]; ?>'>Editar</a> |
                <a href='eliminar_caso.php?id=<?php echo $fila["id"]; ?>' onclick="return confirm('¿Estás seguro de eliminar este caso?')">Eliminar</a> |
                <a href='asignar_caso.php?id=<?php echo $fila["id"]; ?>'>Asignar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No hay casos registrados o ocurrió un error.</p>
    <?php endif; ?>

    <a href="admin_panel.php" class="btn-volver">⬅ Volver al Panel de A_
