<?php
session_start();
//valida que el usuario haya iniciado sesión, si no redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}


require_once __DIR__ . '/../lib/conn.php';

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



$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
            max-width: 1100px;
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
        
        .btn-agregar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-agregar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .mensaje {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: none;
            animation: slideDown 0.3s ease;
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
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        }
        
        tr:hover {
            background: #f8f9ff;
        }
        
        .tipo-badge {
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
        
        .btn-ver:hover {
            background: rgba(102, 126, 234, 0.2);
        }
        
        .btn-editar {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .btn-editar:hover {
            background: rgba(59, 130, 246, 0.2);
        }
        
        .btn-eliminar {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .btn-eliminar:hover {
            background: rgba(239, 68, 68, 0.2);
        }
        
        .sin-usuarios {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .sin-usuarios i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .sin-usuarios p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            h1 {
                font-size: 24px;
            }
            
            th, td {
                padding: 12px;
                font-size: 14px;
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
    <div class="container">
        <div class="header">
            <h1>👥 Listado de Usuarios</h1>
            <a href="nuevo.php" class="btn-agregar">
                <i class="fas fa-plus"></i> Agregar Usuario
            </a>
        </div>
        
        <?php if ($mensaje): ?>
            <div class="mensaje show <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($usuarios) > 0): ?>
            <div class="tabla-container">
                <table>
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
</body>
</html>
