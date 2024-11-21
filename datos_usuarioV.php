<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Leer el archivo usuarios.txt para obtener los datos del usuario
$usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
$usuario_actual = null;

foreach ($usuarios as $usuario) {
    list($nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
    
    if ($username_existente == $_SESSION['username']) {
        $usuario_actual = [
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .user-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .user-info {
            text-align: center;
        }
        .user-info img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
        .logout-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
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
        </div>
        <div class="logout-link">
            <a href="cerrar_sesio.nphp">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
