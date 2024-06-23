<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todos los campos están llenos
    if (isset($_POST['id'], $_POST['titulo'], $_POST['genero'], $_POST['descripcion'])) {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $genero = $_POST['genero'];
        $descripcion = $_POST['descripcion'];

        $query = "SELECT imagen FROM peliculas WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Manejo de la subida de archivos si se proporciona una nueva imagen
        if (!empty($_FILES['image']['name'])) {

            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // Actualizar la película incluyendo la nueva imagen

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $currentImagePath = $row['imagen'];

                    if (file_exists($currentImagePath)) {
                        unlink($currentImagePath);
                    }
                }
                $query = "UPDATE peliculas SET titulo = ?, genero = ?, descripcion = ?, imagen = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssi", $titulo, $genero, $descripcion, $uploadFile, $id);

            } else {
                echo json_encode(array('error' => 'Error al subir la imagen.'));
                exit;
            }
        } else {
            // Actualizar la película sin cambiar la imagen
            $query = "UPDATE peliculas SET titulo = ?, genero = ?, descripcion = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $titulo, $genero, $descripcion, $id);
        }

        // Ejecutar la consulta preparada
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Película actualizada correctamente';
            $_SESSION['message_type'] = 'primary'; # Funcion de bootstrap
        } else {
            echo json_encode(array('error' => 'Error al actualizar la película.'));
        }

        $stmt->close();
    } else {
        echo json_encode(array('error' => 'Todos los campos son obligatorios.'));
    }
} else {
    echo json_encode(array('error' => 'Método de solicitud no válido.'));
}
?>
