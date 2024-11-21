<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .img-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .img-container img {
            max-width: 100%;
            height: auto;
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
    width: 100%; /* El contenedor de la imagen ocupa el 100% del ancho disponible */
    max-width: 200px; /* Puedes ajustar este tamaño según lo que prefieras */
    margin: 0 auto; /* Centrar el contenedor en la pantalla */
    position: relative; /* Para manejar el menú desplegable */
}

/* Estilo de la imagen de perfil */
.profile-btn img {
    width: 100%; /* La imagen ocupará todo el ancho del contenedor */
    height: auto; /* Mantendrá las proporciones de la imagen */
    border-radius: 50%; /* Mantiene la imagen redondeada */
    cursor: pointer;
    border: 2px solid #ddd;
    transition: border-color 0.3s ease;
     justify-content: center;
}

/* Menú desplegable debajo de la imagen de perfil */
.dropdown-menu {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background-color: white;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    min-width: 150px;
    z-index: 1;
}

.dropdown-menu a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: block;
    border-bottom: 1px solid #eee;
}

.dropdown-menu a:hover {
    background-color: #f1f1f1;
}

/* Mostrar el menú desplegable cuando esté activo */
.show {
    display: block;
}

/* Estilos para los botones en el menú */
.dropdown-menu a i {
    margin-right: 10px;
}


    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a tu Drive Personal</h1>

        
        <div class="profile-btn">
            <img src="imagenes/pngwing.com.png" alt="Imagen de perfil" id="profile-img">
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> Registrarse</a>
                <a href="cerrar_sesion.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            </div>
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
