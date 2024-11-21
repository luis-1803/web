<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'victoriasalasluisangel@gmail.com'; // Cambia esto por tu correo
    $mail->Password = 'jbbb ylmx jkid dqpg'; // Cambia esto por tu contraseña o contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('victoriasalasluisangel@gmail.com', 'Tu Nombre');
    $mail->addAddress('destinatario@gmail.com', 'Destinatario');

    $mail->isHTML(true);
    $mail->Subject = 'Correo de Prueba';
    $mail->Body = 'Este es un correo de prueba usando PHPMailer en XAMPP.';
    $mail->AltBody = 'Este es un correo de prueba en texto plano.';

    $mail->send();
    echo 'El correo fue enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
