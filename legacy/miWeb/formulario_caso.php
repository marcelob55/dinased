<?php
session_start();
if (!isset($_SESSION['cedula'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['caso_id'])) {
    echo "Caso no definido.";
    exit();
}

$caso_id = intval($_GET['caso_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Caso</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        form { background: #fff; padding: 20px; border-radius: 8px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        textarea { resize: vertical; }
        .btn { margin-top: 20px; background: #007BFF; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<h2>Formulario Detallado del Caso #<?php echo $caso_id; ?></h2>

<form method="POST" action="guardar_formulario_caso.php">
    <input type="hidden" name="caso_id" value="<?php echo $caso_id; ?>">

    <label>ZONA</label>
    <select name="zona">
        <option value="4">4</option>
    </select>

    <label>SUBZONA</label>
    <select name="subzona">
        <option value="Manabí">Manabí</option>
    </select>

    <label>DISTRITO</label>
    <select name="distrito">
        <option value="Portoviejo">Portoviejo</option>
    </select>

    <label>CIRCUITO</label>
    <select name="circuito">
        <option value="Guabito">Guabito</option>
    </select>

    <label>SUBCIRCUITO</label>
    <select name="subcircuito">
        <option value="Guabito 1">Guabito 1</option>
    </select>

    <label>ESPACIO</label>
    <select name="espacio">
        <option value="Público">Público</option>
        <option value="Privado">Privado</option>
    </select>

    <label>ÁREA</label>
    <select name="area">
        <option value="Urbana">Urbana</option>
        <option value="Rural">Rural</option>
    </select>

    <label>LUGAR DEL HECHO</label>
    <input type="text" name="lugar_hecho" required>

    <label>FECHA/HORA DEL HECHO</label>
    <input type="datetime-local" name="fecha_hora_hecho" required>

    <label>COORDENADAS</label>
    <input type="text" name="coordenadas" placeholder="Ej: -1.080561, -80.437014">

    <label>¿Asiste Criminalística?</label>
    <select name="asiste_criminalistica">
        <option value="Sí">Sí</option>
        <option value="No">No</option>
    </select>

    <label>Cantidad de Vainas Percutidas (si aplica)</label>
    <input type="number" name="vainas" min="0">

    <label>Tipo de Arma</label>
    <input type="text" name="tipo_arma">

    <label>¿Existen Indicios?</label>
    <select name="indicios">
        <option value="Sí">Sí</option>
        <option value="No">No</option>
    </select>

    <label>Tipo de Delito</label>
    <input type="text" name="tipo_delito">

    <label>Motivación</label>
    <input type="text" name="motivacion">

    <label>Estado del Caso</label>
    <select name="estado_caso">
        <option value="Identificado sospechoso">Identificado sospechoso</option>
        <option value="Investigación">Investigación</option>
        <option value="Resuelto">Resuelto</option>
    </select>

    <label>Justificación de la Motivación</label>
    <textarea name="justificacion" rows="3"></textarea>

    <label>Circunstancias de los Hechos</label>
    <textarea name="circunstancias" rows="5"></textarea>

    <label>Entrevistas Realizadas</label>
    <textarea name="entrevistas" rows="5"></textarea>

    <label>Actividades Realizadas</label>
    <textarea name="actividades" rows="5"></textarea>

    <button class="btn" type="submit">Guardar Información</button>
</form>

</body>
</html>
