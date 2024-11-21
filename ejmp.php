<?php
session_start();

// Imagen de perfil predeterminada
$imagen_perfil = 'default.jpg';

// Si el usuario ha iniciado sesión, obtener la imagen de perfil y el ID
if (isset($_SESSION['username'])) {
    $usuarios = file('usuarios.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($usuarios as $usuario) {
        list($id, $nombre, $apellido, $fecha_nac, $sexo, $correo, $telefono, $username_existente, $password, $imagen) = explode('|', $usuario);
        
        if ($username_existente == $_SESSION['username']) {
            $imagen_perfil = $imagen;
            $_SESSION['user_id'] = $id; // Guardamos el ID del usuario para identificarlo
            break;
        }
    }
}

// Manejo de acciones de carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'add' && isset($_POST['name'], $_POST['price'], $_POST['image']) && isset($_SESSION['user_id'])) {
        // Agregar artículo al archivo de carrito
        $userId = $_SESSION['user_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_POST['image'];

        $entry = "$userId|$name|$price|$image\n";
        file_put_contents('articulos_usu.txt', $entry, FILE_APPEND);

        echo json_encode(['success' => true]);
        exit;
    } elseif ($action === 'remove' && isset($_POST['name']) && isset($_SESSION['user_id'])) {
        // Eliminar artículo del archivo de carrito
        $userId = $_SESSION['user_id'];
        $nameToDelete = $_POST['name'];

        $lines = file('articulos_usu.txt', FILE_IGNORE_NEW_LINES);
        $newLines = [];

        foreach ($lines as $line) {
            list($id, $name, $price, $image) = explode('|', $line);
            if (!($id === $userId && $name === $nameToDelete)) {
                $newLines[] = $line;
            }
        }

        file_put_contents('articulos_usu.txt', implode("\n", $newLines));

        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        /* Aquí va tu CSS */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            transition: opacity 0.5s ease;
        }
    </style>
</head>
<body>

    <!-- Aquí va tu HTML -->
    <div class="container" id="products-container">
        <div class="product">
            <div class="article">
                <div class="article-content">
                    <img src="sillones1.jpg" alt="Producto 1" width="80%">
                    <div class="article-title">Seccional Decohome Alba</div>
                    <div class="article-price">Precio: $60.00</div>
                    <button class="add-to-cart-btn" data-name="Seccional Decohome Alba" data-price="60" data-image="sillones1.jpg">Añadir al Carrito</button>
                </div>
            </div>
        </div>
    </div>

    <div class="cart" id="cart">
        <span class="close-cart">&times;</span>
        <h2>Carrito de Compras</h2>
        <ul id="cart-items"></ul>
        <p id="total-price">Total: $0</p>
    </div>

    <script>
    // Script JavaScript
    document.addEventListener("DOMContentLoaded", function() {
        const cartIcon = document.getElementById('cart-icon');
        const cart = document.getElementById('cart');
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        const cartItems = document.getElementById('cart-items');
        const totalPrice = document.getElementById('total-price');
        const closeCartBtn = document.querySelector('.close-cart');
        const cartCountEl = document.getElementById('cart-count');

        let cartCount = 0;
        let total = 0;

        // Añadir producto al carrito y enviar datos al servidor
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const name = btn.getAttribute('data-name');
                const price = parseFloat(btn.getAttribute('data-price'));
                const image = btn.getAttribute('data-image');
                cartCount++;
                total += price;

                // Llamada AJAX para guardar en el archivo PHP
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'add',
                        name: name,
                        price: price,
                        image: image
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCart(name, price, image);
                        showNotification("Producto añadido al carrito");
                    }
                });
            });
        });

        // Eliminar producto del carrito y actualizar el archivo
        cartItems.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove')) {
                const item = event.target.parentElement;
                const price = parseFloat(item.getAttribute('data-price'));
                const name = item.textContent.trim().split(' - ')[0];
                total -= price;
                cartCount--;

                // Llamada AJAX para eliminar en el archivo PHP
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'remove',
                        name: name
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        item.remove();
                        totalPrice.textContent = `Total: $${total.toFixed(2)}`;
                        cartCountEl.textContent = cartCount;
                        showNotification("Producto eliminado del carrito");
                    }
                });
            }
        });

        // Función para actualizar el carrito en la interfaz
        function updateCart(name, price, image) {
            cartItems.innerHTML += `
                <li class="cart-item" data-price="${price}">
                    <img src="${image}" alt="${name}" width="50">
                    ${name} - $${price.toFixed(2)}
                    <button class="btn-remove">Eliminar</button>
                </li>`;
            totalPrice.textContent = `Total: $${total.toFixed(2)}`;
            cartCountEl.textContent = cartCount;
        }

        // Función para mostrar notificación emergente
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }
    });
    </script>
</body>
</html>
