<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            text-align: center;
            margin-top: 100px;
        }
        input {
            padding: 10px;
            margin: 10px;
            width: 250px;
        }
        button {
            padding: 10px 20px;
            background-color: #005b8f;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #003f63;
        }
    </style>
</head>
<body>

    <h2>Iniciar sesión</h2>
    <form action="validar.php" method="POST">
        <input type="text" name="cedula" placeholder="Cédula" required><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <button type="submit">Ingresar</button>
    </form>

    <p>DINASED PORTOVIEJO</p>

</body>
</html>

