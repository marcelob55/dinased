<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cedula = $_POST['cedula'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($cedula) || empty($contrasena)) {
        echo "Cédula o contraseña vacías<br><a href='login.php'>Volver</a>";
        exit();
    }

    $conexion = new mysqli("localhost", "root", "", "sistema_casos");
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Asignar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['cedula'] = $usuario['cedula'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redireccionar según rol
            switch ($usuario['rol']) {
                case 'admin':
                    header("Location: admin_panel.php");
                    break;
                case 'generador':
                    header("Location: generar_caso.php");
                    break;
                case 'editor':
                    header("Location: alimentar_caso.php");
                    break;
                default:
                    echo "Rol no reconocido<br><a href='login.php'>Volver</a>";
            }
            exit();
        }
    }

    echo "Usuario o contraseña incorrectos<br><a href='login.php'>Volver</a>";
} else {
    header("Location: login.php");
    exit();
}
?>
