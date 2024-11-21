<?php
session_start();

$imagen_perfil = 'default.jpg'; // Imagen predeterminada en caso de que no haya usuario logueado

// Si el usuario ha iniciado sesión, obtener la imagen de perfil y el ID del usuario
if (isset($_SESSION['username'])) {
    $usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($usuarios as $usuario) {
        list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
        
        if ($username_existente == $_SESSION['username']) {
            $imagen_perfil = $imagen; // Usar la imagen del usuario logueado
            $user_id = $id; // Obtener el ID del usuario
            break;
        }
    }

    // Guardar el artículo en el archivo si se envían los datos
    if (isset($_POST['article_name']) && isset($_POST['article_price']) && isset($_POST['article_image'])) {
        $article_name = $_POST['article_name'];
        $article_price = $_POST['article_price'];
        $article_image = $_POST['article_image'];

        // Abrir o crear el archivo `articulos_usu.txt`
        $file_path = "articulos_usu.txt";
        $file = fopen($file_path, 'a'); // 'a' para añadir al archivo sin sobrescribir

        // Escribir los detalles del artículo en el archivo, junto con el ID del usuario
        fwrite($file, "ID Usuario: $user_id | Artículo: $article_name | Precio: $article_price | Imagen: $article_image\n");

        // Cerrar el archivo
        fclose($file);

        echo "El artículo ha sido guardado exitosamente en articulos_usu.txt.";
    }
} else {
    echo "Por favor, inicie sesión para añadir artículos al carrito.";
}
?>


