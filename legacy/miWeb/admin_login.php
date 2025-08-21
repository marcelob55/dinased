<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Credenciales quemadas
    $admin_usuario = "admin";
    $admin_clave = "locemarB5.";

// ...
	if ($usuario === $admin_usuario && $clave === $admin_clave) {
		$_SESSION["admin"] = true;
		header("Location: admin_panel.php");
		exit();
	}
	else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ingreso como Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Ingreso como Administrador</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required><br><br>
        <label>Clave:</label>
        <input type="password" name="clave" required><br><br>
        <input type="submit" value="Ingresar">
    </form>
</body>
</html>
