<?php
include 'conexion.php';

// Validación básica
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Escapar y obtener todos los valores del formulario
    $nombres = $conn->real_escape_string($_POST['nombres']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $nickname = $conn->real_escape_string($_POST['nickname']);
    $celular = $conn->real_escape_string($_POST['celular']);
    $cedula = $conn->real_escape_string($_POST['cedula']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $agencia = $conn->real_escape_string($_POST['agencia']);
    $equipo = $conn->real_escape_string($_POST['equipo']);
    $numero_caso = $conn->real_escape_string($_POST['numero_caso']);

    // Actualización del usuario
    $sql = "UPDATE usuarios SET 
                nombres='$nombres',
                apellidos='$apellidos',
                nickname='$nickname',
                celular='$celular',
                cedula='$cedula',
                correo='$correo',
                agencia='$agencia',
                equipo='$equipo',
                numero_caso='$numero_caso'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redireccionar a la vista principal
        header("Location: ver_usuarios.php");
        exit;
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

} else {
    echo "Datos incompletos o método incorrecto.";
}
?>
