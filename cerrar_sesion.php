<?php
session_start();
session_unset();
session_destroy();
header("Location: Tienda de Mueble.html");
exit;
?>
