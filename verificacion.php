<?php
session_start();
include("lib/conn.php");

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
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['correo'];
            $_SESSION['es_admin'] = $usuario['es_admin'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Correo electrónico o contraseña incorrectos.</p>";
            echo "<p><a href='login2.php'>Volver a login</a></p>";
        }
        
        $stmt->close();
    }
} else {
    echo "<p style='color:orange;'>Acceso directo a verificación. Por favor usa el formulario de login.</p>";
    echo "<p><a href='login2.php'>Ir a login</a></p>";
}
?>