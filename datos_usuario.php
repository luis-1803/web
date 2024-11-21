<?php
session_start();

// Configuración inicial
$archivo_usuarios = 'usuarios.txt';
$archivo_articulos = 'articulos_usu.txt';
$imagen_perfil = 'default.jpg'; // Imagen predeterminada
$mensaje = ''; // Mensaje de estado para el usuario
$color_clase = ''; // Clase CSS para el mensaje

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $usuarios = file($archivo_usuarios, FILE_IGNORE_NEW_LINES);

    foreach ($usuarios as $usuario) {
        list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);

        if ($username_existente === $_SESSION['username']) {
            $imagen_perfil = $imagen; // Imagen del usuario logueado
            $_SESSION['id_usuario'] = $id; // Guardar ID del usuario en la sesión
            $_SESSION['correo'] = $correo; // Guardar correo para posibles acciones futuras
            break;
        }
    }

    $mensaje = 'Ya puedes acceder a tu perfil y añadir artículos al carrito.';
    $color_clase = 'mensaje-verde'; // Mensaje para usuarios logueados
} else {
    $mensaje = 'Inicia sesión para acceder a tu perfil y añadir artículos al carrito.';
    $color_clase = 'mensaje-rojo'; // Mensaje para usuarios no logueados
}

// Manejo de peticiones POST para el carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_usuario) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para gestionar el carrito.']);
        exit;
    }

    if ($accion === 'añadir') {
        $nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $imagen = $_POST['imagen'] ?? '';
        $identificador = uniqid(); // Generar un identificador único

        // Añadir artículo al archivo
        $linea = "$id_usuario|$nombre|$precio|$imagen|$identificador\n";
        file_put_contents($archivo_articulos, $linea, FILE_APPEND);
        echo json_encode(['success' => true, 'message' => 'Artículo añadido al carrito.', 'id' => $identificador]);
    } elseif ($accion === 'eliminar') {
        $identificador = $_POST['id'] ?? '';

        // Eliminar artículo del archivo
        $contenido = file($archivo_articulos, FILE_IGNORE_NEW_LINES);
        $contenido_actualizado = array_filter($contenido, function ($linea) use ($identificador) {
            return strpos($linea, $identificador) === false;
        });
        file_put_contents($archivo_articulos, implode("\n", $contenido_actualizado) . "\n");
        echo json_encode(['success' => true, 'message' => 'Artículo eliminado del carrito.']);
    }
    exit;
}

// Obtener artículos del carrito para mostrar
$carrito = [];
$total = 0;

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    if (file_exists($archivo_articulos)) {
        $articulos = file($archivo_articulos, FILE_IGNORE_NEW_LINES);
        $carrito = array_filter($articulos, function ($linea) use ($id_usuario) {
            return strpos($linea, "$id_usuario|") === 0; // Reemplazo de str_starts_with
        });

        // Calcular el total
        foreach ($carrito as $articulo) {
            list($id, $nombre, $precio, $imagen, $identificador) = explode('|', $articulo);
            $total += (float)$precio;
        }
    }
}
?>
<?php


// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Leer el archivo usuarios.txt para obtener los datos del usuario
$usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
$usuario_actual = null;

foreach ($usuarios as $index => $usuario) {
    list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
    
    if ($username_existente == $_SESSION['username']) {
        $usuario_actual = [
            'index' => $index,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'fecha_nac' => $fecha_nac,
            'sexo' => $sexo,
            'correo' => $correo,
            'telefono' => $telefono,
            'username' => $username_existente,
            'imagen' => $imagen
        ];
        break;
    }
}

// Si no se encuentra el usuario actual, redirigir
if ($usuario_actual === null) {
    echo "Error: Usuario no encontrado.";
    exit;
}

