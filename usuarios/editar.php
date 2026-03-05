<?php
session_start();

//Valida que exista la variable de sesion del usuario, si no existe redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}

// Incluir conexión a la base de datos
require_once __DIR__ . '/../lib/conn.php';

$id = intval($_GET['id']);

// Consultar el usuario en la base de datos
$sql = "SELECT id, nombre, correo, contraseña, es_admin FROM usuarios WHERE id = " . $id;
$resultado = $conexion->query($sql);

if (!$resultado || $resultado->num_rows == 0) {
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
    <title>Editar Usuario</title>
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
            max-width: 500px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            font-weight: normal;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-guardar {
            background-color: #4CAF50;
            color: white;
        }
        
        .btn-guardar:hover {
            background-color: #45a049;
        }
        
        .btn-cancelar {
            background-color: #f44336;
            color: white;
        }
        
        .btn-cancelar:hover {
            background-color: #da190b;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            padding: 5px;
            font-size: 18px;
        }
        
        .toggle-password:hover {
            color: #333;
        }
        
        small {
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje-error">
                <?php 
                    if ($_GET['error'] === 'campos_vacios') {
                        echo 'Por favor completa todos los campos.';
                    } elseif ($_GET['error'] === 'bd') {
                        echo 'Error al actualizar el usuario en la base de datos.';
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="procesar_editar.php">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la actual" style="padding-right: 40px;">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
                <small style="color: #666; font-size: 12px;">⚠️ La contraseña actual está hasheada (no se puede ver). Solo ingresa una nueva si deseas cambiarla.</small>
            </div>
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="es_admin" name="es_admin" value="1" <?php echo ($usuario['es_admin'] == 1) ? 'checked' : ''; ?>>
                    <label for="es_admin">Es Administrador</label>
                </div>
            </div>
            
            <div class="form-buttons">
    
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
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
                <button type="button" class="btn-cancelar" onclick="window.location.href='index.php'">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
