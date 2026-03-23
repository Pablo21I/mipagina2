<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}

require_once __DIR__ . '/../lib/conn.php';

$clientes = [];
$mensaje = '';
$tipo_mensaje = '';

if (isset($_SESSION['flash_mensaje']) && isset($_SESSION['flash_tipo'])) {
    $mensaje = $_SESSION['flash_mensaje'];
    $tipo_mensaje = $_SESSION['flash_tipo'];
    unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
}

$sql = "SELECT id, nombre, domicilio, giro, razon_social FROM clientes ORDER BY id DESC";
$resultado = $conexion->query($sql);

if ($resultado) {
    $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            color: #333;
            font-size: 32px;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-agregar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-volver {
            background: #4b5563;
            box-shadow: 0 4px 15px rgba(75, 85, 99, 0.35);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .mensaje {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: none;
        }

        .mensaje.show {
            display: block;
        }

        .mensaje.exito {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.5);
            color: #10b981;
        }

        .mensaje.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #ef4444;
        }

        .tabla-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            vertical-align: top;
        }

        tr:hover {
            background: #f8f9ff;
        }

        .acciones {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-accion {
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-ver {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn-editar {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .btn-eliminar {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .sin-clientes {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .sin-clientes i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 Listado de Clientes</h1>
            <div class="header-actions">
                <a href="../templates/plantilla.php?view=dashboard" class="btn btn-volver">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <a href="nuevo.php" class="btn btn-agregar">
                    <i class="fas fa-plus"></i> Agregar Cliente
                </a>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <div class="mensaje show <?php echo htmlspecialchars($tipo_mensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if (count($clientes) > 0): ?>
            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Domicilio</th>
                            <th>Giro</th>
                            <th>Razón Social</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><strong>#<?php echo htmlspecialchars((string) $cliente['id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['domicilio']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['giro']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['razon_social']); ?></td>
                                <td>
                                    <div class="acciones">
                                        <a href="ver.php?id=<?php echo (int) $cliente['id']; ?>" class="btn-accion btn-ver">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="editar.php?id=<?php echo (int) $cliente['id']; ?>" class="btn-accion btn-editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" action="eliminar.php" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                            <input type="hidden" name="id" value="<?php echo (int) $cliente['id']; ?>">
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
            <div class="sin-clientes">
                <i class="fas fa-inbox"></i>
                <p>No hay clientes registrados aún.</p>
                <br>
                <a href="nuevo.php" class="btn btn-agregar">
                    <i class="fas fa-plus"></i> Crear primer cliente
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
