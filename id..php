<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nac = $_POST['fecha_nac'];
    $sexo = $_POST['sexo'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si se ha subido una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = $_FILES['imagen']['name'];
        $imagen_destino = 'imagenes/' . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_destino);
    } else {
        $imagen = 'default.png'; // Si no se selecciona imagen, usar una por defecto
    }

    // Leer el archivo de usuarios
    $usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);

    // Inicializar el ID en 1 y buscar el siguiente ID disponible
    $id = 1;
    if (!empty($usuarios)) {
        $ultimo_usuario = end($usuarios);
        $ultimo_id = (int)explode('|', $ultimo_usuario)[0]; // Obtener el ID del último usuario registrado
        $id = $ultimo_id + 1; // Incrementar el ID
    }

    // Verificar si el usuario o el correo ya existen
    foreach ($usuarios as $usuario) {
        list(, , , , $correo_existente, , $usuario_existente) = explode('|', $usuario);
        if ($usuario_existente == $username) {
            echo "<script>alert('Error: Usuario ya registrado.'); window.history.back();</script>";
            exit;
        }
        if ($correo_existente == $correo) {
            echo "<script>alert('Error: Correo ya registrado.'); window.history.back();</script>";
            exit;
        }
    }

    // Guardar los datos del nuevo usuario
    $registro = "$id|$nombre|$apellido|$fecha_nac|$sexo|$correo|$telefono|$username|$password|$imagen\n";
    file_put_contents('usuarios.txt', $registro, FILE_APPEND);

    // Redirigir al index después del registro
    header('Location: Tienda de Mueble.html');
    exit;
}
?>