// Procesar cambio de imagen de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == 0) {
        // Validar que el archivo es una imagen
        $file_type = mime_content_type($_FILES['nueva_imagen']['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (in_array($file_type, $allowed_types)) {
            // Guardar la nueva imagen
            $nueva_imagen = 'imagenes/' . basename($_FILES['nueva_imagen']['name']);
            move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $nueva_imagen);

            // Actualizar el archivo usuarios.txt con la nueva imagen
            $usuario_actual['imagen'] = $_FILES['nueva_imagen']['name'];
            $usuarios[$usuario_actual['index']] = implode('|', array($usuario_actual['id'], $usuario_actual['nombre'], $usuario_actual['apellido'], $usuario_actual['fecha_nac'], $usuario_actual['sexo'], $usuario_actual['correo'], $usuario_actual['telefono'], $usuario_actual['username'], $password, $usuario_actual['imagen']));

            file_put_contents('usuarios.txt', implode("\n", $usuarios));
            
            echo "<script>alert('Imagen de perfil actualizada con éxito.'); window.history.back();</script>";
        } else {
            echo "Error: Solo se permiten archivos de tipo imagen (JPG, PNG, GIF).";
        }
    } else {
        echo "Error al subir la nueva imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>Muebles en Venta</title>
    <style>
        /* Estilos generales del cuerpo */
body {
    font-family: Georgia, sans-serif;
    margin: 0px;
    padding: 0;
    color: black;
    background: #eae9e6;
    box-shadow: 0 0 15px rgb(139, 69, 19);
}

/* Estilo de texto general */
.text {
    margin: 0;
    padding: 0;
    font-family: Georgia, serif;
    color: black;
    min-height: 100%;
    position: relative;
    font-size: 2.0em;
    letter-spacing: 3px;
    line-height: 1;
    text-align: center;

}

/* Header (Encabezado) */
.header {
    background-color: #5a523a;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .logo {
    height: 80px;
}

.header nav {
    display: flex;
}

.header nav a {
    color: #fff;
    margin: 0 15px;
    text-decoration: none;
    position: relative;
}

.header nav a:hover {
    text-decoration: underline;
}

/* Dropdown del Header */
.header nav .dropdown {
    position: relative;
    display: inline-block;
}

.header nav .dropdown-content {
    display: none;
    position: absolute;
    background-color: #5a523a;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    top: 50px;
}

.header nav .dropdown-content a {
    color: white;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    
}

.header nav .dropdown-content a:hover {
    background-color: #555;
}

.header nav .dropdown:hover .dropdown-content {
    display: block;
}

/* Sección de bienvenida */
.welcome-section {
    background-color: #ffffff;
    padding: 40px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.welcome-section h2 {
    color: #746844;
    margin-bottom: 20px;
}

.welcome-section p {
    line-height: 1.6;
    margin-bottom: 20px;
}

/* Botón de llamada a la acción */
.cta-button {
    background-color: #746844;
    color: #fff;
    padding: 15px 30px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 18px;
    display: inline-block;
}

.cta-button:hover {
    background-color: #5a523a;
}

/* Footer */
.footer, footer {
    background-color: #5a523a;
    color: #fff;
    text-align: center;
    padding: 20px 0;
}

/* Información de contacto en el Footer */
.contact-info {
    font-size: 14px;
}

.contact-columns {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.contact-column {
    flex: 1;
    padding: 10px;
}

.contact-column ul {
    list-style-type: none;
    padding: 0;
}

.contact-column ul li {
    margin-bottom: 10px;
}

/* Estilos para artículos */
/* Contenedor principal para los artículos */
.container {
    display: flex; /* Usamos Flexbox */
    flex-wrap: wrap; /* Permitir que los elementos pasen a nuevas filas */
    gap: 20px; /* Espaciado entre los artículos */
    justify-content: center; /* Centra los elementos horizontalmente */
    padding: 20px;
}

/* Artículos individuales (2x2 por defecto en pantallas grandes) */
.article {
    flex: 1 1 calc(50% - 20px); /* Cada artículo ocupa el 50% del contenedor */
    background-color: #ffffff;
    border: 1px solid transparent;
    box-shadow: 0 0 15px rgb(139, 69, 19); /* Sombra */
    border-radius: 5px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transición suave */
    margin-bottom: 20px;
  position: relative; /* Necesario para que el botón se posicione dentro del contenedor */
    padding-bottom: 50px; 
    text-align: center;
           

}
button {
    background: #733D18;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    transition: 0.3s;
    margin-top:10px;
    align-self: center;
    
}

.article:hover {
    transform: translate(5px, -5px);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.article button {
    margin-top: 15px; /* Espaciado entre el contenido y el botón */
    align-self: center; /* Centra el botón horizontalmente */
    padding: 10px 20px;
    background-color: #5a523a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
     position: absolute;
    bottom: 0; /* Posiciona el botón en la parte inferior */
    left: 50%; /* Centra el botón horizontalmente */
    transform: translate(-50%,-10px);
    width:70%;


}
/* Ajuste para pantallas más grandes (más columnas) */
@media screen and (min-width: 1024px) {
    .article {
        flex: 1 1 calc(25% - 20px); /* En pantallas más grandes, ocupan el 25% (4x4) */
    }
}

/* Ajuste para pantallas más pequeñas (dispositivos móviles) */
@media screen and (max-width: 768px) {
    .article {
        flex: 1 1 calc(100% - 20px); /* Cada artículo ocupa todo el ancho */
    }
}

/* Imágenes dentro de los artículos */
.article img {
    height: auto;
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 100%; /* Ajusta el tamaño de las imágenes */
}

.article-title{
font-size:22px;
font-weight: bold;
margin-bottom:16px ;

}
/* Contenido del artículo */
.article-content {
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Distribuye los elementos uniformemente */
    padding: 15px;
    height: 100%; /* Para asegurarse de que ocupe toda la altura del contenedor */
}

.article-description {
    font-size: 18px;
    color: #666666;
    overflow: hidden; /* Oculta el exceso de texto */
    text-overflow: ellipsis;
    flex-grow: 1;
    max-height: 150px; /* Permite mostrar más texto sin desbordar */
}

.article-price {
    font-size: 18px;
    font-weight: bold;
    color: #5D3215;
    margin-top: 10px; /* Empuja el precio hacia la parte inferior */
    margin-bottom: 20px;
}

.add-to-cart-btn {
    margin-top: 10px; /* Espacio entre el precio y el botón */
    align-self: center; /* Centra el botón horizontalmente */
}

/* Estilos del carrito de compra */
.cart {
    position: fixed;
    top: 0;
    right: -300px;
    background-color: #f4f4f4;
    padding: 20px;
    width: 300px;
    height: auto;
    max-height: 100%;
    overflow-y: auto;
    transition: transform 0.3s ease-in-out, max-height 0.3s ease-in-out;
}

.cart.show {
    transform: translateX(-100%);
}

.cart-count {
    position: absolute;
    top: -10px;
    
    background-color: red;
    color: #fff;
    border-radius: 50%;
    padding: 5px 8px;
    font-size: 14px;
}

.cart ul {
    list-style-type: none;
    padding: 0;
}

.cart li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.cart-item img {
    width: 50px;
    height: 50px;
    margin-right: 10px;
}

.btn-remove {
    background-color: #ff0000;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

/* Otros estilos */


button:hover {
    background: #fff;
    color: #733D18;
    box-shadow: 0 0 15px rgb(139, 69, 19);
}

a {
    text-decoration: none;
    color: black;
}

.barra a {
    display: block;
    text-align: center;
    padding: 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    margin-top: 20px;
}

/* Perfil del usuario */
.profile-btn {
    width: 100%;
    max-width: 200px;
   
    position: relative;
    margin-right: 10px;
    text-align: center;
}

.profile-btn img {
    background-color: white;
    cursor: pointer;
    border: 3px solid #000000;
    transition: border-color 0.3s ease;
    justify-content: center;
    width: 49px;
    height: 49px;
    border-radius: 50%;
    object-fit: contain;
    margin-top: 10px;
    margin-left: 10px;
}

/* Menú desplegable debajo de la imagen de perfil */
.dropdown-menu {
    display: none;
    position: absolute;
    top: 99%;
    right: 0;
    background-color: white;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    min-width: 150px;
    padding: 10px;
    z-index: 1;
    border: 2px solid #755405;
}

.dropdown-menu a {
    padding: 10px 20px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-block;
    margin: 6px;
    background-color: #8B4513;
    color: white;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
    cursor: pointer;
    box-shadow: 0px 0px 10px rgba(139, 69, 19, 0.5);
}

.dropdown-menu a:hover {
    background-color: #A0522D;
    transform: translateY(-5px);
}

.show {
    display: block;
}

.cart-icon {
   
    display: inline-block;
    margin-right: 30px;
}

.cart-icon img {
    width: 48px;
    height: 48px;
    cursor: pointer;
}

/* Barra lateral */


.icons {
    display: flex;
    flex-direction: column; /* Alinear en vertical */
    align-items: center;
    margin-top: 20px;
}

.icons img {
    width: 40px;
    margin-bottom: 10px;
}

.back-button {
    margin-top: auto; /* Mover al final */
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #402d20;
    color: white;
    border: none;
    cursor: pointer;
}
/* Barra lateral oculta por defecto */

.icons {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}

.icons img {
    width: 40px;
    margin-bottom: 10px;
}

/* Mostrar la barra lateral y ocultar el menú superior para pantallas pequeñas */




/* Hide logo button on larger screens */
.logo-button {
    display: none;
}

@media screen and (max-width: 768px) {
    .logo-button {
        display: inline;
    }
}

/* Center product text */
.article-title, .article-description {
    text-align: center;
}

/* Cart toggle adjustments */
.cart {
    display: none;
}

.cart.show {
    display: block;
}
body {
    font-family: Georgia, sans-serif;
    margin: 0px;
    padding: 0;
    color: black;
    background: #eae9e6;
    box-shadow: 0 0 15px rgb(139, 69, 19);
}

/* Header (Encabezado) */
.header {
    background-color: #5a523a;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .logo {
    height: 80px;
}

.header nav {
    display: flex;
}

.header nav a {
    color: #fff;
    margin: 0 15px;
    text-decoration: none;
    position: relative;
}

.header nav a:hover {
    text-decoration: underline;
}

/* Barra lateral */

.icons {
    display: flex;
    flex-direction: row; /* Colocar las imágenes en fila */
    justify-content: center;
    align-items: center;
    gap: 10px; /* Espacio entre las imágenes */
    margin-top: 20px;
}

.icons img {
    width: 40px;
    margin-bottom: 10px;
}


.back-button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #402d20;
    color: white;
    border: none;
    cursor: pointer;
}

/* Botones de control */
.logo-button {
    display: none; /* Ocultar por defecto */
}

@media screen and (max-width: 768px) {
    .header nav {
        display: none;
    }
    .logo-button {
        display: inline;
        height: 80px;

    }
}

.dropdown-content {
    display: none;
}
.dropdown-content.show {
    display: block;
}

.bajo a{
 top:20px;
 font-size: 25px;
}







.sidebar {
    position: fixed;
    top: 0;
    left: -350px;
    width: 300px;
    height: 100%;
    background-color: #5a523a;
    
    
    z-index: 1000;
    transition: left 0.3s ease-in-out;
    overflow-y: auto;
}

.sidebar.show {
    left: 0;
}

.close-sidebar {
    font-size: 44px;
    color: white;
    cursor: pointer;
    position: absolute;
    top: -1px;
    right: 10px;
    margin-bottom:10px;
}

/* Iconos del carrito y usuario */
.sidebar-icons {
    display: flex;
    gap: 60px;

    
}

#mobile-cart-icon,
#mobile-user-icon {
    cursor: pointer;
    font-size: 18px;
    padding: 10px;
    
    border-radius: 5px;
    text-align: center;
}

/* Contenido del carrito */
.mobile-cart,
.mobile-user-menu {
    display: none;
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.mobile-cart.show,
.mobile-user-menu.show {
    display: block;
}

.btn-close {
    background-color: #5a523a;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    margin-top: 10px;
    border-radius: 5px;
}

.mobile-user-menu a {
    display: block;
    color: #5a523a;
    padding: 10px;
    text-decoration: none;
    border-bottom: 1px solid #ddd;
}

.mobile-user-menu a:hover {
    background-color: #eaeaea;
}


@media screen and (max-width: 768px) {
    .mobile-bar {
         margin-top: 40px;
        width: 100%;
        background-color: #5a523a;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        z-index: 1001;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
    }

    .mobile-bar-icon {
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    /* Carrito móvil */
    .mobile-cart {
        
        background-color: #f4f4f4;
        padding: 20px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.3);
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1002;
        overflow-y: auto;
    }

    .mobile-cart.show {
        transform: translateY(0);
    }

    /* Menú de usuario móvil */
    .mobile-user-menu {
       
       
        height: auto;
        background-color: #fff;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.3);
        padding: 20px;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1002;
    }

    .mobile-user-menu.show {
        transform: translateY(0);
    }

    .mobile-user-menu a {
        display: block;
        color: #5a523a;
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #5a523a;
    }

    .profile-btn {
    width: 100%;
    max-width: 50px;
   
    position: relative;
    margin-right: 10px;
    text-align: center;
}

.profile-btn img {
    background-color: white;
    cursor: pointer;
    border: 3px solid #000000;
    transition: border-color 0.3s ease;
    justify-content: center;
    width: 59px;
    height: 59px;
    border-radius: 50%;
    object-fit: contain;
    margin-top: 10px;
    margin-left: 10px;
}
.cart-count {
    position: absolute;
    top: -2px;
    left: 67px;
    background-color: red;
    color: #fff;
    border-radius: 50%;
    padding: 5px 8px;
    font-size: 14px;
}
}




/* Estilo de la barra lateral */
.sidebar-nav {
    background-color: #5b4e3a; /* Color café oscuro */
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

/* Estilo del encabezado */
.bajo h2 {
    color: #fff;
    margin-bottom: 15px;
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1px solid #fff;
    padding-bottom: 10px;
    cursor: pointer;
}


/* Responsivo para móviles */
@media (max-width: 600px) {
    .sidebar-nav {
       
        padding: 10px;
    }

    #dropdown-content li a {
        font-size: 16px;
    }
.dropdown-content {
    display: none; /* Ocultar todos los menús por defecto */
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #5b4e3a;
    border: 1px solid #fff;
    border-radius: 5px;
}

.dropdown-content li a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px;
    text-align: center;
}

.dropdown-content li a:hover {
    background-color: #4a4033;
    font-weight: bold;
}

}






img.responsive {
    width: 30%; /* Imagen ocupará el 30% del ancho del contenedor */
    max-width: 100%; /* Asegura que no exceda su tamaño original */
    height: auto; /* Mantiene las proporciones */
}

/* Tamaño para dispositivos más pequeños (por ejemplo, móviles) */
@media screen and (max-width: 768px) {
    img.responsive {
        width: 80%; /* Imagen ocupará el 80% del ancho del contenedor */
    }
}






/** Tamaño predeterminado para pantallas grandes (por ejemplo, PC) 
.container-responsive {
    width: 90%;  El contenedor ocupará el 60% del ancho del viewport 
    max-width: 1200px;  Opcional: Limita el tamaño máximo 
    margin: 0 auto;  Centra el contenedor horizontalmente 
    padding: 20px;  Espaciado interno 
    background-color: #f8f9fa; Color de fondo para visualizarlo mejor 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); Sombra opcional 
}

 Tamaño para dispositivos más pequeños (por ejemplo, móviles o tablets) 
@media screen and (max-width: 768px) {
    .container-responsive {
        width: 90%; En pantallas pequeñas, el contenedor ocupa el 90% del ancho 
        padding: 10px;  Reduce el espaciado interno 
    }
}

**/

.mensaje-alerta {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border: 1px solid;
            border-radius: 5px;
            z-index: 1000;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        /* Estilo para mensaje verde (sesión iniciada) */
        .mensaje-verde {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        /* Estilo para mensaje rojo (sin sesión) */
        .mensaje-rojo {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }


  .btn-confirm-payment {
            width: 100%;
            padding: 12px;
            background-color: #5a523a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .btn-confirm-payment:hover {
            background-color: #746844;
        }

        /* Modal de confirmación de pago */
        .modal-payment {
            display: none; /* Oculto por defecto */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro */
        }

        .modal-payment-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-payment-header {
            color: #5a523a;
            text-align: center;
        }

        .modal-payment-label {
            font-weight: bold;
            color: #5a523a;
        }

        .modal-payment-input {
            width: 94%;
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
        }

        .btn-close-modal {
            color: #5a523a;
            float: right;
            font-size: 24px;
            cursor: pointer;
        }




         /* Estilos para el contenedor principal */
        .user-container {
            background-color: #ffffff; /* Color blanco */
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0px 0px 20px rgba(102, 51, 0, 0.5); /* Sombra con efecto café */
            text-align: center;
            width: 350px;
            margin: auto; /* Centra el contenedor */
            margin-top: 30px; /* Evitar que se superponga con el menú */
            border: 4px solid #755405;

    animation: neon-glow 2s infinite alternate;
    margin-bottom: 20px;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #d8c3a5;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #8c5a3c;
        }
        /* Botones y efectos */
        .cta-buttons button {
            background-color: #8c5a3c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .cta-buttons button:hover {
            background-color: #6f432a;
        }

        /* Estilos para la información del usuario */
        .user-info {
            text-align: center;
        }

        .user-info img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: contain;
            border: 3px solid #000000;
        }

        .user-info p {
            font-size: 18px;
            color: #333;
        }

        .user-info span {
            font-weight: bold;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link button {
            background-color: #8B4513; /* Color café */
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            padding: 15px 30px;
            cursor: pointer;
            box-shadow: 0px 0px 10px rgba(139, 69, 19, 0.5); /* Sombra café */
            margin: 5px;
        }

        .logout-link button:hover {
            background-color: #A0522D; /* Color café más oscuro al hacer hover */
        }
           .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #d8c3a5;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #8c5a3c;
        }
        .product {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #f5f5dc;
            border-radius: 8px;
            padding: 20px;
        }
        .product img {
            max-width: 100%;
            border-radius: 8px;
        }
        .product-info {
            flex: 1;
            margin-left: 20px;
        }
        .product-info h2 {
            margin-top: 0;
            color: #8c5a3c;
        }
        .product-info p, .product-info ul {
            margin: 5px 0;
            color: #5a3e30;
        }
        .product-info .price {
            color: #e74c3c;
            font-size: 1.5em;
            margin-top: 10px;
        }
        .cta-buttons {
            margin-top: 20px;
        }
        .cta-buttons button {
            background-color: #8c5a3c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .cta-buttons button:hover {
            background-color: #6f432a;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            text-decoration: none;
        }

        .logout-link button {
            display: inline-block;
            margin: 5px;
            padding: 15px 30px;
            background-color: #8B4513; /* Color café */
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Efecto de movimiento */
            cursor: pointer;
            box-shadow: 0px 0px 10px rgba(139, 69, 19, 0.5); /* Sombra café */
        }

        .logout-link button:hover {
            background-color: #A0522D; /* Color café más oscuro al hacer hover */
            transform: translateY(-5px); /* Efecto de movimiento hacia arriba */
        }

        .user-container form button[type=submit],
        

        .user-container form button[type=submit]:hover,
        .logout-link button:hover {
            background-color: #A0522D; /* Color café más oscuro al hacer hover */
            transform: translateY(-5px); /* Efecto de movimiento hacia arriba */
        }
        
        .user-info span {
            font-weight: bold;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            text-decoration: none;
        }

        .logout-link button {
            display: inline-block;
            margin: 5px;
            padding: 15px 30px;
            background-color: #8B4513; /* Color café */
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Efecto de movimiento */
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0px 0px 10px rgba(139, 69, 19, 0.5); /* Sombra café */
        }
        
    </style>
</style>
</head>
<body>
    <header class="header">
        <img class="logo" src="logo3.png" alt="Logo de la Tienda"  onclick="window.location.href='Tienda de Mueble.html'">

<div id="mensaje-alerta" class="mensaje-alerta <?= htmlspecialchars($color_clase) ?>">
        <?= htmlspecialchars($mensaje) ?>
    </div>


<div class="text"> 
<a href="Tienda de Mueble.html"><p class="georgia">Vieux Bois</p></a>      
</div>

 <img src="logo3.png" alt="Logo" class="logo-button" onclick="toggleSidebar()">

<nav>
            <div class="dropdown">
                 <div class="bajo">
                <a href="#salas">Salas</a>
                </div>
                <div class="dropdown-content">
                    <a href="sofas.html">Sofás</a>
                    <a href="sillones.html">Sillones</a>
                    <a href="mesasc.html">Mesas de Centro</a>
                </div>
            </div>
            <div class="dropdown">
                 <div class="bajo">
                <a href="#comedores">Comedores</a>
                </div>
                <div class="dropdown-content">
                    <a href="mesas.html">Mesas</a>
                    <a href="sillas.html">Sillas</a>
                    <a href="bufferts.html">Buffets</a>
                </div>
            </div>
            <div class="dropdown">
                 <div class="bajo">
                <a href="#recamaras">Recámaras</a>
                </div>
                <div class="dropdown-content">
                    <a href="camas.html">Camas</a>
                    <a href="buros.html">Burós</a>
                    <a href="armarios.html">Armarios</a>
                </div>
            </div>
            <div class="dropdown">
                 <div class="bajo">
                <a href="#cocinas">Cocinas</a>
                </div>
                <div class="dropdown-content">
                    <a href="mueblesc.html">Muebles de Cocina</a>
                    <a href="mcocina.html">Mesas de Cocina</a>
                    <a href="sillasc.html">Sillas de Cocina</a>
                </div>
            </div>
             <div class="dropdown">
                 <div class="bajo">
                <a href="#juveniles">Juveniles</a>
                </div>
                <div class="dropdown-content">
                    <a href="jcamas.html">Camas</a>
                    <a href="jescritorio.html">Escritorios</a>
                    <a href="jsillas.html">Sillas</a>
                </div>
            </div>
            <div class="profile-btn">
            <div class="cart-icon" id="cart-icon">
            <img src="carrito2.png" alt=" Compras" id="cart-toggle-btn">
            <span class="cart-count" id="cart-count">0</span>
        </div>
         </div>
             <div class="profile-btn">
           <img src="imagenes/<?php echo htmlspecialchars($imagen_perfil); ?>" alt="Imagen de perfil" id="profile-img">
            <div class="dropdown-menu" id="dropdown-menu">
    <?php if (isset($_SESSION['username'])): ?>
        <!-- Si el usuario ha iniciado sesión, mostrar "Perfil" -->
        <a href="datos_usuario.php"><i class="fas fa-user"></i> Perfil</a>
        <a href="cerrar_sesion.php" style="margin-top: 5px;"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    <?php else: ?>

        <!-- Si el usuario no ha iniciado sesión, mostrar "Iniciar sesión" -->
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
        <a href="register.php" style="margin-top: 5px;"><i class="fas fa-user-plus"></i> Registrarse</a>
    <?php endif; ?>
</div>        </div>


          
        </nav>
    </header>

 <div class="sidebar" id="sidebar">
        <div class="close-sidebar" onclick="toggleSidebar()">&times;</div>
<div class="mobile-bar">
        <!-- Iconos del carrito y del usuario dentro de la barra lateral -->
        <div class="sidebar-icons">
            <div id="mobile-cart-icon" class="profile-btn" class="cart-icon" id="cart-icon"><img src="carrito2.png"><span class="cart-count" id="cart-count">0</span></div>
            <div id="mobile-user-icon" class="profile-btn"><img src="imagenes/<?php echo htmlspecialchars($imagen_perfil); ?>" alt="Imagen de perfil" id="profile-img"> </div>
        </div>
</div>
        <!-- Contenido del carrito -->
        <div class="mobile-cart" id="mobile-cart" class="cart" id="cart">
            <h2>Carrito de Compras</h2>
            <ul id="mobile-cart-items"></ul>
            <p id="mobile-total-price">Total: $0</p>
            <button class="btn-close" onclick="toggleMobileCart()">Cerrar</button>

        </div>

        <!-- Menú de usuario -->
        <div class="mobile-user-menu" id="mobile-user-menu">
            <?php if (isset($_SESSION['username'])): ?>
        <!-- Si el usuario ha iniciado sesión, mostrar "Perfil" -->
        <a href="datos_usuario.php"><i class="fas fa-user"></i> Perfil</a>
        <a href="cerrar_sesion.php" style="margin-top: 5px;"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    <?php else: ?>
        <!-- Si el usuario no ha iniciado sesión, mostrar "Iniciar sesión" -->
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
        <a href="register.php" style="margin-top: 5px;"><i class="fas fa-user-plus"></i> Registrarse</a>
    <?php endif; ?>
            <button class="btn-close" onclick="toggleMobileUserMenu()">Cerrar</button>
        </div>

<nav class="sidebar-nav">

    <div class="dropdown">
        <div class="bajo" onclick="toggleDropdown('dropdown-content-salas')">
            <h2>Salas</h2>
        </div>
        <ul id="dropdown-content-salas" class="dropdown-content">
            <li><a href="sofas.html">Sofás</a></li>
            <li><a href="sillones.html">Sillones</a></li>
            <li><a href="mesasc.html">Mesas de Centro</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <div class="bajo" onclick="toggleDropdown('dropdown-content-comedores')">
            <h2>Comedores</h2>
        </div>
        <ul id="dropdown-content-comedores" class="dropdown-content">
            <li><a href="mesas.html">Mesas</a></li>
            <li><a href="sillas.html">Sillas</a></li>
            <li><a href="bufferts.html">Buffets</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <div class="bajo" onclick="toggleDropdown('dropdown-content-recamaras')">
            <h2>Recámaras</h2>
        </div>
        <ul id="dropdown-content-recamaras" class="dropdown-content">
            <li><a href="camas.html">Camas</a></li>
            <li><a href="buros.html">Burós</a></li>
            <li><a href="armarios.html">Armarios</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <div class="bajo" onclick="toggleDropdown('dropdown-content-cocinas')">
            <h2>Cocinas</h2>
        </div>
        <ul id="dropdown-content-cocinas" class="dropdown-content">
            <li><a href="mueblesc.html">Muebles de Cocina</a></li>
            <li><a href="mcocina.html">Mesas de Cocina</a></li>
            <li><a href="sillasc.html">Sillas de Cocina</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <div class="bajo" onclick="toggleDropdown('dropdown-content-juveniles')">
            <h2>Juveniles</h2>
        </div>
        <ul id="dropdown-content-juveniles" class="dropdown-content">
            <li><a href="jcamas.html">Camas</a></li>
            <li><a href="jescritorio.html">Escritorios</a></li>
            <li><a href="jsillas.html">Sillas</a></li>
        </ul>
    </div>

</nav>

    </div>


    <div class="user-container">
        <h2>Información del Usuario</h2>
        <div class="user-info">
            <img src="imagenes/<?php echo htmlspecialchars($usuario_actual['imagen']); ?>" alt="Imagen de perfil">
            <p><span>Nombre:</span> <?php echo htmlspecialchars($usuario_actual['nombre']); ?></p>
            <p><span>Apellido:</span> <?php echo htmlspecialchars($usuario_actual['apellido']); ?></p>
            <p><span>Fecha de Nacimiento:</span> <?php echo htmlspecialchars($usuario_actual['fecha_nac']); ?></p>
            <p><span>Sexo:</span> <?php echo htmlspecialchars($usuario_actual['sexo']); ?></p>
            <p><span>Correo:</span> <?php echo htmlspecialchars($usuario_actual['correo']); ?></p>
            <p><span>Teléfono:</span> <?php echo htmlspecialchars($usuario_actual['telefono']); ?></p>
            <p><span>Nombre de usuario:</span> <?php echo htmlspecialchars($usuario_actual['username']); ?></p>
        </div>

        
<form action="datos_usuario.php" method="post" enctype="multipart/form-data" style="position: relative; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(139,69,19, 0.7);">
    <label for="nueva_imagen"><span style="color: #000000;"><P><b>Cambiar imagen de perfil:</b></P></span></label>
    <input type="file" name="nueva_imagen" id="nueva_imagen" accept="image/*" required>
    <button type="submit" style="background-color: #8B4513; color: white; border: none; padding: 15px 20px; border-radius: 50px; cursor: pointer; position: relative; transition: transform 0.3s ease-in-out;  margin-top: 20px;">
        Actualizar Imagen
    </button>
</form>

<script>
    // Efecto hover para movimiento y brillo neon en el botón
    const button = document.querySelector('button[type="submit"]');
    button.addEventListener('mouseover', () => {
        button.style.transform = 'scale(1.1)';
        button.style.boxShadow = '0 0 10px rgba(139,69,19, 0.7), 0 0 20px rgba(139,69,19, 0.5)';
    });

    button.addEventListener('mouseout', () => {
        button.style.transform = 'scale(1)';
        button.style.boxShadow = 'none';
    });
</script>


        <div class="logout-link">
            <a href="logout.php"><button>Cerrar sesión</button></a>
            <a href="Tienda de Mueble.html"><button>Inicio</button></a>
        </div>
    </div>        
    


  




<div class="cart" id="cart">
    <span class="close-cart">&times;</span>
    <h2>Carrito de Compras</h2>
    <ul id="cart-items">
    <?php foreach ($carrito as $articulo): ?>
        <?php list($id, $nombre, $precio, $imagen, $identificador) = explode('|', $articulo); ?>
        <li class="cart-item" data-id="<?= htmlspecialchars($identificador) ?>" data-name="<?= htmlspecialchars($nombre) ?>" data-price="<?= htmlspecialchars($precio) ?>">
            <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($nombre) ?>" width="50">
            <?= htmlspecialchars($nombre) ?> - $<?= htmlspecialchars($precio) ?>
            <button class="btn-remove">Eliminar</button>
        </li>
    <?php endforeach; ?>
</ul>
<p>Total: $<span id="total-price"><?= number_format($total, 2) ?></span></p>
<button id="btn-open-payment-modal" class="btn-confirm-payment">Pagar</button>

</div>

 <footer>
  <div class="contact-info"> 
       <p>&copy; 2024 Vieux bois."Diseñamos muebles que cuentan historias, tu hogar merece la mejor narrativa."</p>
  </div>    
</footer>


<div id="payment-modal" class="modal-payment">
        <div class="modal-payment-content">
            <span class="btn-close-modal">&times;</span>
            <h2 class="modal-payment-header">Detalles de Pago</h2>
            <form id="payment-form">
                <label for="card-number" class="modal-payment-label">Número de tarjeta:</label>
                <input type="text" id="card-number" name="card-number" class="modal-payment-input" required>

                <label for="expiry-date" class="modal-payment-label">Fecha de expiración:</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/AA" class="modal-payment-input" required>

                <label for="cvv" class="modal-payment-label">CVV:</label>
                <input type="text" id="cvv" name="cvv" class="modal-payment-input" required>

                <label for="name" class="modal-payment-label">Nombre del titular:</label>
                <input type="text" id="name" name="name" class="modal-payment-input" required>

                <button id="btn-pagar"  class="btn-confirm-payment">Confirmar Pago</button>
            </form>
        </div>
    </div>

<script>
// Función para mostrar/ocultar la barra lateral - UTILIZADA para abrir/cerrar la barra lateral
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Función para mostrar/ocultar el carrito - UTILIZADA para abrir/cerrar el carrito al hacer clic en el icono
function toggleCart() {
    const cart = document.getElementById('cart');
    cart.classList.toggle('show');
}

// Función para mostrar/ocultar los dropdowns en la barra lateral - UTILIZADA en los apartados de la barra lateral
function toggleDropdown(element) {
    const dropdownContent = element.nextElementSibling;
    dropdownContent.classList.toggle('show');
}

// Manejo del menú desplegable del perfil - UTILIZADA para el menú de usuario
document.addEventListener('DOMContentLoaded', function() {
    const cartIcon = document.getElementById('cart-icon');
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const cart = document.getElementById('cart');
    const profileImg = document.getElementById('profile-img');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const sidebar = document.getElementById('sidebar');
    const closeCartBtn = document.querySelector('.close-cart');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const cartItems = document.getElementById('cart-items');
    const totalPrice = document.getElementById('total-price');
    let cartCount = 0;
    let total = 0;

    // Mostrar/ocultar el carrito al hacer clic en el icono del carrito
    if (cartToggleBtn) {
        cartToggleBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            cart.classList.toggle('show');
        });
    }

    // Mostrar/ocultar el menú desplegable del perfil al hacer clic en la imagen de perfil
    if (profileImg) {
        profileImg.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevenir cierre inmediato
            dropdownMenu.classList.toggle('show');
        });
    }

   

    // Cerrar el carrito al hacer clic en la "X"
    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', function() {
            cart.classList.remove('show');
        });
    }


    // Asegurarse de cerrar el sidebar al cambiar de tamaño de pantalla
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
        }
    });
});



