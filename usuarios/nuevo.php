<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login2.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
// Procesar formulario de registro
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $repetir_contrasena = $_POST['repetir_contrasena'] ?? '';
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;
    $tipo_usuario = $es_admin ? 'admin' : 'usuario';

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($contrasena) || empty($repetir_contrasena)) {
        $mensaje = 'Por favor completa todos los campos.';
        $tipo_mensaje = 'error';
    } elseif ($contrasena !== $repetir_contrasena) {
        $mensaje = 'Las contraseñas no coinciden.';
        $tipo_mensaje = 'error';
    } elseif (strlen($contrasena) < 8) {
        $mensaje = 'La contraseña debe tener mínimo 8 caracteres.';
        $tipo_mensaje = 'error';
    } else {
        require_once __DIR__ . '/../lib/conn.php';
        if ($conexion) {
            // Verificar si el correo ya existe
            $sql_verificar = "SELECT id FROM usuarios WHERE correo = '" . $conexion->real_escape_string($correo) . "'";
            $resultado = $conexion->query($sql_verificar);
            
            if ($resultado && $resultado->num_rows > 0) {
                $mensaje = 'El correo ya está registrado.';
                $tipo_mensaje = 'error';
            } else {
                // Encriptar contraseña con MD5
                $contrasena_hash = md5($contrasena);
                
                // Insertar el nuevo usuario
                $sql_insertar = "INSERT INTO usuarios (nombre, correo, contraseña, es_admin) 
                                VALUES (
                                    '" . $conexion->real_escape_string($nombre) . "',
                                    '" . $conexion->real_escape_string($correo) . "',
                                    '" . $contrasena_hash . "',
                                    " . $es_admin . "
                                )";
                
                if ($conexion->query($sql_insertar) === TRUE) {
                    $mensaje = '¡Usuario registrado exitosamente! Ahora puedes iniciar sesión.';
                    $tipo_mensaje = 'exito';
                    
                    // Limpiar los campos
                    $nombre = '';
                    $correo = '';
                    $contrasena = '';
                    $repetir_contrasena = '';
                    $es_admin = 0;
                } else {
                    $mensaje = 'Error al registrar el usuario: ' . $conexion->error;
                    $tipo_mensaje = 'error';
                }
            }
            
            $conexion->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de Usuario</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --accent: #22d3ee;
            --accent-2: #f59e0b;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Poppins", "Segoe UI", sans-serif;
            color: var(--text);
            background: radial-gradient(1200px 600px at 10% 10%, #1f2937, transparent),
                radial-gradient(900px 500px at 90% 20%, #0ea5e9, transparent),
                linear-gradient(135deg, #0b1020, #0f172a 45%, #0b1020);
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .card {
            width: 100%;
            max-width: 760px;
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.92), rgba(17, 24, 39, 0.96));
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: -120px 40% auto auto;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.35), transparent 70%);
            filter: blur(2px);
            pointer-events: none;
        }

        .header {
            display: grid;
            gap: 8px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 0;
            color: var(--muted);
        }

        .mensaje {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            position: relative;
            z-index: 1;
            animation: slideDown 0.3s ease;
        }

        .mensaje.show {
            display: block;
        }

        .mensaje.exito {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.5);
            color: #10b981;
        }

        .mensaje.error {
            background: rgba(239, 68, 68, 0.2);
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

        .form-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            position: relative;
            z-index: 1;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: var(--muted);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: rgba(15, 23, 42, 0.6);
            color: var(--text);
            outline: none;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.15);
        }

        .full {
            grid-column: 1 / -1;
        }

        .checkboxes {
            display: grid;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .checkboxes label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: var(--text);
            font-size: 15px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #38bdf8);
            color: #0b1120;
            box-shadow: 0 12px 24px rgba(34, 211, 238, 0.25);
        }

        .btn-secondary {
            background: rgba(148, 163, 184, 0.12);
            color: var(--text);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .footer {
            margin-top: 18px;
            color: var(--muted);
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        .footer a {
            color: var(--accent);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 540px) {
            .card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <header class="header">
            <h1>Registro de Usuario</h1>
            <p>Completa los datos para crear tu acceso.</p>
        </header>

        <?php if ($mensaje): ?>
            <div class="mensaje show <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-grid">
                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required />
                </div>

                <div>
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" placeholder="tu@correo.com" value="<?php echo htmlspecialchars($correo ?? ''); ?>" required />
                </div>

                <div>
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Min. 8 caracteres" minlength="8" required />
                </div>

                <div>
                    <label for="repetir_contrasena">Repetir contraseña</label>
                    <input type="password" id="repetir_contrasena" name="repetir_contrasena" placeholder="Repite tu contraseña" minlength="8" required />
                </div>

                <div class="full">
                    <label>Permisos</label>
                    <div class="checkboxes">
                        <label>
                            <input type="checkbox" name="es_admin" value="1" <?php echo !empty($es_admin) ? 'checked' : ''; ?> />
                            Es administrador
                        </label>
                    </div>
                    <div class="hint">Si no marcas esta opción, se registra como usuario normal.</div>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Crear cuenta</button>
                <button class="btn btn-secondary" type="reset">Limpiar</button>
            </div>
        </form>

        <div class="footer">
            ¿Ya tienes cuenta? <a href="javascript:history.back()">Volver</a>
        </div>
    </main>
</body>
</html>