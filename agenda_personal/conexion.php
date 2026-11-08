<?php
$servidor = "127.0.0.1";
$usuario = "root";
$password = "2727";
$base_datos = "agenda_personal";
$puerto = 3307;

$conn = new mysqli($servidor, $usuario, $password, $base_datos, $puerto);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>