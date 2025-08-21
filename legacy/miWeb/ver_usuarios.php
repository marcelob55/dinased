<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "sistema_casos");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
			text-align: center;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: fff;
        }

        th, td {
            padding: 12px;
			border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #005b8f;
            color: white;
        }

        .acciones a {
            padding: 6px 12px;
            margin: 2px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .editar {
            background-color: #28a745;
            color: white;
        }

        .eliminar {
            background-color: #e74c3c;
            color: white;
        }

        .volver {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #34495e;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .volver:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>

    <h2>Usuarios Registrados</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Nickname</th>
            <th>Celular</th>
            <th>Cédula</th>
            <th>Correo</th>
            <th>Agencia</th>
            <th>Equipo</th>
            <th>Rol</th>
            <th>Número de Caso</th>
            <th>Acciones</th>
        </tr>

        <?php
        $resultado = $conexion->query("SELECT * FROM usuarios");

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['id'] . "</td>";
            echo "<td>" . $fila['nombres'] . "</td>";
            echo "<td>" . $fila['apellidos'] . "</td>";
            echo "<td>" . $fila['nickname'] . "</td>";
            echo "<td>" . $fila['celular'] . "</td>";
            echo "<td>" . $fila['cedula'] . "</td>";
            echo "<td>" . $fila['correo'] . "</td>";
            echo "<td>" . $fila['agencia'] . "</td>";
            echo "<td>" . $fila['equipo'] . "</td>";
            echo "<td>" . $fila['rol'] . "</td>";
            echo "<td>" . $fila['numero_caso'] . "</td>";
            echo "<td class='acciones'>
                    <a href='editar_usuario.php?id=" . $fila['id'] . "' class='editar'>Editar</a>
                    <a href='eliminar_usuario.php?id=" . $fila['id'] . "' class='eliminar' onclick=\"return confirm('¿Estás seguro de eliminar este usuario?');\">Eliminar</a>
                  </td>";
            echo "</tr>";
        }

        $conexion->close();
        ?>
    </table>

    <div style="text-align:center;">
        <a href="admin_panel.php" class="volver">← Volver al Panel de Administrador</a>
    </div>

</body>
</html>
