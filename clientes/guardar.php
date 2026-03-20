<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $domicilio = trim($_POST['domicilio'] ?? '');
    $giro = trim($_POST['giro'] ?? '');
    $razon_social = trim($_POST['razon_social'] ?? '');

    require_once __DIR__ . '/../lib/conn.php';

    $sql = "INSERT INTO clientes (nombre, domicilio, giro, razon_social) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    $mensaje = 'Registro guardado exitosamente';
    $tipo = 'exito';

    if ($stmt) {
        $stmt->bind_param('ssss', $nombre, $domicilio, $giro, $razon_social);
        if (!$stmt->execute()) {
            $mensaje = 'Error al guardar el cliente. Intenta nuevamente.';
            $tipo = 'error';
        }
        $stmt->close();
    } else {
        $mensaje = 'No se pudo preparar la solicitud.';
        $tipo = 'error';
    }

    $conexion->close();
    header('Location: ../templates/plantilla.php?view=clientes&mensaje=' . urlencode($mensaje) . '&tipo=' . urlencode($tipo));
    exit();
}

http_response_code(405);
echo 'Error: La solicitud no es de tipo POST.';
exit;
?>