<?php
$userName = isset($userName) ? $userName : ($_SESSION['user_name'] ?? 'Usuario');
?>
    <header class="header">
        <h1>Mi pagina</h1>
        <div class="user-menu">
            <div class="user-info">
                <span><?php echo htmlspecialchars($userName); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </header>