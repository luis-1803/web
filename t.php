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
    margin: 0px;
    padding: 0;
    color: black;
  
   
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
    margin-left: 20px;
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


/* Header (Encabezado) */


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
   
    gap: 60px;

    
}


#mobile-user-icon {
    cursor: pointer;
    font-size: 18px;
    padding: 10px;
    
    border-radius: 5px;
    text-align: center;
}

/* Contenido del carrito */

.mobile-user-menu {
    display: none;
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

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
    

    .show {
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

 .content {
            flex: 1;
            padding: 20px;
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


.footer {
            background-color: #5a523a;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

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

        .contact-column ul li strong {
            display: inline-block;
            width: 120px;
        }

        .social-icons img {
            width: 30px;
            margin: 0 10px;
        }




        .content {
            flex: 1;
            padding: 20px;
        }

        .banner {
            flex: 1;
            position: relative;
            overflow: hidden;
            height: 500px; /* Altura ajustable según necesidades */
        }

        .moving-image {
            position: absolute;  
            top: 0;
            right: -100%; /* Empieza fuera de la pantalla */
            animation: moveBanner 10s infinite linear; /* Cambia la duración o efecto según necesidades */
        }

        .moving-image img {
            max-width: 100%;
            height: auto;

        }
 
  @keyframes moveBanner {
            0% { right: -100%; }
            100% { right: 0; }
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

.footer {
            background-color: #5a523a;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

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

        .contact-column ul li strong {
            display: inline-block;
            width: 120px;
        }

        .social-icons img {
            width: 30px;
            margin: 0 10px;
        }




        .content {
            flex: 1;
            padding: 20px;
        }

        .banner {
            flex: 1;
            position: relative;
            overflow: hidden;
            height: 500px; /* Altura ajustable según necesidades */
            margin-top: 30px;
        }

        .moving-image {
            position: absolute;  
            top: 0;
            right: -100%; /* Empieza fuera de la pantalla */
            animation: moveBanner 10s infinite linear; /* Cambia la duración o efecto según necesidades */
        }

        .moving-image img {
            max-width: 100%;
            height: auto;

        }
 
  @keyframes moveBanner {
            0% { right: -100%; }
            100% { right: 0; }
        }

@media (min-width: 1024px){
.main {
    display: flex;
}
}






 .banner-container {
            width: 100%;
            overflow: hidden;
            position: relative;
            background: #000;
        }

        .banner-slider {
            display: flex;
            animation: scroll-banner 15s linear infinite; /* Cambiar velocidad aquí */
        }

        .slide {
            flex: 0 0 auto;
            width: 100%;
        }

        .slide img {
    width: 100%;
    height: 100%; /* Asegura que llene el contenedor */
    object-fit: cover; /* Hace que la imagen llene el área sin deformarse */
    object-position: center; /* Centra la imagen en el contenedor */
    display: block;
}


        /* Animación de deslizamiento */
        @keyframes scroll-banner {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

@media screen and (max-width: 768px) {
            .content {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

                 }




/* Ocultar la versión de escritorio en móviles */
@media screen and (max-width: 768px) {
    .main {
        display: none;
    }
}

/* Diseño responsivo: visible solo en móviles */
.responsive-main {
    display: none; /* Oculto por defecto */
}

@media screen and (max-width: 768px) {
    .responsive-main {
        display: block; /* Visible solo en móviles */
    }
}

/* Estilo del banner responsivo */
.responsive-banner-container {
    width: 100%;
    overflow: hidden;
    position: relative;
    background: #000;
    margin-top: 10px;
}

.responsive-banner-slider {
    display: flex;
    animation: scroll-banner 15s linear infinite;
}

.responsive-content{

 flex: 1;
    padding: 20px;   
}
.responsive-slide {
    flex: 0 0 auto;
    width: 100%;
}

.responsive-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

/* Animación de deslizamiento */
@keyframes scroll-banner {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-100%);
    }
}


</style>
</head>
<body>
    <header class="header">
        <img class="logo" src="logo3.png" alt="Logo de la Tienda"  onclick="window.location.href='Tienda de Mueble.html'">


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
        <div class="sidebar-icons"><center>
            <div id="mobile-cart-icon" class="profile-btn" class="cart-icon" id="cart-icon"></div>
            <div id="mobile-user-icon" class="profile-btn"><img src="imagenes/<?php echo htmlspecialchars($imagen_perfil); ?>" alt="Imagen de perfil" id="profile-img"> </div></center>
        </div>
</div>
        <!-- Contenido del carrito -->
        

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

        
    

 <section class="main">
        <div class="content" class="article-description">
            <center><h2>Bienvenido a nuestra tienda de muebles</h2></center>
           <p class="georgia">En Vieux Boiss, fusionamos la nostalgia de la madera antigua con la modernidad del diseño contemporáneo, creando un espacio donde cada mueble cuenta su 
propia historia única. Nos especializamos en ofrecer piezas de mobiliario que encapsulan el encanto atemporal de la madera envejecida, brindando a tu hogar un toque 
de elegancia y carácter.

Cada producto de Vieux Boiss es más que un mueble; es una obra de arte cuidadosamente elaborada que resalta la belleza natural de la madera. Desde salones acogedores 
hasta dormitorios relajantes, nuestra colección abraza la riqueza de la historia de la madera, transformándola en muebles contemporáneos que se integran 
armoniosamente en cualquier espacio.<br>
<br>
Servicio de Vieux Boiss:<br>
En Vieux Boiss, no solo ofrecemos muebles excepcionales; proporcionamos una experiencia única que transforma tus espacios en auténticos refugios de estilo y calidez. 
Nuestro servicio está diseñado para llevarte más allá de la compra de muebles, sumergiéndote en un viaje donde cada detalle refleja nuestro compromiso con la 
excelencia y la atención personalizada.
<br><br>
Asesoría Personalizada:<br>
Nuestro equipo de expertos en diseño de interiores en Vieux Boiss está listo para brindarte asesoramiento personalizado. Desde
la selección de muebles que se adapten a tu estilo hasta la creación de composiciones que realcen la belleza de tu hogar, estamos aquí para convertir tus ideas en realidades concretas.
<br><br><br><br></p>
        </div>
        <div class="banner">
                  <div class="banner-container">
            <div class="banner-slider">
                <?php
                // Imágenes dinámicas desde PHP
                $imagenes = ['cm1.webp', 'mesas2.jpg', 'mesas1.jpg', 'mesas4.jpg']; // Cambia estas rutas

                foreach ($imagenes as $imagen) {
                    echo "<div class='slide'><img src='$imagen' alt='Imagen'></div>";
                }
                ?>
            </div>
        </div>
        </div>
    </section>


    <section class="responsive-main">
    <?php
    // Texto dinámico y banners para móviles
    $contenidoResponsivo = [
        "En Vieux Boiss, fusionamos la nostalgia de la madera antigua con la modernidad del diseño contemporáneo...",
        "Servicio de Vieux Boiss: En Vieux Boiss, no solo ofrecemos muebles excepcionales...",
        "Asesoría Personalizada: Nuestro equipo de expertos en diseño de interiores está listo para brindarte asesoramiento personalizado..."
    ];

    $imagenesResponsivas = ['cm1.webp', 'mesas2.jpg', 'mesas1.jpg', 'mesas4.jpg'];

    foreach ($contenidoResponsivo as $index => $texto) {
        echo "<div class='responsive-content'>
                <p>$texto</p>
              </div>";

        echo "<div class='responsive-banner-container'>
                <div class='responsive-banner-slider'>";

        foreach ($imagenesResponsivas as $imagen) {
            echo "<div class='responsive-slide'><img src='$imagen' alt='Banner'></div>";
        }

        echo "  </div>
              </div>";
    }
    ?>
</section>

  

<div class="cart" id="cart">
    <span class="close-cart">&times;</span>
    <h2>Carrito de Compras</h2>
    <ul id="cart-items">
        <!-- Los productos seleccionados se listarán aquí -->
    </ul>
    <p id="total-price">Total: $0.00</p>
</div>

 <footer class="footer">
        <div class="contact-info">

         

            <div class="contact-columns">
                <div class="contact-column">
                    <ul>
                        <li><strong>Email:</strong>Vieux_bois@gmail.com</li>
                        <li><strong>Teléfono:</strong>+664-594-7665</li>
                        <li><strong>Dirección:</strong>Calle villa del sol,tijuana,Mexico</li>
                    </ul>
                </div>
                <div class="contact-column">
                    <ul>
                        <li>Facebook: /Vieux bois</li>
                        <li>Twitter: @Vieux bois</li>
                        <li>Instagram: @Vieux bois</li>
                        <li>LinkedIn: linkedin.com/in/Vieux bois</li>
                    </ul>
                </div>
                <div class="contact-column">
                    <ul>
                        <li><strong>WhatsApp:</strong>+664-594-7665</li>
                        <li><strong>Horario de atención:</strong> Lunes a Viernes, 9am - 5pm</li>
                    </ul>
                </div>
            </div>
            <div class="social-icons">
                <img src="facebook-app-symbol.png" alt="Facebook">
                <img src="twitter.png" alt="Twitter">
                <img src="instagram.png" alt="Instagram">
                <img src="linkedin.png" alt="LinkedIn">
            </div>
        </div>
        <p>&copy; 2024 Vieux bois. "Diseñamos muebles que cuentan historias, tu hogar merece la mejor narrativa."</p>
    </footer>



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

    // Añadir productos al carrito
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const name = btn.getAttribute('data-name');
            const price = parseFloat(btn.getAttribute('data-price'));
            const image = btn.getAttribute('data-image');
            cartCount++;
            total += price;
            updateCart(name, price, image);
        });
    });

    // Eliminar productos del carrito
    cartItems.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-remove')) {
            const item = event.target.parentElement;
            const price = parseFloat(item.getAttribute('data-price'));
            total -= price;
            item.remove();
            cartCount--;
            totalPrice.textContent = `Total: $${total.toFixed(2)}`;
            document.getElementById('cart-count').textContent = cartCount;
        }
    });

    // Actualizar carrito
    function updateCart(name, price, image) {
        cartItems.innerHTML += `
            <li class="cart-item" data-price="${price}">
                <img src="${image}" alt="${name}">
                ${name} - $${price.toFixed(2)}
                <button class="btn-remove">Eliminar</button>
            </li>
        `;
        totalPrice.textContent = `Total: $${total.toFixed(2)}`;
        document.getElementById('cart-count').textContent = cartCount;
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
</body>
</html>