<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login2.php');
    exit();
}

require_once __DIR__ . '/../lib/conn.php';

$sql = "CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(180) NOT NULL,
    domicilio VARCHAR(255) NOT NULL,
    giro VARCHAR(180) NOT NULL,
    razon_social VARCHAR(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conexion->query($sql) === true) {
    $_SESSION['flash_mensaje'] = "Tabla 'clientes' creada (o ya existía) correctamente.";
    $_SESSION['flash_tipo'] = 'exito';
} else {
    $_SESSION['flash_mensaje'] = 'Error al crear tabla clientes: ' . $conexion->error;
    $_SESSION['flash_tipo'] = 'error';
}

$conexion->close();

header('Location: index.php');
exit();
