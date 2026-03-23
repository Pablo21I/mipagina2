<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login2.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nombre = trim($_POST['nombre'] ?? '');
$domicilio = trim($_POST['domicilio'] ?? '');
$giro = trim($_POST['giro'] ?? '');
$razon_social = trim($_POST['razon_social'] ?? '');

if ($id <= 0 || $nombre === '' || $domicilio === '' || $giro === '' || $razon_social === '') {
    $_SESSION['flash_mensaje'] = 'Datos inválidos para actualizar cliente.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: editar.php?id=' . $id);
    exit();
}

require_once __DIR__ . '/../lib/conn.php';

$stmt = $conexion->prepare('UPDATE clientes SET nombre = ?, domicilio = ?, giro = ?, razon_social = ? WHERE id = ?');
$stmt->bind_param('ssssi', $nombre, $domicilio, $giro, $razon_social, $id);

if ($stmt->execute()) {
    $_SESSION['flash_mensaje'] = 'Cliente actualizado correctamente';
    $_SESSION['flash_tipo'] = 'exito';
} else {
    $_SESSION['flash_mensaje'] = 'Error al actualizar cliente: ' . $conexion->error;
    $_SESSION['flash_tipo'] = 'error';
}

$stmt->close();
$conexion->close();

header('Location: index.php');
exit();
