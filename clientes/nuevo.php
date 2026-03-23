<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login2.php');
    exit();
}

$mensaje = '';
$tipo_mensaje = '';
if (isset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo'])) {
    $mensaje = $_SESSION['flash_mensaje'];
    $tipo_mensaje = $_SESSION['flash_tipo'];
    unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        }
        .container { display: flex; flex: 1; }
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 20px 0;
        }
        .sidebar ul { list-style: none; }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2980b9;
            border-left-color: #3498db;
        }
        .main-content { flex: 1; padding: 30px; }
        .content-area {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.08);
        }
        .content-area h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .form-field { display: flex; flex-direction: column; gap: 8px; }
        .form-field label { font-weight: 600; color: #374151; }
        .form-field input {
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
        }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary { background: linear-gradient(120deg, #6b73ff, #000dff); color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .mensaje {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .mensaje.exito { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .mensaje.error { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>

    <div class="container">
        <?php $currentView = 'clientes'; include __DIR__ . '/../templates/sidebar.php'; ?>

        <main class="main-content">
            <div class="content-area">
                <h2>Registrar Cliente</h2>

                <?php if ($mensaje): ?>
                    <div class="mensaje <?php echo htmlspecialchars($tipo_mensaje); ?>">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="guardar.php">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-field">
                            <label for="domicilio">Domicilio</label>
                            <input type="text" id="domicilio" name="domicilio" required>
                        </div>
                        <div class="form-field">
                            <label for="giro">Giro</label>
                            <input type="text" id="giro" name="giro" required>
                        </div>
                        <div class="form-field">
                            <label for="razon_social">Razón Social</label>
                            <input type="text" id="razon_social" name="razon_social" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
