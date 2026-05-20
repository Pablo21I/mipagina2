<?php
$currentView = $currentView ?? '';
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$relativeRoot = '';

if ($docRoot && strpos($projectRoot, $docRoot) === 0) {
    $relativeRoot = substr($projectRoot, strlen($docRoot));
}

$basePath = '/' . trim($relativeRoot, '/');
if ($basePath === '/') {
    $basePath = '/';
}

$baseUrl = rtrim($basePath, '/') . '/';
?>
        <aside class="sidebar">
            <ul>
                <li><a class="<?php echo $currentView === 'dashboard' ? 'active' : ''; ?>" href="<?php echo $baseUrl; ?>templates/plantilla.php?view=dashboard">Dashboard</a></li>
                <li><a class="<?php echo $currentView === 'clientes' ? 'active' : ''; ?>" href="<?php echo $baseUrl; ?>templates/plantilla.php?view=clientes">Clientes</a></li>
                <li><a class="<?php echo $currentView === 'usuarios' ? 'active' : ''; ?>" href="<?php echo $baseUrl; ?>templates/plantilla.php?view=usuarios">Usuarios</a></li>
                <li><a href="<?php echo $baseUrl; ?>reportes.php">Reportes</a></li>
                <li><a href="<?php echo $baseUrl; ?>configuracion.php">Configuración</a></li>
                <li><a href="<?php echo $baseUrl; ?>ayuda.php">Ayuda</a></li>
            </ul>
        </aside>