<?php
session_start();

//valida que el usuario haya iniciado sesión, si no redirige al login
if (!isset($_SESSION['usuario'])) {
	header("Location: ../login2.php");
	exit();
}
//Valida que se haya proporcionado un ID por GET
require_once __DIR__ . '/../lib/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
	$_SESSION['flash_mensaje'] = 'Usuario no valido';
	$_SESSION['flash_tipo'] = 'error';
	header('Location: index.php');
	exit;
}
//Consulta para obtener los detalles del usuario
$stmt = $conexion->prepare("SELECT id, nombre, correo, es_admin FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
	$_SESSION['flash_mensaje'] = 'Usuario no encontrado';
	$_SESSION['flash_tipo'] = 'error';
	header('Location: index.php');
	exit;
}

$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ver Usuario</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
			padding: 20px;
		}

		.container {
			max-width: 560px;
			margin: 50px auto;
			background-color: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		h1 {
			color: #333;
			margin-bottom: 25px;
			text-align: center;
		}

		.campo {
			margin-bottom: 18px;
		}

		.campo label {
			display: block;
			margin-bottom: 6px;
			color: #555;
			font-weight: bold;
		}

		.valor {
			width: 100%;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			background-color: #f9f9f9;
			color: #333;
		}

		.badge {
			display: inline-block;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.badge-admin {
			background: rgba(239, 68, 68, 0.1);
			color: #ef4444;
		}

		.badge-usuario {
			background: rgba(102, 126, 234, 0.1);
			color: #667eea;
		}

		.acciones {
			margin-top: 28px;
			text-align: center;
		}

		.btn-volver {
			display: inline-block;
			padding: 10px 18px;
			background-color: #667eea;
			color: white;
			text-decoration: none;
			border-radius: 6px;
			font-weight: bold;
		}

		.btn-volver:hover {
			background-color: #5967c8;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Detalle del Usuario</h1>

		<div class="campo">
			<label>ID</label>
			<div class="valor"><?php echo htmlspecialchars((string)$usuario['id']); ?></div>
		</div>

		<div class="campo">
			<label>Nombre</label>
			<div class="valor"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
		</div>

		<div class="campo">
			<label>Correo</label>
			<div class="valor"><?php echo htmlspecialchars($usuario['correo']); ?></div>
		</div>

		<div class="campo">
			<label>Tipo</label>
			<div class="valor">
				<span class="badge <?php echo ((int)$usuario['es_admin'] === 1) ? 'badge-admin' : 'badge-usuario'; ?>">
					<?php echo ((int)$usuario['es_admin'] === 1) ? 'Admin' : 'Usuario'; ?>
				</span>
			</div>
		</div>

		<div class="acciones">
			<a class="btn-volver" href="index.php">Volver al listado</a>
		</div>
	</div>
</body>
</html>
