<?php
session_start();
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'generador') {
    header("Location: login.php");
    exit();
}

// Conexión
$conexion = new mysqli("localhost", "root", "", "sistema_casos");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener fecha actual
$fecha = date("Y-m-d");
$anio = date("Y");
$mes = date("m");
$dia = date("d");
$zona = "Z4"; // zona fija para este ejemplo

// Contar casos existentes hoy
$stmt = $conexion->prepare("SELECT COUNT(*) as total FROM casos WHERE fecha = ?");
$stmt->bind_param("s", $fecha);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();
$total = $resultado['total'] + 1;

// Generar código: Z4 + año + mes + día + contador
$numero_caso = $zona . $anio . $mes . $dia . str_pad($total, 2, "0", STR_PAD_LEFT);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Caso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f2f2f2;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
        }

        form {
            display: inline-block;
            margin-top: 30px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        input[type="text"] {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1a5276;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2c3e50;
        }
    </style>
</head>
<body>

    <h2>Generar Nuevo Caso</h2>

    <form action="guardar_caso.php" method="POST">
        <input type="hidden" name="numero_caso" value="<?php echo $numero_caso; ?>">
        <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
        <input type="hidden" name="cedula" value="<?php echo $_SESSION['cedula']; ?>">

        <label for="label">Descripción del Caso:</label><br>
        <input type="text" name="label" placeholder="19-07-2025-MV-simple/doble-portoviejo" required><br>

        <input type="submit" value="Crear Caso">
    </form>

    <a href="logout.php" class="volver">← Cerrar sesión</a>

</body>
</html>
