<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login2.php');
    exit();
}

require_once __DIR__ . '/../lib/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit();
}

$stmt = $conexion->prepare('SELECT id, nombre, correo, es_admin FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
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
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .logout-btn:hover { background-color: #c0392b; }
        .container { display: flex; flex: 1; }
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: #fff;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar ul { list-style: none; }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: background-color 0.3s, padding-left 0.3s;
        }
        .sidebar a:hover { background-color: #2c3e50; border-left-color: #3498db; padding-left: 25px; }
        .sidebar a.active { background-color: #2980b9; border-left-color: #3498db; }
        .main-content { flex: 1; padding: 30px; background-color: #f5f5f5; }
        .content-area {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.08);
        }
        .content-area h2 {
            color: #2c3e50;
            margin-bottom: 25px;
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
        .form-field input[type="text"],
        .form-field input[type="email"],
        .form-field input[type="password"] {
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }
        .form-field input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .checkbox-group input { width: 18px; height: 18px; }
        .checkbox-group label { margin: 0; font-weight: 500; }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .btn-primary,
        .btn-secondary {
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary {
            background: linear-gradient(120deg, #6b73ff, #000dff);
            color: #fff;
            box-shadow: 0 10px 25px rgba(107, 115, 255, 0.35);
        }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-primary:hover,
        .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(15,23,42,0.15); }
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }
        .footer a { color: #3498db; text-decoration: none; }
        small { color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>

    <div class="container">
        <?php include __DIR__ . '/../templates/sidebar.php'; ?>

        <main class="main-content">
            <div class="content-area">
                <h2>Editar Usuario</h2>

                <form method="POST" action="update.php">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                        </div>
                        <div class="form-field">
                            <label for="correo">Correo</label>
                            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                        </div>
                        <div class="form-field">
                            <label for="password">Contraseña</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la actual" style="padding-right: 40px;">
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <span id="eye-icon">👁️</span>
                                </button>
                            </div>
                            <small>⚠️ La contraseña actual está hasheada. Ingresa una nueva solo si deseas cambiarla.</small>
                        </div>
                        <div class="form-field">
                            <label>Rol</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="es_admin" name="es_admin" value="1" <?php echo ($usuario['es_admin'] == 1) ? 'checked' : ''; ?>>
                                <label for="es_admin">Es Administrador</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">Cancelar</button>
                        <button type="submit" class="btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../templates/footer.php'; ?>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>
