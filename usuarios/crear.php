<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../lib/conn.php';

// Crear tabla
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	usuario VARCHAR(120) NOT NULL UNIQUE,
	contrasena VARCHAR(255) NOT NULL,
	es_admin TINYINT DEFAULT 0,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conexion->query($sql) === true) {
	echo "Tabla 'usuarios' creada (o ya existía) correctamente.\n\n";
} else {
	echo "Error al crear tabla: " . $conexion->error;
	$conexion->close();
	exit;
}

// Insertar usuario si se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : '';
	$password = isset($_POST["password"]) ? $_POST["password"] : '';
	
	if (!empty($usuario) && !empty($password)) {
		// Encriptar contraseña con MD5
		$password_encriptada = md5($password);
		
		$sql_insert = "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)";
		$stmt = $conexion->prepare($sql_insert);
		$stmt->bind_param("ss", $usuario, $password_encriptada);
		
		if ($stmt->execute()) {
			echo "Usuario '$usuario' creado exitosamente con contraseña encriptada en MD5.";
		} else {
			echo "Error al crear usuario: " . $stmt->error;
		}
		$stmt->close();
	} else {
		echo "Por favor, envía usuario y contraseña.";
	}
}

$conexion->close();
?>

