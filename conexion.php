<?php
// conexion.php
declare(strict_types=1);

// Desactivar el reporte de excepciones de mysqli para manejar los errores manualmente
// y evitar la pantalla de error 500 en InfinityFree si hay algún problema temporal.
mysqli_report(MYSQLI_REPORT_OFF);

// Detectar automáticamente si estamos en entorno local (XAMPP) o en producción (InfinityFree)
$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '[::1]']) 
               || preg_match('/\.local$/', $_SERVER['HTTP_HOST'] ?? '');

if ($isLocalhost) {
    // Configuración para XAMPP local
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $bd = 'mipagina2';
} else {
    // Configuración automática para tu hosting de InfinityFree
    $server = 'sql302.infinityfree.com';
    $user = 'if0_40970301';
    $pass = 'Wk6JprWEfcK27DN';
    $bd = 'if0_40970301_mipagina2';
}

if ($bd === '') {
    die('Falta definir el nombre de la base de datos en conexion.php');
}

$conexion = new mysqli($server, $user, $pass, $bd);

if ($conexion->connect_error) {
    die('<h3>Error de conexión a la base de datos</h3>' . 
        '<p>Detalle: ' . htmlspecialchars($conexion->connect_error) . '</p>' .
        '<p>Asegúrate de haber creado la base de datos "if0_40970301_mipagina2" e importado las tablas en el phpMyAdmin de InfinityFree.</p>');
}

$conexion->set_charset('utf8mb4');
?>
