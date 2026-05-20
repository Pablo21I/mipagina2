<?php
// dashboard.php
session_start();
//valida que el usuario haya iniciado sesión, si no redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}


require_once __DIR__ . '/../conexion.php';

$usuarios = [];
$mensaje = '';
$tipo_mensaje = '';

if (isset($_SESSION['flash_mensaje']) && isset($_SESSION['flash_tipo'])) {
    $mensaje = $_SESSION['flash_mensaje'];
    $tipo_mensaje = $_SESSION['flash_tipo'];
    unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
}

// Obtener todos los usuarios
$sql = "SELECT id, nombre, correo, es_admin FROM usuarios ORDER BY id DESC";
$resultado = $conexion->query($sql);

//Valida si el usuario existe al editar, si no existe redirige al index
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_usuario = "SELECT id, nombre, correo, contraseña, es_admin FROM usuarios WHERE id = " . $id;
    $resultado_usuario = $conexion->query($sql_usuario);
    
    if (!$resultado_usuario || $resultado_usuario->num_rows == 0) {
        header('Location: index.php');
        exit;
    }
}

if ($resultado) {
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        /* Header */
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info span {
            font-weight: 500;
        }

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

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Container Principal */
        .container {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin: 0;
        }

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

        /* Contenido Principal */
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f5f5f5;
        }

        .content-area {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 400px;
        }

        .content-area h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        /* Footer */
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .footer a {
            color: #3498db;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Listado de usuarios */
        .tabla-container {
            margin-top: 25px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
            background: #fff;
        }

        .usuarios-table {
            width: 100%;
            border-collapse: collapse;
        }

        .usuarios-table thead {
            background: linear-gradient(90deg, #6a82fb 0%, #8567f7 50%, #a177ff 100%);
        }

        .usuarios-table th {
            color: #fff;
            text-align: left;
            padding: 18px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .usuarios-table td {
            padding: 20px 18px;
            border-bottom: 1px solid #f1f1f6;
            color: #3d3d4e;
            font-size: 15px;
        }

        .usuarios-table tbody tr:last-child td {
            border-bottom: none;
        }

        .usuarios-table tbody tr:hover {
            background: #f9f9ff;
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

        .badge-admin {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .badge-usuario {
            background: rgba(102, 126, 234, 0.18);
            color: #4f46e5;
        }

        .acciones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-accion {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-accion i {
            font-size: 14px;
        }

        .btn-accion:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .btn-ver {
            background: rgba(118, 92, 255, 0.12);
            color: #5b4bff;
        }

        .btn-editar {
            background: rgba(59, 130, 246, 0.15);
            color: #2563eb;
        }

        .btn-eliminar {
            background: rgba(244, 114, 182, 0.18);
            color: #d8245c;
        }

        .mensaje {
            margin-top: 20px;
            padding: 15px 18px;
            border-radius: 12px;
            font-weight: 600;
            display: none;
        }

        .mensaje.show {
            display: block;
        }

        .mensaje.exito {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .mensaje.error {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-agregar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            background: linear-gradient(120deg, #6b73ff, #000dff);
            color: #fff;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(107, 115, 255, 0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-agregar:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(107, 115, 255, 0.45);
        }

        @media (max-width: 768px) {
            .usuarios-table td,
            .usuarios-table th {
                padding: 14px 12px;
            }

            .acciones {
                flex-direction: column;
            }

            .btn-accion {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
<!-- Header -->
<?php include ('../templates/header.php'); ?>

    <!-- Container Principal -->
    <div class="container">
        <!-- Sidebar -->
        <?php include ('../templates/sidebar.php'); ?>

        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="content-area">
                <h2>Listado de usuarios</h2>
                        <?php if ($mensaje): ?>
            <div class="mensaje show <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($usuarios) > 0): ?>
            <div class="tabla-container">
                <table class="usuarios-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><strong>#<?php echo htmlspecialchars($usuario['id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <span class="tipo-badge <?php echo $usuario['es_admin'] == 1 ? 'badge-admin' : 'badge-usuario'; ?>">
                                        <?php echo $usuario['es_admin'] == 1 ? 'Admin' : 'Usuario'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="acciones">
                                        <a href="ver.php?id=<?php echo $usuario['id']; ?>" class="btn-accion btn-ver">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn-accion btn-editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" action="eliminar.php" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                            <button type="submit" class="btn-accion btn-eliminar">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="sin-usuarios">
                <i class="fas fa-inbox"></i>
                <p>No hay usuarios registrados aún.</p>
                <a href="nuevo.php" class="btn-agregar">
                    <i class="fas fa-plus"></i> Crear primer usuario
                </a>
            </div>
        <?php endif; ?>
            </div>
        </main>
        <!-- Footer -->
        <?php include ('../templates/footer.php'); ?>
    </div>
</body>
</html>