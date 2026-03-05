<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Acceso</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 50%, #01579b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 20px;
        }

        .login-left {
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 80%, #01579b 100%);
            color: white;
            padding: 80px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100%;
        }

        .login-left i {
            font-size: 100px;
            margin-bottom: 35px;
            opacity: 0.95;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .login-left h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .login-left p {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.7;
            max-width: 280px;
        }

        .login-right {
            padding: 70px 55px;
        }

        .login-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .login-title h3 {
            color: #2d3748;
            font-weight: 700;
            font-size: 30px;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .login-title p {
            color: #718096;
            font-size: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 22px;
        }

        .input-group-text {
            background-color: #f7fafc;
            border-right: none;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px 0 0 10px;
        }

        .input-group-text i {
            color: #42a5f5;
            font-size: 19px;
        }

        .form-control {
            border-left: none;
            padding: 14px 16px;
            font-size: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 0 10px 10px 0;
            background-color: #f7fafc;
        }

        .form-control:focus {
            border-color: #e2e8f0;
            box-shadow: none;
            background-color: white;
        }

        .input-group:focus-within .input-group-text {
            border-color: #42a5f5;
            background-color: white;
        }

        .input-group:focus-within .form-control {
            border-color: #42a5f5;
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #0d47a1 0%, #42a5f5 100%);
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 71, 161, 0.35);
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(66, 165, 245, 0.5);
            background: linear-gradient(135deg, #01579b 0%, #0d47a1 100%);
        }

        .forgot-password {
            color: #42a5f5;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .forgot-password:hover {
            color: #0d47a1;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 32px 0 28px;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 18px;
            position: relative;
            color: #a0aec0;
            font-size: 13px;
            font-weight: 500;
        }

        .social-login {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }

        .btn-social {
            flex: 1;
            padding: 13px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            color: #4a5568;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .btn-google:hover {
            border-color: #db4437;
            color: #db4437;
            background: #fef5f5;
        }

        .btn-facebook:hover {
            border-color: #4267B2;
            color: #4267B2;
            background: #f0f4ff;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #718096;
            font-size: 14px;
        }

        .register-link a {
            color: #42a5f5;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .register-link a:hover {
            color: #0d47a1;
            text-decoration: underline;
        }

        .form-check {
            padding-left: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0.15em;
            cursor: pointer;
            border: 2px solid #cbd5e0;
        }

        .form-check-input:checked {
            background-color: #42a5f5;
            border-color: #42a5f5;
        }

        .form-check-input:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 0.2rem rgba(66, 165, 245, 0.15);
        }

        .form-check-label {
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
            margin-left: 8px;
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }

            .login-right {
                padding: 50px 35px;
            }

            .login-title h3 {
                font-size: 26px;
            }

            .social-login {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .login-right {
                padding: 40px 25px;
            }

            .login-title h3 {
                font-size: 24px;
            }

            .login-card {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Panel Izquierdo -->
                <div class="col-md-5 login-left">
                    <i class="bi bi-shield-lock-fill"></i>
                    <h2>Sistema de Acceso</h2>
                    <p>Plataforma segura y profesional para la gestión de tu cuenta. Accede a todas las funcionalidades de tu sistema.</p>
                </div>

                <!-- Panel Derecho - Formulario -->
                <div class="col-md-7 login-right">
                    <div class="login-title">
                        <h3>Iniciar Sesión</h3>
                        <p>Ingresa tus credenciales para acceder</p>
                    </div>

                    <form action="verificacion.php" method="POST">
                        <!-- Correo Electrónico -->
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input name="email" id="email" type="email" class="form-control" placeholder="Correo electrónico" required>
                        </div>

                        <!-- Contraseña -->
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input name="password" id="password" type="password" class="form-control" placeholder="Contraseña" required>
                        </div>

                        <!-- Recordar y Olvidé mi contraseña -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Recuérdame
                                </label>
                            </div>
                            <a href="#" class="forgot-password">¿Olvidé mi contraseña?</a>
                        </div>

                        <!-- Botón Login -->
                        <button type="submit" class="btn btn-login w-100 text-white">Iniciar Sesión</button>
                    </form>

                    <!-- Divisor -->
                    <div class="divider">
                        <span>O continúa con</span>
                    </div>

                    <!-- Botones Sociales -->
                    <div class="social-login">
                        <button type="button" class="btn-social btn-google">
                            <i class="bi bi-google"></i> Google
                        </button>
                        <button type="button" class="btn-social btn-facebook">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                    </div>

                    <!-- Link de Registro -->
                    <div class="register-link">
                        ¿No tienes cuenta? <a href="#">Regístrate aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Validación del formulario -->
    <script>
        // Validación de Bootstrap
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>