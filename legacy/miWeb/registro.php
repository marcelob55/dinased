<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }
        form {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #005b8f;
            color: white;
            border: none;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
        }
        .volver {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Registro de Usuario</h2>
    <form action="guardar_registro.php" method="POST">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required>

        <label for="nickname">Nickname:</label>
        <input type="text" name="nickname" required>

        <label for="celular">Celular:</label>
        <input type="text" name="celular" required>

        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>

        <label for="agencia">Agencia:</label>
        <input type="text" name="agencia">

        <label for="equipo">Equipo:</label>
        <input type="text" name="equipo">

        <label for="numero_caso">Número de Caso:</label>
        <input type="text" name="numero_caso">

        <label for="rol">Rol del Usuario:</label>
        <select name="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="admin">Administrador</option>
            <option value="generador">Generador</option>
            <option value="editor">Editor</option>
        </select>

        <button type="submit">Registrar</button>
    </form>

    <div class="volver">
        <a href="index.php">← Volver al Inicio</a>
    </div>

</body>
</html>