</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.getElementById('cart-items'); // Lista de productos en el carrito
    const totalPrice = document.getElementById('total-price'); // Total del carrito
    let total = parseFloat(totalPrice.textContent) || 0; // Inicializar total

    // Añadir productos al carrito
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const name = btn.getAttribute('data-name');
            const price = parseFloat(btn.getAttribute('data-price'));
            const image = btn.getAttribute('data-image');

            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    accion: 'añadir',
                    nombre: name,
                    precio: price,
                    imagen: image
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    cartItems.innerHTML += `
                        <li class="cart-item" data-name="${name}" data-price="${price}">
                            <img src="${image}" alt="${name}" width="50">
                            ${name} - $${price.toFixed(2)}
                            <button class="btn-remove">Eliminar</button>
                        </li>
                    `;
                    total += price;
                    totalPrice.textContent = total.toFixed(2);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Eliminar productos del carrito
       // Delegar eventos al contenedor (para manejar elementos dinámicos)
    cartItems.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-remove')) {
            const item = event.target.parentElement; // El elemento <li> del producto
            const id = item.getAttribute('data-id');
            const price = parseFloat(item.getAttribute('data-price'));

            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    accion: 'eliminar',
                    id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    item.remove(); // Eliminar el producto de la lista en la interfaz
                    total -= price;
                    totalPrice.textContent = total.toFixed(2); // Actualizar el total
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});



