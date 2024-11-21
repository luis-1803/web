<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ruta al autoload de Composer

session_start();

if (!isset($_SESSION['correo'])) {
    echo "Debes iniciar sesión para enviar el correo.";
    exit;
}

$correo_destinatario = $_SESSION['correo']; // Correo registrado del usuario

// Configurar PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'tu_correo@gmail.com'; // Cambiar por tu correo Gmail
    $mail->Password = 'tu_contraseña'; // Cambiar por tu contraseña o contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Destinatarios
    $mail->setFrom('tu_correo@gmail.com', 'Vieux Bois');
    $mail->addAddress($correo_destinatario); // Correo del usuario

    // Archivos adjuntos
    $mail->addAttachment('articulos_usu.txt', 'articulos_usu.txt'); // Archivo de artículos
    $mail->addAttachment('mesas.html', 'mesas.html'); // Archivo de HTML

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Tus archivos de Vieux Bois';
    $mail->Body = '
        <h1>¡Hola!</h1>
        <p>Gracias por usar Vieux Bois. Te adjuntamos los archivos relacionados con tu cuenta:</p>
        <ul>
            <li><strong>Artículos</strong>: Detalle de tus artículos seleccionados.</li>
            <li><strong>Página HTML</strong>: Información sobre los productos.</li>
        </ul>
        <p>Si tienes alguna duda, no dudes en contactarnos.</p>
        <p><em>Vieux Bois</em></p>
    ';
    $mail->AltBody = 'Gracias por usar Vieux Bois. Te adjuntamos tus archivos relacionados.';

    // Enviar correo
    $mail->send();
    echo "El correo ha sido enviado con éxito a $correo_destinatario.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
