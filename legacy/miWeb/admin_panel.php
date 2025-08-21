<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel del Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 40px;
            text-align: center;
        }

        h1 {
            color: #2c3e50;
        }

        .botones a {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            text-decoration: none;
            font-size: 16px;
            background-color: #34495e;
            color: white;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .botones a:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>

    <h1>Panel de Administración</h1>

    <div class="botones">
        <a href="ver_usuarios.php">👥 Ver Usuarios</a>
        <a href="ver_casos.php">📂 Ver Casos</a> <!-- Este archivo lo puedes crear luego -->
        <a href="logout.php">🔓 Cerrar Sesión</a>
    </div>

</body>
</html>