</script>






<script>
    

    // Elementos de la interfaz móvil
const mobileCartIcon = document.getElementById('mobile-cart-icon');
const mobileUserIcon = document.getElementById('mobile-user-icon');
const mobileCart = document.getElementById('mobile-cart');
const mobileUserMenu = document.getElementById('mobile-user-menu');
const mobileCartItems = document.getElementById('mobile-cart-items');
const mobileTotalPrice = document.getElementById('mobile-total-price');
let mobileTotal = 0;

// Función para mostrar/ocultar el carrito móvil
function toggleMobileCart() {
    mobileCart.classList.toggle('show');
}

// Función para mostrar/ocultar el menú de usuario móvil
function toggleMobileUserMenu() {
    mobileUserMenu.classList.toggle('show');
}

// Eventos para abrir el carrito y el menú de usuario
mobileCartIcon.addEventListener('click', toggleMobileCart);
mobileUserIcon.addEventListener('click', toggleMobileUserMenu);

// Función para actualizar el carrito móvil
function updateMobileCart(name, price, image) {
    mobileCartItems.innerHTML += `
        <li class="cart-item" data-price="${price}">
            <img src="${image}" alt="${name}" width="50">
            ${name} - $${price.toFixed(2)}
            <button class="btn-remove" onclick="removeMobileCartItem(this)">Eliminar</button>
        </li>
    `;
    mobileTotal += price;
    mobileTotalPrice.textContent = `Total: $${mobileTotal.toFixed(2)}`;
}

