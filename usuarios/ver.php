<?php
session_start();

if (!isset($_SESSION['usuario'])) {
	header('Location: ../login2.php');
	exit();
}

require_once __DIR__ . '/../lib/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
	$_SESSION['flash_mensaje'] = 'Usuario no válido';
	$_SESSION['flash_tipo'] = 'error';
	header('Location: index.php');
	exit();
}

$stmt = $conexion->prepare('SELECT id, nombre, correo, es_admin FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
	$_SESSION['flash_mensaje'] = 'Usuario no encontrado';
	$_SESSION['flash_tipo'] = 'error';
	header('Location: index.php');
	exit();
}

$usuario = $resultado->fetch_assoc();
$stmt->close();

$userName = $_SESSION['user_name'] ?? 'Usuario';
$currentView = 'usuarios';
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Detalle del Usuario</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #f5f5f5;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		.header {
			background-color: #2c3e50;
			color: white;
			padding: 15px 30px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
		}

		.header h1 { font-size: 24px; }

		.user-menu { display: flex; align-items: center; gap: 20px; }

		.logout-btn {
			background-color: #e74c3c;
			color: white;
			border: none;
			padding: 8px 15px;
			border-radius: 4px;
			cursor: pointer;
			text-decoration: none;
			transition: background-color 0.3s;
		}

		.logout-btn:hover { background-color: #c0392b; }

		.container { display: flex; flex: 1; }

		.sidebar {
			width: 250px;
			background-color: #34495e;
			color: white;
			padding: 20px 0;
			box-shadow: 2px 0 5px rgba(0,0,0,0.1);
		}

		.sidebar ul { list-style: none; }

		.sidebar a {
			display: block;
			color: white;
			text-decoration: none;
			padding: 15px 20px;
			transition: background-color 0.3s, padding-left 0.3s;
			border-left: 4px solid transparent;
		}

		.sidebar a:hover {
			background-color: #2c3e50;
			border-left-color: #3498db;
			padding-left: 25px;
		}

		.sidebar a.active {
			background-color: #2980b9;
			border-left-color: #3498db;
		}

		.main-content { flex: 1; padding: 30px; background-color: #f5f5f5; }

		.content-area {
			background-color: white;
			padding: 30px;
			border-radius: 16px;
			box-shadow: 0 20px 45px rgba(0,0,0,0.08);
		}

		.content-area h2 {
			color: #2c3e50;
			margin-bottom: 25px;
			border-bottom: 2px solid #3498db;
			padding-bottom: 10px;
		}

		.detail-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
			gap: 20px;
		}

		.detail-field { display: flex; flex-direction: column; gap: 6px; }

		.detail-field label { font-weight: 600; color: #4b5563; }

		.detail-value {
			padding: 12px;
			border: 1px solid #e5e7eb;
			border-radius: 10px;
			background: #f9fafb;
		}

		.tipo-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 90px;
			padding: 8px 14px;
			border-radius: 999px;
			font-size: 12px;
			font-weight: 700;
			letter-spacing: 0.8px;
		}

		.badge-admin { background: rgba(239, 68, 68, 0.15); color: #dc2626; }
		.badge-usuario { background: rgba(102, 126, 234, 0.18); color: #4f46e5; }

		.form-actions {
			display: flex;
			justify-content: flex-end;
			margin-top: 30px;
		}

		.btn-secondary {
			background: #e5e7eb;
			color: #374151;
			border: none;
			border-radius: 10px;
			padding: 12px 20px;
			font-weight: 600;
			cursor: pointer;
			transition: transform 0.15s ease, box-shadow 0.15s ease;
			text-decoration: none;
		}

		.btn-secondary:hover {
			transform: translateY(-2px);
			box-shadow: 0 14px 30px rgba(15,23,42,0.15);
		}

		.footer {
			background-color: #2c3e50;
			color: white;
			text-align: center;
			padding: 20px;
			margin-top: auto;
			box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
		}

		.footer a { color: #3498db; text-decoration: none; }
	</style>
</head>
<body>
	<?php include __DIR__ . '/../templates/header.php'; ?>

	<div class="container">
		<?php include __DIR__ . '/../templates/sidebar.php'; ?>

		<main class="main-content">
			<div class="content-area">
				<h2>Detalle del Usuario</h2>
				<div class="detail-grid">
					<div class="detail-field">
						<label>ID</label>
						<div class="detail-value"><?php echo htmlspecialchars((string) $usuario['id']); ?></div>
					</div>
					<div class="detail-field">
						<label>Nombre</label>
						<div class="detail-value"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
					</div>
					<div class="detail-field">
						<label>Correo</label>
						<div class="detail-value"><?php echo htmlspecialchars($usuario['correo']); ?></div>
					</div>
					<div class="detail-field">
						<label>Tipo</label>
						<div class="detail-value">
							<span class="tipo-badge <?php echo ((int) $usuario['es_admin'] === 1) ? 'badge-admin' : 'badge-usuario'; ?>">
								<?php echo ((int) $usuario['es_admin'] === 1) ? 'Admin' : 'Usuario'; ?>
							</span>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<a class="btn-secondary" href="index.php">Volver al listado</a>
				</div>
			</div>
		</main>
	</div>

	<?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
