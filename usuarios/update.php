<?php

session_start();

//Verfica que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}
//tomamos los datos por metodo POST
$id = intval($_POST['id']);
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$es_admin = isset($_POST['es_admin']) ? 1 : 0;

//Validamos que los campos no estén vacíos
if ($id <= 0 || empty($nombre) || empty($correo)) {
    echo "<p style='color:red;'>Por favor, completa todos los campos.</p>";
    echo "<p><a href='editar.php?id=$id'>Volver a editar</a></p>";
    exit();
}

//Conexión a la base de datos
include("../lib/conn.php");

if (empty($password)) {
    //Si la contraseña está vacía, no la actualizamos
    $smt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, es_admin = ? WHERE id = ?");
    $smt->bind_param("ssii", $nombre, $correo, $es_admin, $id);
} else {
    //Si la contraseña no está vacía, la encriptamos y actualizamos
    $password = md5($password);
    $smt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ?, es_admin = ? WHERE id = ?");
    $smt->bind_param("sssii", $nombre, $correo, $password, $es_admin, $id);
}
//Ejecutamos la consulta
if ($smt->execute()) {
    $_SESSION['flash_mensaje'] = 'Usuario actualizado correctamente';
    $_SESSION['flash_tipo'] = 'exito';
    header("Location: index.php");
    exit();
} else {
    echo "<p style='color:red;'>Error al actualizar el usuario: " . $conexion->error . "</p>";
    echo "<p><a href='editar.php?id=$id'>Volver a editar</a></p>";
}
?>