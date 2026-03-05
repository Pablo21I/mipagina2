<?php
declare(strict_types=1);

$server = 'localhost';
$user = 'root';
$pass = '';
$bd = 'mipagina';

if ($bd === '') {
    die('Falta definir el nombre de la base de datos en conn.php');
}

$conexion = new mysqli($server, $user, $pass, $bd);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

$conexion->set_charset('utf8mb4');
