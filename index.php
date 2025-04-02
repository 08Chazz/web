<?php
session_start(); // Iniciar sesión para almacenar la clave generada

// Función para generar la clave cifrada
function generarClave($nombre, $edad, $puesto, $telefono) {
    $nombreArray = explode(" ", $nombre);
    $inicialesNombre = strtoupper(substr($nombreArray[0], 0, 1) . substr($nombreArray[1], 0, 1) . substr($nombreArray[2], 0, 1));

    $numerosIniciales = [];
    foreach (str_split($inicialesNombre) as $letra) {
        $numerosIniciales[] = ord($letra) - 64;
    }
    $numeroIniciales = array_sum($numerosIniciales);

    $simboloEdad = $edad > 20 ? '!' : '@';

    $anioNacimiento = date("Y") - $edad;
    $ultimoDigitoAnioNacimiento = substr($anioNacimiento, -1);
    $edadMultiplicada = $edad * $ultimoDigitoAnioNacimiento;

    $numeroGerente = ord('G') - 64;

    $primerosDigitosTelefono = substr($telefono, 0, 2);
    $ultimosDigitosTelefono = array_sum(str_split(substr($telefono, -2)));
    $letraUltimosDigitos = strtolower(chr(64 + $ultimosDigitosTelefono));

    $primerosDigitosSuma = array_sum(str_split(substr($telefono, 0, 2)));

    $clave = $numeroIniciales . $simboloEdad . $edadMultiplicada . 'LO' . $numeroGerente . $primerosDigitosTelefono . $letraUltimosDigitos . $primerosDigitosSuma;
    
    return $clave;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $puesto = $_POST['puesto'];
    $telefono = $_POST['telefono'];

    $_SESSION['claveGenerada'] = generarClave($nombre, $edad, $puesto, $telefono);

    header("Location: ".$_SERVER['PHP_SELF']); // Recarga la página sin datos del formulario
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Clave Cifrada</title>
    <link rel="stylesheet" href="sytle.css">
</head>
<body>
<div class="container">
    <h1 class="titulo">Generador de <br> Clave Cifrada</h1>

    <form method="POST">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" name="nombre" required><br>

        <label for="edad">Edad:</label>
        <input type="number" name="edad" required><br>

        <label for="puesto">Puesto:</label>
        <input type="text" name="puesto" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" required><br>

        <button type="submit">Generar Clave</button>
    </form>

    <?php
    if (isset($_SESSION['claveGenerada'])) {
        echo "<h2>Clave Generada: </h2>";
        echo "<p>".$_SESSION['claveGenerada']."</p>";
        unset($_SESSION['claveGenerada']); // Borra la clave de la sesión después de mostrarla
    }
    ?>
</div>
</body>
</html>
