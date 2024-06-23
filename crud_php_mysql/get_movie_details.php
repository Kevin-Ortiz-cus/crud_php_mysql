<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM peliculas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Asegúrate de que la imagen tenga una URL válida
        if (!empty($row['imagen'])) {
            $row['imagen'] = $row['imagen'];
        }

        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Película no encontrada'));
    }
} else {
    echo json_encode(array('error' => 'ID no especificado'));
}
?>
