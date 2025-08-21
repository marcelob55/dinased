<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres = $_POST["nombres"] ?? '';
    $apellidos = $_POST["apellidos"] ?? '';
    $nickname = $_POST["nickname"] ?? '';
    $celular = $_POST["celular"] ?? '';
    $cedula = $_POST["cedula"] ?? '';
    $correo = $_POST["correo"] ?? '';
    $agencia = $_POST["agencia"] ?? '';
    $equipo = $_POST["equipo"] ?? '';
    $numero_caso = $_POST["numero_caso"] ?? '';
    $rol = $_POST["rol"] ?? '';

    // Validación básica
    if (empty($cedula) || empty($rol)) {
        echo "Cédula y rol son obligatorios.";
        exit();
    }

    // Generar la contraseña a partir de la cédula
    $contrasena_hashed = password_hash($cedula, PASSWORD_DEFAULT);

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "sistema_casos");
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios 
        (nombres, apellidos, nickname, celular, cedula, correo, agencia, equipo, numero_caso, contrasena, rol)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $nombres, $apellidos, $nickname, $celular, $cedula, $correo, $agencia, $equipo, $numero_caso, $contrasena_hashed, $rol);

    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.<br><a href='index.php'>Volver al inicio</a>";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso no permitido.";
}
?>



