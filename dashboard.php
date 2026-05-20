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

        :root {
            --primary: #1e40af;
            --secondary: #0ea5e9;
            --accent: #10b981;
            --dark: #0f172a;
            --light: #f8fafc;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: #333;
        }

        /* HEADER */
        header {
            background: linear-gradient(135deg, var(--dark) 0%, #2d3436 100%);
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
            font-weight: 800;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
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
            background: var(--dark);
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
            background-color: rgba(30, 64, 175, 0.3);
            color: white;
            border-left-color: var(--secondary);
        }

        .menu-item.active {
            background-color: rgba(30, 64, 175, 0.5);
            color: white;
            border-left-color: var(--secondary);
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
            color: var(--primary);
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
            border-left: 4px solid var(--secondary);
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
            color: var(--primary);
        }

        /* FOOTER */
        footer {
            background: var(--dark);
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
            </div>

            <div class="menu-section">
                <div class="menu-title">Directorio</div>
                <a href="clientes/index.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Gestión de Clientes</span>
                </a>
                <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == 1): ?>
                <a href="usuarios/index.php" class="menu-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Gestión de Usuarios</span>
                </a>
                <?php endif; ?>
            </div>

            <div class="menu-section">
                <div class="menu-title">Inventario</div>
                <a href="productos_js/index.php" class="menu-item">
                    <i class="fas fa-box"></i>
                    <span>Gestión de Productos</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Cuenta</div>
                <a href="logout.php" class="menu-item">
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
                    <h2 style="color: var(--primary); margin-bottom: 15px;">Actividad Reciente</h2>
                    <p style="color: #666; line-height: 1.6;">
                        Este es el área principal de trabajo donde puedes mostrar el contenido dinámico de cada sección del menú. 
                        Aquí se pueden cargar diferentes páginas según lo que el usuario seleccione del menú lateral.
                    </p>
                </div>

                <div class="card">
                    <h2 style="color: var(--primary); margin-bottom: 15px;">Módulos de Administración</h2>
                    <ul style="margin-left: 20px; color: #666; line-height: 1.8;">
                        <li><strong>Directorio de Clientes:</strong> Administra y organiza tu cartera de clientes y usuarios registrados en la plataforma.</li>
                        <li><strong>Inventario de Productos:</strong> Gestiona el catálogo de productos disponibles de manera interactiva y en tiempo real.</li>
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
        // Marcar el item activo según la URL actual (opcional, pero mejora la experiencia)
        const currentUrl = window.location.href;
        document.querySelectorAll('.menu-item').forEach(item => {
            if(item.href === currentUrl) {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>
