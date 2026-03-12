<?php
session_start();

//Valida que exista la variable de sesion del usuario, si no existe redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}

//Validamos que la solicitud sea metodo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

//Tomamos el id por metodo POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo "<p style='color:red;'>ID del usuario no proporcionado.</p>";
    echo "<p><a href='index.php'>Volver al listado de usuarios</a></p>";
    exit();
}

//Conexión a la base de datos
include("../lib/conn.php");

$smt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
$smt->bind_param("i", $id);

if ($smt->execute()) {
    if ($smt->affected_rows > 0) {
        $_SESSION['flash_mensaje'] = 'Usuario eliminado correctamente';
        $_SESSION['flash_tipo'] = 'exito';
    } else {
        $_SESSION['flash_mensaje'] = 'No se encontro el usuario a eliminar';
        $_SESSION['flash_tipo'] = 'error';
    }
    header("Location: index.php");
    exit();
}

echo "<p style='color:red;'>Error al eliminar el usuario: " . $conexion->error . "</p>";
echo "<p><a href='index.php'>Volver al listado de usuarios</a></p>";
?>