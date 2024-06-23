<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>PHP MsSQL CRUD</title>
    <!-- CDN de Bootstrap 4-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <!--Fontawesome-->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <!-- Función JavaScript -->
    <script>
        function validarform() {
            let titulo = document.forms["form"]["titulo"].value;
            let genero = document.forms["form"]["genero"].value;
            let descripcion = document.forms["form"]["descripcion"].value;
            if (titulo == "" || genero == "" || descripcion == "") {
                alert("Debes llenar todos los campos");
                return false;
            }
        }

        $(document).ready(function() {
    $('.btn-editar').on('click', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        editarPelicula(id);
    });

    // Función para cargar los detalles de la película en el formulario modal
    function editarPelicula(id) {
        $.ajax({
            url: 'get_movie_details.php',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                // Rellenar el formulario modal con los datos recibidos
                $('#editId').val(data.id);
                $('#editTitulo').val(data.titulo);
                $('#editGenero').val(data.genero);
                $('#editdescripcion').val(data.descripcion);
                $('#previewEdit').attr('src', data.imagen).show();
                // Mostrar el modal
                $('#editarModal').modal('show');
            },
            error: function() {
                alert('Error al cargar los datos de la película.');
            }
        });
    }

    // Manejar el envío del formulario de edición
    $('#formEditar').submit(function(event) {
        event.preventDefault();

        // Obtener los datos del formulario
        let formData = new FormData(this);

        // Enviar la solicitud AJAX para actualizar la película
        $.ajax({
            url: 'update.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error al actualizar la película.');
            }
        });
    });
});

    </script>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand">CRUD CON PHP & MYSQL</a>
            </div>
        </nav>
        <div class="container p-4">
            <?php 
                include("conexion.php");
                if(isset($_SESSION['message'])){
            ?>
                <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php 
                session_unset(); // Limpiar todas las variables de sesión
                } 
            ?>
            <div class="row">
                <div class="col-md-4 mx-auto">
                    <div class="card card-body">
                        <form method="post" name="form" enctype="multipart/form-data" onsubmit="return validarform()" action="create.php">
                            <div class="form-group">
                                <input type="text" name="titulo" class="form-control" placeholder="Ingresa titulo" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="genero" class="form-control" placeholder="Ingresa genero" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="descripcion" class="form-control" placeholder="Ingresa descripcion" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <input type="file" name="image" class="form-control" placeholder="Ingresa imagen" required>
                            </div>
                            <input type="submit" class="btn btn-success btn-block" name="send" value="Agregar">
                            <input type="reset" class="btn btn-danger btn-block" value="Limpiar">
                        </form>
                    </div>
                </div> <!--End col-md-4-->
                <div class="col-md-8 mx-auto">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titulo</th>
                                <th>Genero</th>
                                <th>descripcion</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT * FROM peliculas";
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_array($result)){ 
                            ?>
                                <tr>
                                    <td><?php echo $row['id'] ?></td>
                                    <td><?php echo $row['titulo'] ?></td>
                                    <td><?php echo $row['genero'] ?></td>
                                    <td><?php echo $row['descripcion'] ?></td>
                                    <td><img width="100" src="./<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>"></td>
                                    <td>
                                    <a href="#" class="btn btn-secondary btn-sm btn-editar" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                        <a href="delete.php?id=<?php echo $row['id']?>" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                } 
                            ?>
                        </tbody>
                    </table>
                </div> <!--End col-md-8-->
            </div> <!--End row-->
        </div><!--End container p-4-->
    </div><!--End container-->

    <!-- Modal para editar película -->
<div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Película</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editTitulo">Título</label>
                        <input type="text" class="form-control" id="editTitulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="editGenero">Género</label>
                        <input type="text" class="form-control" id="editGenero" name="genero" required>
                    </div>
                    <div class="form-group">
                        <label for="editdescripcion">Descripcion</label>
                        <input type="text" class="form-control" id="editdescripcion" name="descripcion" required>
                    </div>
                    <div class="form-group">
                        <label for="editImagen">Imagen</label>
                        <input type="file" class="form-control-file" id="editImagen" name="image">
                        <img id="previewEdit" src="" alt="Imagen actual" style="max-width: 100%; display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
