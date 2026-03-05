<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #18a270 0%, #000000 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #1ae1a2;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.5;
        }

        .input-wrapper.email::before {
            content: '📧';
        }

        .input-wrapper.password::before {
            content: '🔒';
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #2eeea8;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control::placeholder {
            color: #999;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .remember-me label {
            color: #666;
            cursor: pointer;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #2eeea8;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #18a270 0%, #5c5858 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #18a270;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #2eeea8;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>¡Bienvenido!</h1>
            <p>Inicia sesión para continuar</p>
        </div>

        <form action="verificacion.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-wrapper email">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="ejemplo@correo.com"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrapper password">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Ingresa tu contraseña"
                        required
                    >
                </div>
            </div>

            <div class="options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordarme</label>
                </div>
                <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="#">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>