<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit();
}

require_once '../conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Leer productos
        $query = "SELECT id, codigo, descripcion, precio, cantidad FROM productos ORDER BY id DESC";
        $result = $conexion->query($query);
        $productos = [];
        if ($result) {
            $productos = $result->fetch_all(MYSQLI_ASSOC);
        }
        echo json_encode($productos);
        break;

    case 'POST':
        // Crear o Actualizar
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $codigo = trim($_POST['codigo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $cantidad = intval($_POST['cantidad'] ?? 0);

        if (empty($codigo) || empty($descripcion)) {
            echo json_encode(["status" => "error", "message" => "Código y descripción son obligatorios"]);
            exit();
        }

        if ($id > 0) {
            // Actualizar
            $stmt = $conexion->prepare("UPDATE productos SET codigo=?, descripcion=?, precio=?, cantidad=? WHERE id=?");
            $stmt->bind_param("ssdii", $codigo, $descripcion, $precio, $cantidad, $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Producto actualizado con éxito"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al actualizar producto"]);
            }
            $stmt->close();
        } else {
            // Crear
            $stmt = $conexion->prepare("INSERT INTO productos (codigo, descripcion, precio, cantidad) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssdi", $codigo, $descripcion, $precio, $cantidad);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Producto creado con éxito"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al crear producto"]);
            }
            $stmt->close();
        }
        break;

    case 'DELETE':
        // Eliminar
        parse_str(file_get_contents("php://input"), $delete_vars);
        $id = isset($delete_vars['id']) ? intval($delete_vars['id']) : 0;
        
        // También soportar leer el ID si viene en json
        if ($id === 0) {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = isset($data['id']) ? intval($data['id']) : 0;
        }

        if ($id > 0) {
            $stmt = $conexion->prepare("DELETE FROM productos WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Producto eliminado con éxito"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al eliminar producto"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "ID de producto inválido"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Método no soportado"]);
        break;
}

$conexion->close();
?>
