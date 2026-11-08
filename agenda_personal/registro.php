<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $password);

    if ($stmt->execute()) {
        $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
    } else {
        $mensaje = "Error: El correo ya está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Agenda</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        p { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registro de Usuario</h2>
        <?php if($mensaje != "") echo "<p>$mensaje</p>"; ?>
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            <label>Correo:</label>
            <input type="email" name="email" required>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Registrarse</button>
        </form>
        <p style="text-align:center; margin-top:15px;"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    </div>
</body>
</html>