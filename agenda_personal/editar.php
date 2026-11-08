<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Si se recibe el ID por URL
if (isset($_GET['id'])) {
    $id_contacto = $_GET['id'];

    // Consultar el contacto asegurando que pertenezca al usuario actual (Seguridad)
    $stmt = $conn->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id_contacto, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $contacto = $resultado->fetch_assoc();
    } else {
        header("Location: index.php");
        exit();
    }
}

// Si se envía el formulario para actualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_contacto = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $ciudad = $_POST['ciudad'];

    $update = $conn->prepare("UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, ciudad = ? WHERE id = ? AND usuario_id = ?");
    $update->bind_param("ssssii", $nombre, $telefono, $correo, $ciudad, $id_contacto, $id_usuario);
    
    if ($update->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error al actualizar el contacto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { color: #555; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Contacto</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $contacto['id']; ?>">
            
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($contacto['nombre']); ?>" required>
            
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($contacto['telefono']); ?>" required>
            
            <label>Correo electrónico:</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($contacto['correo']); ?>">
            
            <label>Ciudad:</label>
            <input type="text" name="ciudad" value="<?php echo htmlspecialchars($contacto['ciudad']); ?>">
            
            <button type="submit">Actualizar Contacto</button>
            <a href="index.php">Cancelar</a>
        </form>
    </div>
</body>
</html>