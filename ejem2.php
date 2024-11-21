<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Responsiva</title>
    <link rel="stylesheet" href="styles.css">
</head>

<style>
    

    /* Estilos Generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

/* Botón para abrir la barra lateral */
.logo-button {
    position: fixed;
    top: 10px;
    left: 10px;
    background-color: #5a523a;
    color: white;
    font-size: 24px;
    border: none;
    padding: 10px;
    cursor: pointer;
    z-index: 1001;
    margin-left: 300px;
}

/* Barra lateral */
.sidebar {
    position: fixed;
    top: 0;
    left: -250px;
    width: 250px;
    height: 100%;
    background-color: #5a523a;
    color: white;
    padding: 20px;
    z-index: 1000;
    transition: left 0.3s ease-in-out;
    overflow-y: auto;
}

.sidebar.show {
    left: 0;
}

.close-sidebar {
    font-size: 24px;
    color: white;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 20px;
}

/* Iconos del carrito y usuario */
.sidebar-icons {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

#mobile-cart-icon,
#mobile-user-icon {
    cursor: pointer;
    font-size: 18px;
    padding: 10px;
    background-color: #746844;
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
         margin-top: 20px;
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
        border-bottom: 1px solid #ddd;
    }
}
</style>
<body>

    <!-- Botón para abrir la barra lateral en móvil -->
    <button class="logo-button" onclick="toggleSidebar()">☰</button>

    <!-- Barra Lateral -->
    <div class="sidebar" id="sidebar">
        <div class="close-sidebar" onclick="toggleSidebar()">&times;</div>
<div class="mobile-bar">
        <!-- Iconos del carrito y del usuario dentro de la barra lateral -->
        <div class="sidebar-icons">
            <div id="mobile-cart-icon">🛒 Carrito</div>
            <div id="mobile-user-icon">👤 Usuario</div>
        </div>
</div>
        <!-- Contenido del carrito -->
        <div class="mobile-cart" id="mobile-cart">
            <h2>Carrito de Compras</h2>
            <ul id="mobile-cart-items"></ul>
            <p id="mobile-total-price">Total: $0</p>
            <button class="btn-close" onclick="toggleMobileCart()">Cerrar</button>
        </div>

        <!-- Menú de usuario -->
        <div class="mobile-user-menu" id="mobile-user-menu">
            <a href="login.html">Iniciar sesión</a>
            <a href="register.html">Registrarse</a>
            <button class="btn-close" onclick="toggleMobileUserMenu()">Cerrar</button>
        </div>
    </div>

    <script>
        
        // Elementos de la interfaz
const sidebar = document.getElementById('sidebar');
const mobileCartIcon = document.getElementById('mobile-cart-icon');
const mobileUserIcon = document.getElementById('mobile-user-icon');
const mobileCart = document.getElementById('mobile-cart');
const mobileUserMenu = document.getElementById('mobile-user-menu');
const mobileCartItems = document.getElementById('mobile-cart-items');
const mobileTotalPrice = document.getElementById('mobile-total-price');
let mobileTotal = 0;

// Función para mostrar/ocultar la barra lateral
function toggleSidebar() {
    sidebar.classList.toggle('show');
}

// Función para mostrar/ocultar el carrito dentro de la barra lateral
function toggleMobileCart() {
    mobileCart.classList.toggle('show');
    mobileUserMenu.classList.remove('show'); // Ocultar menú de usuario si está abierto
}

// Función para mostrar/ocultar el menú de usuario dentro de la barra lateral
function toggleMobileUserMenu() {
    mobileUserMenu.classList.toggle('show');
    mobileCart.classList.remove('show'); // Ocultar carrito si está abierto
}

// Eventos para abrir el carrito y el menú de usuario
mobileCartIcon.addEventListener('click', toggleMobileCart);
mobileUserIcon.addEventListener('click', toggleMobileUserMenu);

// Función para actualizar el carrito
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

// Función para eliminar un producto del carrito
function removeMobileCartItem(button) {
    const item = button.parentElement;
    const price = parseFloat(item.getAttribute('data-price'));
    mobileTotal -= price;
    item.remove();
    mobileTotalPrice.textContent = `Total: $${mobileTotal.toFixed(2)}`;
}

    </script>
</body>
</html>