// Función para eliminar un producto del carrito móvil
function removeMobileCartItem(button) {
    const item = button.parentElement;
    const price = parseFloat(item.getAttribute('data-price'));
    mobileTotal -= price;
    item.remove();
    mobileTotalPrice.textContent = `Total: $${mobileTotal.toFixed(2)}`;
}

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function() {
        const name = this.getAttribute('data-name');
        const price = parseFloat(this.getAttribute('data-price'));
        const image = this.getAttribute('data-image');
        updateMobileCart(name, price, image);
    });
});

</script>

<script>

function toggleDropdown(dropdownId) {
    // Obtener el menú correspondiente
    const dropdownContent = document.getElementById(dropdownId);

    // Cerrar otros menús si están abiertos
    const allDropdowns = document.querySelectorAll('.dropdown-content');
    allDropdowns.forEach(dropdown => {
        if (dropdown !== dropdownContent) {
            dropdown.style.display = 'none'; // Cerrar los demás menús
        }
    });

    // Mostrar/Ocultar el menú seleccionado
    dropdownContent.style.display = 
        (dropdownContent.style.display === 'block') ? 'none' : 'block';
}

</script>

<script>
        // Mostrar el mensaje
        const mensajeAlerta = document.getElementById("mensaje-alerta");
        if (mensajeAlerta) {
            mensajeAlerta.style.display = "block";

            // Ocultar el mensaje después de 5 segundos
            setTimeout(() => {
                mensajeAlerta.style.display = "none";
            }, 5000);
        }
    </script>

    <script>
    document.getElementById('btn-pagar').addEventListener('click', function() {
        const cartItems = document.querySelectorAll('#cart-items li');
        if (cartItems.length === 0) {
            alert('El carrito está vacío.');
            return;
        }

        // Recopilar datos del carrito
        const articulos = [];
        cartItems.forEach(item => {
            articulos.push({
                nombre: item.textContent.split(' - ')[0].trim(),
                precio: item.getAttribute('data-price')
            });
        });

        // Enviar datos al servidor
        fetch('procesar_pago.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ articulos })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pago procesado correctamente. Revisa tu correo.');
            } else {
                alert('Error al procesar el pago: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


<script>
    
            // Abrir modal de pago
        document.getElementById('btn-open-payment-modal').addEventListener('click', function() {
            const paymentModal = document.getElementById('payment-modal');
            paymentModal.style.display = 'block';
        });

        // Cerrar modal al hacer clic en la 'X'
        document.querySelector('.btn-close-modal').addEventListener('click', function() {
            document.getElementById('payment-modal').style.display = 'none';
        });

        // Cerrar modal si el usuario hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('payment-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
</script>
</body>
</html>