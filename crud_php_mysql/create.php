<?php
include ("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si todos los campos están llenos
    if (isset($_POST['titulo'], $_POST['genero'], $_POST['descripcion'], $_FILES['image'])) {
        $titulo = $_POST['titulo'];
        $genero = $_POST['genero'];
        $descripcion = $_POST['descripcion'];

        // Manejo de la subida de archivos
        $uploadDir = 'uploads/'; // Directorio donde se guardarán los archivos subidos
        
        // Verificar si el directorio existe, si no, crear el directorio
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Mover el archivo subido al directorio de destino
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // Preparar la consulta SQL para insertar los datos
                $query = "INSERT INTO peliculas (titulo, genero, descripcion, imagen) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                // Asignar valores a los parámetros
                $stmt->bind_param('ssss', $titulo, $genero, $descripcion, $uploadFile);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    echo "El archivo " . basename($_FILES['image']['name']) . " ha sido subido y la información se ha guardado en la base de datos.";
                } else {
                    echo "Error al guardar la información en la base de datos: " . $stmt->error;
                }

                // Cerrar la declaración
                $stmt->close();
                $_SESSION['message'] = 'Pelicula creada correctamente';
                $_SESSION['message_type'] = 'success'; # Funcion de bootstrap
                header("Location: index.php");
            } else {
                echo "Ocurrió un error al subir el archivo.";
            }
        } else {
            echo "El archivo no es una imagen.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
?>
