<?php
session_start();

//Valida que exista la variable de sesion del usuario, si no existe redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login2.php");
    exit();
}


$nombre_usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Simulex</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        /* HEADER */
        header {
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 50%, #01579b 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 8px rgba(0, 153, 255, 0.3);
        }

        .company-name {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(90deg, #ffffff 0%, #00d4ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .user-menu:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* LAYOUT CONTAINER */
        .container-main {
            display: flex;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
        }

        /* SIDEBAR */
        aside {
            width: 250px;
            background: #1e3a5f;
            color: white;
            padding: 25px 0;
            position: fixed;
            top: 80px;
            left: 0;
            height: calc(100vh - 80px);
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0 20px;
            margin-bottom: 15px;
            color: #90a4c4;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #b0c4de;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .menu-item:hover {
            background-color: rgba(70, 130, 180, 0.3);
            color: white;
            border-left-color: #42a5f5;
        }

        .menu-item.active {
            background-color: rgba(66, 165, 245, 0.2);
            color: #42a5f5;
            border-left-color: #42a5f5;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* CONTENT AREA */
        .content-wrapper {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-header {
            margin-bottom: 30px;
        }

        .content-header h1 {
            font-size: 28px;
            color: #0d47a1;
            margin-bottom: 8px;
        }

        .content-header p {
            color: #666;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #42a5f5;
        }

        .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0d47a1;
        }

        /* FOOTER */
        footer {
            background: #1e3a5f;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            border-top: 1px solid #2a4a7c;
            margin: 0;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #42a5f5;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0d47a1;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            aside {
                width: 0;
                padding: 0;
            }

            .container-main {
                flex-direction: column;
            }

            .content-wrapper {
                margin-left: 0;
            }

            footer {
                margin-left: 0;
            }

            .header-company {
                flex-direction: column;
                gap: 5px;
            }

            .company-name {
                font-size: 18px;
            }

            main {
                padding: 15px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="header-left">
            <div class="logo">
                <i class="fas fa-microchip"></i>
            </div>
            <div>
                <div class="company-name">Simulex</div>
            </div>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span><?php echo htmlspecialchars($nombre_usuario); ?></span>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <div class="container-main">
        <!-- SIDEBAR -->
        <aside>
            <div class="menu-section">
                <div class="menu-title">Principal</div>
                <a href="#" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Reportes</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Gestión</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-cogs"></i>
                    <span>Configuración</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-database"></i>
                    <span>Base de Datos</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Operaciones</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Documentos</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-inbox"></i>
                    <span>Tareas</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-envelope"></i>
                    <span>Mensajes</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Cuenta</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Mi Perfil</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- CONTENT WRAPPER -->
        <div class="content-wrapper">
            <!-- MAIN CONTENT -->
            <main>
                <div class="content-header">
                    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>
                    <p>Aquí puedes gestionar todas las operaciones de tu empresa</p>
                </div>

                <!-- STATISTICS -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-label">Total de Usuarios</div>
                        <div class="stat-value">248</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Ingresos Mensuales</div>
                        <div class="stat-value">$15.8K</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Proyectos Activos</div>
                        <div class="stat-value">12</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tareas Pendientes</div>
                        <div class="stat-value">24</div>
                    </div>
                </div>

                <!-- CONTENT CARDS -->
                <div class="card">
                    <h2 style="color: #0d47a1; margin-bottom: 15px;">Actividad Reciente</h2>
                    <p style="color: #666; line-height: 1.6;">
                        Este es el área principal de trabajo donde puedes mostrar el contenido dinámico de cada sección del menú. 
                        Aquí se pueden cargar diferentes páginas según lo que el usuario seleccione del menú lateral.
                    </p>
                </div>

                <div class="card">
                    <h2 style="color: #0d47a1; margin-bottom: 15px;">Instrucciones</h2>
                    <ul style="margin-left: 20px; color: #666; line-height: 1.8;">
                        <li>Modifica el logo en el header con tu imagen de empresa</li>
                        <li>Cambia "MiEmpresa" por el nombre real de tu empresa</li>
                        <li>Personaliza los items del menú izquierdo según tus necesidades</li>
                        <li>Usa esta estructura para cargar contenido dinámico con PHP o JavaScript</li>
                        <li>Los colores azules se pueden ajustar fácilmente modificando los códigos hexadecimales</li>
                    </ul>
                </div>
            </main>

            <!-- FOOTER -->
            <footer>
                <p>&copy; 2024 Simulex. Todos los derechos reservados. | Política de Privacidad | Términos de Servicio</p>
            </footer>
        </div>
    </div>

    <script>
        // Función para activar items del menú
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
