<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido</title>
	    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            margin-top: 100px;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Acceso correcto</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
