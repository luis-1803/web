<?php
session_start();

// Simulación de inicio de sesión (cambiar según la lógica de tu aplicación)
$_SESSION['username'] = null; // Cambia a un nombre de usuario para simular sesión iniciada

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        /* Estilos para el cuadro de mensaje */
        #mensaje-login {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ffdddd;
            color: #333;
            padding: 15px;
            border: 1px solid #ff0000;
            border-radius: 5px;
            z-index: 1000;
            font-family: Arial, sans-serif;
            font-size: 14px;
            display: none; /* Ocultamos inicialmente */
        }
    </style>
</head>
<body>

    <!-- Cuadro de mensaje -->
    <?php if (!$usuario_logueado): ?>
        <div id="mensaje-login">Inicia sesión para añadir artículos al carrito.</div>
    <?php endif; ?>

    <!-- Contenido de la página -->
    <h1>Carrito de Compras</h1>
    <?php if ($usuario_logueado): ?>
        <p>Hola, <?= htmlspecialchars($_SESSION['username']) ?>. ¡Bienvenido de nuevo!</p>
        <p>Puedes añadir artículos al carrito.</p>
    <?php else: ?>
        <p>Por favor, inicia sesión para usar el carrito.</p>
    <?php endif; ?>

    <script>
        // Mostrar el mensaje si existe
        const mensajeLogin = document.getElementById("mensaje-login");
        if (mensajeLogin) {
            mensajeLogin.style.display = "block";

            // Ocultar después de 5 segundos
            setTimeout(() => {
                mensajeLogin.style.display = "none";
            }, 5000);
        }
    </script>
</body>
</html>
