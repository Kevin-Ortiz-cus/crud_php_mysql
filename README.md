# crud_php_mysql
- Una vez creado el primer registro e introducida la imagen, se creará automáticamente la carpeta 'uploads', donde se guardarán todas las imágenes automáticamente.
- Las imágenes se eliminarán si actualizamos un registro con una nueva imagen o si eliminamos un registro.

- Esta es la tabla que usaremos como ejemplo.
  CREATE TABLE `peliculas` (
    `id` int(11) NOT NULL,
    `titulo` text NOT NULL,
    `genero` text NOT NULL,
    `descripcion` text NOT NULL,
    `imagen` text NOT NULL
  )
