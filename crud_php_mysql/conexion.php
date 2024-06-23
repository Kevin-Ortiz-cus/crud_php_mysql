<?php  
    session_start(); // Inicia una sesión o reanuda una existente
    $servername = "localhost";   // Localhost o IP
    $username = "dwes";          // Usuario de la DB
    $password = "dwes";          // Contraseña de la DB
    $database = "gestion_peliculas"; // Nombre de la DB

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Conexión no establecida: " . mysqli_connect_error());
    }
?>
