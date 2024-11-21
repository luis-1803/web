<?php
session_start();

$imagen_perfil = 'default.jpg'; // Imagen predeterminada
$mensaje = ''; // Variable para el mensaje
$color_clase = ''; // Clase CSS para el estilo del mensaje

// Ruta del archivo de usuarios y carrito
$archivo_usuarios = 'usuarios.txt';
$archivo_articulos = 'articulos_usu.txt';

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $usuarios = file($archivo_usuarios, FILE_IGNORE_NEW_LINES);

    foreach ($usuarios as $usuario) {
        list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);

        if ($username_existente == $_SESSION['username']) {
            $imagen_perfil = $imagen; // Usar la imagen del usuario logueado
            $_SESSION['id_usuario'] = $id; // Guardar el ID del usuario en la sesión
            $_SESSION['correo'] = $correo; // Guardar el correo para futuras acciones (como envío de correo)
            break;
        }
    }

    $mensaje = 'Ya puedes acceder a tu perfil y añadir artículos al carrito.';
    $color_clase = 'mensaje-verde'; // Clase CSS para mensaje verde
} else {
    $mensaje = 'Inicia sesión para acceder a tu perfil y añadir artículos al carrito.';
    $color_clase = 'mensaje-rojo'; // Clase CSS para mensaje rojo
}

// Manejar peticiones POST para el carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_usuario) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para gestionar el carrito.']);
        exit;
    }

    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $imagen = $_POST['imagen'] ?? '';

    if ($accion === 'añadir') {
        // Añadir artículo al archivo
        $linea = "$id_usuario|$nombre|$precio|$imagen\n";
        file_put_contents($archivo_articulos, $linea, FILE_APPEND);
        echo json_encode(['success' => true, 'message' => 'Artículo añadido al carrito.']);
    } elseif ($accion === 'eliminar') {
        // Eliminar artículo del archivo
        $contenido = file($archivo_articulos, FILE_IGNORE_NEW_LINES);
        $contenido_actualizado = array_filter($contenido, function ($linea) use ($id_usuario, $nombre) {
            return !str_starts_with($linea, "$id_usuario|$nombre");
        });
        file_put_contents($archivo_articulos, implode("\n", $contenido_actualizado) . "\n");
        echo json_encode(['success' => true, 'message' => 'Artículo eliminado del carrito.']);
    }
    exit;
}

// Obtener artículos del carrito para mostrar
$carrito = [];
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $articulos = file($archivo_articulos, FILE_IGNORE_NEW_LINES);
    $carrito = array_filter($articulos, function ($linea) use ($id_usuario) {
        return str_starts_with($linea, "$id_usuario|");
    });
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        /* Aquí tus estilos CSS (puedes usar el contenido actual del archivo) */
    </style>
</head>
<body>
    <header class="header">
        <h1>Bienvenido a Vieux Bois</h1>
        <p class="<?= htmlspecialchars($color_clase) ?>"><?= htmlspecialchars($mensaje) ?></p>
        <img src="imagenes/<?= htmlspecialchars($imagen_perfil) ?>" alt="Imagen de Perfil">
    </header>

    <div class="container">
        <h2>Carrito de Compras</h2>
        <ul id="cart-items">
            <?php foreach ($carrito as $articulo): ?>
                <?php list($id, $nombre, $precio, $imagen) = explode('|', $articulo); ?>
                <li>
                    <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($nombre) ?>" width="50">
                    <?= htmlspecialchars($nombre) ?> - $<?= htmlspecialchars($precio) ?>
                    <button class="btn-remove" data-name="<?= htmlspecialchars($nombre) ?>">Eliminar</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Total: $<span id="total-price">0.00</span></p>
    </div>

    <script>
        // Lógica para gestionar el carrito (añadir/eliminar)
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const name = btn.getAttribute('data-name');
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ 'accion': 'eliminar', 'nombre': name })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        btn.parentElement.remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>