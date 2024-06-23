<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la ruta de la imagen a eliminar
    $query = "SELECT imagen FROM peliculas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagen_path = $row['imagen'];

        // Eliminar el archivo de imagen si existe
        if (file_exists($imagen_path)) {
            unlink($imagen_path); // Eliminar el archivo
        }

        // Eliminar el registro de la base de datos
        $delete_query = "DELETE FROM peliculas WHERE id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            $_SESSION['message'] = 'Registro borrado exitosamente';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php');
        } else {
            echo "Error al borrar registro: " . $stmt_delete->error;
        }
    } else {
        echo "No se encontró la película.";
    }

    // Cerrar las conexiones y declaraciones
    $stmt->close();
    $stmt_delete->close();
} else {
    echo "ID no especificado.";
}

$conn->close();
?>
