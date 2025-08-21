<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Casos de Muertes Violentas Zona 4</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .opciones {
            margin-top: 30px;
        }

        .opciones a {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            text-decoration: none;
            font-size: 16px;
            background-color: #2980b9;
            color: white;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .opciones a:hover {
            background-color: #1a5276;
        }

        .opciones i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<!-- Encabezado -->
<div style="display:flex; align-items:center; justify-content:space-between; padding:10px 20px; background:#fff; border-bottom:2px solid #ccc;">
    
    <!-- Logos e identificación -->
    <div style="display:flex; align-items:center; gap:10px;">
        <img src="img/sello_policia.jpg" alt="Escudo Ecuador" style="height:60px;">
        <img src="img/logo_dinased.jpg" alt="Escudo DINASED" style="height:60px;">
        <span style="font-weight:bold; font-size:18px; white-space:nowrap;">
            DIRECCIÓN NACIONAL DE INVESTIGACIÓN DE MUERTES VIOLENTAS Y DESAPARECIDOS
        </span>
    </div>
    
    <!-- Botón de inicio -->
    <div>
        <a href="index.php" style="display:inline-flex; align-items:center; background:#4CAF50; color:#fff; padding:8px 15px; border-radius:20px; text-decoration:none; font-weight:bold;">
            <span style="margin-right:5px;">🏠</span> Inicio
        </a>
    </div>

</div>


    <h1>Sistema de Casos de Muertes Violentas Zona 4</h1>

    <div class="opciones">
        <a href="registro.php"><i class="fa-solid fa-user-plus"></i> Registro de Usuario</a>
        <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Ingreso como Usuario</a>
        <a href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Ingreso como Administrador</a>
    </div>

</body>
</html>
