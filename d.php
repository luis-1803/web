<?php
session_start();

$imagen_perfil = 'default.jpg'; // Imagen predeterminada en caso de que no haya usuario logueado

// Si el usuario ha iniciado sesión, obtener la imagen de perfil
if (isset($_SESSION['username'])) {
    $usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($usuarios as $usuario) {
        list($nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
        
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
    <title>Página de Inicio</title>
    <style>
        body {
            font-family: Georgia, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        a {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            margin-top: 20px;
        }

        .profile-btn {
            width: 100%; 
            max-width: 200px;
            margin: 0 auto;
            position: relative;
        }

        .profile-btn img {
            cursor: pointer;
            border: 3px solid #000000;
            transition: border-color 0.3s ease;
            justify-content: center;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: contain;
            margin-top: 10px;
            margin-left: 10px;
        }

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
            display: block;
            border-bottom: 1px solid #eee;
            display: inline-block;
            margin: 6px;
            background-color: #8B4513; /* Color café */
            color: white;
            border: none;
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

        .profile-btn {
            text-align: center;        
        }
    </style>
</head>
<body>
    <div class="profile-btn">
        <img src="imagenes/<?php echo htmlspecialchars($imagen_perfil); ?>" alt="Imagen de perfil" id="profile-img">
        <div class="dropdown-menu" id="dropdown-menu">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Si el usuario ha iniciado sesión, mostrar "Perfil" -->
                <a href="datos_usuario.php"><i class="fas fa-user"></i> Perfil</a>
                <a href="cerrar_sesion.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            <?php else: ?>
                <!-- Si el usuario no ha iniciado sesión, mostrar "Iniciar sesión" -->
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> Registrarse</a>
            <?php endif; ?>
        </div>
    </div>

    <script>        
        document.getElementById('profile-img').addEventListener('click', function() {
            const menu = document.getElementById('dropdown-menu');
            menu.classList.toggle('show');
        });

        window.onclick = function(event) {
            if (!event.target.matches('#profile-img')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script> 
</body>
</html>
