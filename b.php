<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner Deslizante</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        /* Banner Styles */
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
    <header>
        <h1>Ejemplo de Banner Deslizante</h1>
    </header>
    <main>
        <h2>Bienvenido</h2>
        <p>Aquí tienes un banner deslizante que se mueve de derecha a izquierda.</p>

        <!-- Banner Deslizante -->
        <div class="banner-container">
            <div class="banner-slider">
                <?php
                // Imágenes dinámicas desde PHP
                $imagenes = ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg']; // Cambia estas rutas

                foreach ($imagenes as $imagen) {
                    echo "<div class='slide'><img src='$imagen' alt='Imagen'></div>";
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>

