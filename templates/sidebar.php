<?php $currentView = $currentView ?? ''; ?>
        <aside class="sidebar">
            <ul>
                <li><a class="<?php echo $currentView === 'dashboard' ? 'active' : ''; ?>" href="http://localhost/mipagina2/templates/plantilla.php?view=dashboard">Dashboard</a></li>
                <li><a class="<?php echo $currentView === 'clientes' ? 'active' : ''; ?>" href="http://localhost/mipagina2/templates/plantilla.php?view=clientes">Clientes</a></li>
                <li><a class="<?php echo $currentView === 'usuarios' ? 'active' : ''; ?>" href="http://localhost/mipagina2/templates/plantilla.php?view=usuarios">Usuarios</a></li>
                <li><a href="reportes.php">Reportes</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
                <li><a href="ayuda.php">Ayuda</a></li>
            </ul>
        </aside>