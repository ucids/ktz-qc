<?php
// Conexión a la base de datos (ajusta los detalles según tu configuración)
$host = '192.168.0.194:5100';
$usuario = 'ucid';
$contraseña = '1974';
$base_de_datos = 'ktz';

$conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Comprobar si se recibió el ID del artículo a eliminar
if (isset($_POST['articuloId'])) {
    $articuloId = $_POST['articuloId'];

    // Eliminar el artículo de la base de datos
    $sql = "DELETE FROM articulos WHERE id = $articuloId";

    if ($conn->query($sql) === TRUE) {
        // La eliminación fue exitosa
        echo "El artículo se eliminó correctamente";
    } else {
        // Ocurrió un error al eliminar el artículo
        echo "Error al eliminar el artículo: " . $conn->error;
    }
} else {
    // No se recibió el ID del artículo a eliminar
    echo "ID del artículo no especificado";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
