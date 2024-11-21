<?php
session_start();

$imagen_perfil = 'default.jpg'; // Imagen predeterminada en caso de que no haya usuario logueado

// Si el usuario ha iniciado sesión, obtener la imagen de perfil
if (isset($_SESSION['username'])) {
    $usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($usuarios as $usuario) {
        list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
        
        if ($username_existente == $_SESSION['username']) {
            $imagen_perfil = $imagen; // Usar la imagen del usuario logueado
            break;
        }
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
    margin: 20px;
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
    }
}
    </style>
</head>
<body>
    <header class="header">
        <img class="logo" src="logo3.png" alt="Logo de la Tienda" onclick="window.location.href='Tienda de Mueble.html'">
        <div class="text">
            <a href="Tienda de Mueble.html"><p class="georgia">Vieux Bois</p></a>
        </div>
        <img src="logo3.png" alt="Logo" class="logo-button" onclick="toggleSidebar()">
        <img src="carrito2.png" alt="Carrito" class="cart-icon" onclick="toggleCart()">
    </header>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        function toggleCart() {
            const cart = document.getElementById('cart');
            cart.classList.toggle('show');
        }
    </script>
</body>
</html>
