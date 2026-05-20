<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    
    if (empty($email) || empty($password)) {
        echo "<p style='color:red;'>Por favor, completa todos los campos.</p>";
        echo "<p><a href='login2.php'>Volver a login</a></p>";
    } else {
        $password_encriptada = md5($password);

        $stmt = $conexion->prepare("SELECT id, nombre, correo, es_admin FROM usuarios WHERE correo = ? AND contraseña = ?");
        
        if ($stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }
        
        $stmt->bind_param("ss", $email, $password_encriptada);
        $stmt->execute();
        $stmt->store_result(); // Almacenar el resultado para obtener el número de filas de forma compatible
        
        $stmt->bind_result($db_id, $db_nombre, $db_correo, $db_es_admin);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $_SESSION['usuario'] = $db_nombre;
            $_SESSION['usuario_id'] = $db_id;
            $_SESSION['usuario_nombre'] = $db_nombre;
            $_SESSION['usuario_email'] = $db_correo;
            $_SESSION['es_admin'] = $db_es_admin;
            
            $stmt->close();
            header("Location: dashboard.php");
            exit();
        } else {
            $stmt->close();
            echo "<p style='color:red;'>Correo electrónico o contraseña incorrectos.</p>";
            echo "<p><a href='login2.php'>Volver a login</a></p>";
        }
    }
} else {
    echo "<p style='color:orange;'>Acceso directo a verificación. Por favor usa el formulario de login.</p>";
    echo "<p><a href='login2.php'>Ir a login</a></p>";
}
?>