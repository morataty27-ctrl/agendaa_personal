<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
include 'conexion.php';

$usuario_id = $_SESSION['usuario_id'];

// Agregar contacto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['email'];
    $ciudad = $_POST['ciudad'];

    $sql = "INSERT INTO contactos (usuario_id, nombre, telefono, correo, ciudad) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $usuario_id, $nombre, $telefono, $correo, $ciudad);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

// Eliminar contacto
if (isset($_GET['eliminar'])) {
    $id_contacto = $_GET['eliminar'];
    $sql = "DELETE FROM contactos WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_contacto, $usuario_id);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

// Obtener contactos del usuario actual
$sql = "SELECT * FROM contactos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Agenda Personal</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        form { margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; }
        input { padding: 8px; box-sizing: border-box; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .btn-danger { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn-danger:hover { background: #c82333; }
        .btn-logout { background: #6c757d; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
            <a href="login.php" class="btn-logout">Cerrar Sesión</a>
        </div>

        <h3>Agregar Nuevo Contacto</h3>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="email" placeholder="Correo electrónico">
            <input type="text" name="ciudad" placeholder="Ciudad">
            <button type="submit">Guardar</button>
        </form>

        <h3>Mis Contactos</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
            <?php while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                <td><?php echo htmlspecialchars($row['ciudad']); ?></td>
                <td>
        <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn-editar" style="margin-right: 5px; color: blue;">Editar</a>
        <a href="index.php?eliminar=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('¿Estás seguro de eliminar este contacto?');">Eliminar</a>
    </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>