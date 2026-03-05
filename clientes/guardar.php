<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $domicilio = $_POST['domicilio'];
    $giro = $_POST['giro'];
    $razon_social = $_POST['razon_social'];
    require_once __DIR__ . '/../conn.php';

    //Insertar datos en la base de datos
    $sql = "INSERT INTO clientes (nombre, domicilio, giro, razon_social) 
    VALUES ('".$nombre."', '".$domicilio."', '".$giro."', '".$razon_social."')";
    if ($conexion->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente<br>";
    }
    else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    echo "Si se ejecuto<br>";
    $conexion->close();
    //Redirigir a la pagina principal con mensaje de exito o error
    header("Location: index.php?mensaje=" . urlencode('Registro guardado exitosamente'));
    exit();
}
else {
    echo "Error: La solicitud no es de tipo POST.";
    exit;
}
?>