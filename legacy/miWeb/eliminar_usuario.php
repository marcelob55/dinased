<?php
include 'conexion.php';

// Verifica si se ha recibido el parámetro 'id'
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegura que sea un número entero

    // Elimina el registro de la base de datos
    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirige a la página de listado de usuarios
        header("Location: ver_usuarios.php");
        exit;
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }

} else {
    echo "ID no especificado.";
}
?>
