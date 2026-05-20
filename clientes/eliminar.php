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
if ($id <= 0) {
    $_SESSION['flash_mensaje'] = 'ID de cliente no válido.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit();
}

require_once __DIR__ . '/../conexion.php';

$stmt = $conexion->prepare('DELETE FROM clientes WHERE id = ?');
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['flash_mensaje'] = 'Cliente eliminado correctamente';
        $_SESSION['flash_tipo'] = 'exito';
    } else {
        $_SESSION['flash_mensaje'] = 'No se encontró el cliente a eliminar';
        $_SESSION['flash_tipo'] = 'error';
    }
} else {
    $_SESSION['flash_mensaje'] = 'Error al eliminar cliente.';
    $_SESSION['flash_tipo'] = 'error';
}

$stmt->close();
$conexion->close();

header('Location: index.php');
exit();
