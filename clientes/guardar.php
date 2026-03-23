<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../templates/plantilla.php?view=clientes');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$domicilio = trim($_POST['domicilio'] ?? '');
$giro = trim($_POST['giro'] ?? '');
$razon_social = trim($_POST['razon_social'] ?? '');

if ($nombre === '' || $domicilio === '' || $giro === '' || $razon_social === '') {
    $_SESSION['flash_mensaje'] = 'Por favor completa todos los campos del cliente.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: nuevo.php');
    exit();
}

require_once __DIR__ . '/../lib/conn.php';

$sql = "INSERT INTO clientes (nombre, domicilio, giro, razon_social) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ssss', $nombre, $domicilio, $giro, $razon_social);
    if ($stmt->execute()) {
        $_SESSION['flash_mensaje'] = 'Cliente registrado correctamente.';
        $_SESSION['flash_tipo'] = 'exito';
    } else {
        $_SESSION['flash_mensaje'] = 'Error al guardar el cliente.';
        $_SESSION['flash_tipo'] = 'error';
    }
    $stmt->close();
} else {
    $_SESSION['flash_mensaje'] = 'No se pudo preparar la consulta para crear cliente.';
    $_SESSION['flash_tipo'] = 'error';
}

$conexion->close();
header('Location: ../templates/plantilla.php?view=clientes');
exit();
?>