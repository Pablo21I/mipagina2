<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login2.php");
    exit();
}

if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] != 1) {
    echo "<script>alert('Acceso Denegado: Esta zona es solo para Administradores.'); window.location.href='../dashboard.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos (SPA)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #0ea5e9;
            --accent: #10b981;
            --dark: #0f172a;
            --light: #f8fafc;
            --warning: #f59e0b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--light); color: #333; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-volver { background: var(--dark); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .btn-volver:hover { background: #1e293b; }
        
        .main-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; }
        
        .form-card, .table-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-card h2, .table-card h2 { margin-bottom: 20px; color: var(--primary); font-size: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; color: #4b5563; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        
        .btn-submit { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--dark); }
        .btn-update { width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: none; }
        .btn-update:hover { background: #059669; }
        .btn-cancel { width: 100%; padding: 10px; background: #9ca3af; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; display: none; }
        
        #mensaje-sistema { margin-top: 15px; padding: 10px; border-radius: 6px; font-weight: 600; text-align: center; display: none; }
        .msg-success { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .msg-error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .msg-info { background: #dbeafe; color: #1e40af; border: 1px solid #60a5fa; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f8fafc; font-weight: 600; color: #4b5563; }
        tr:hover { background: #f9fafb; }
        
        .btn-accion { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; color: white; margin-right: 5px; transition: opacity 0.2s; }
        .btn-accion:hover { opacity: 0.8; }
        .btn-edit-row { background: var(--secondary); }
        .btn-delete-row { background: #ef4444; }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📦 Inventario de Productos</h1>

            </div>
            <a href="../dashboard.php" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
        </div>
        
        <div class="main-grid">
            <!-- Formulario -->
            <div class="form-card">
                <h2 id="form-title">Nuevo Producto</h2>
                <form id="form-producto">
                    <input type="hidden" id="prod-id" name="id">
                    
                    <div class="form-group">
                        <label for="prod-codigo">Código</label>
                        <input type="text" id="prod-codigo" name="codigo" required placeholder="Ej. PRD-001">
                    </div>
                    
                    <div class="form-group">
                        <label for="prod-descripcion">Descripción</label>
                        <input type="text" id="prod-descripcion" name="descripcion" required placeholder="Nombre del producto">
                    </div>
                    
                    <div class="form-group">
                        <label for="prod-precio">Precio ($)</label>
                        <input type="number" id="prod-precio" name="precio" required step="0.01" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="prod-cantidad">Cantidad en Stock</label>
                        <input type="number" id="prod-cantidad" name="cantidad" required min="0">
                    </div>
                    
                    <button type="submit" id="btn-guardar" class="btn-submit"><i class="fas fa-save"></i> Guardar Producto</button>
                    <button type="button" id="btn-actualizar" class="btn-update"><i class="fas fa-sync"></i> Actualizar Producto</button>
                    <button type="button" id="btn-cancelar" class="btn-cancel">Cancelar Edición</button>
                    
                    <div id="mensaje-sistema"></div>
                </form>
            </div>
            
            <!-- Tabla -->
            <div class="table-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    <h2 style="border: none; margin: 0; padding: 0;">Inventario Actual</h2>
                    <button id="btn-refrescar" style="background: transparent; border: 1px solid #d1d5db; padding: 6px 12px; border-radius: 4px; cursor: pointer;"><i class="fas fa-sync-alt"></i> Actualizar</button>
                </div>
                
                <div style="overflow-x: auto;">
                    <table id="tabla-productos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-tabla">
                            <tr><td colspan="5" style="text-align: center;">Cargando productos...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="app.js"></script>
</body>
</html>
