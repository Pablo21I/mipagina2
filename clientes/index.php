<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Registro de clientes</h1>
    <form action="guardar.php" method="post">
    <table>
        <tr>
            <th>Nombre</th>
            <td>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese nombre" title="Nombre">
            </td>
            <th>Domicilio</th>
            <td><input type="text" name="domicilio" id="domicilio" placeholder="Ingrese domicilio" title="Domicilio"></td>
            <th>Giro</th>
            <td>
                <input type="text" name="giro" id="giro" placeholder="Ingrese giro" title="Giro">
            </td>
            <th>Razon social</th>
            <td>
                <input type="text" name="razon_social" id="razon_social" placeholder="Ingrese razón social" title="Razón social">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="centered-cell">
            <input type="submit" value="Guardar cliente"></td>
        </tr> 
    </table>
    </form>
    <?php
        if (isset($_GET['mensaje'])) {
        echo "<p>" . urldecode($_GET['mensaje']) . "</p>";
    }
    ?>
</body>
</html>
