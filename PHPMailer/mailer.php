<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function envoyerMail($destinataire, $sujet, $message) {
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'joelnambigue360@gmail.com'; 
    $mail->Password   = 'zcurmbczubqjxdcu'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('joelnambigue360@gmail.com', 'Shopesa');
    $mail->addAddress($destinataire);

    $mail->isHTML(true);
    $mail->Subject = $sujet;
    $mail->Body    = '
<div style="font-family: Arial, sans-serif; background-color:#f9f9f9; padding:20px; border-radius:8px; border:1px solid #ddd;">
  <h2 style="color:#2c3e50; text-align:center;">📩 Shopesa center</h2>
  <p style="font-size:16px; color:#333;">
    Bonjour,<br><br>
    Vous avez reçu un nouveau message :
  </p>
  <form style="margin:20px 0; background:#fff; padding:15px; border-radius:6px; border:1px solid #ccc;">
    <label style="display:block; font-weight:bold; margin-bottom:8px; color:#555;">Message :</label>
    <div style="background:#e8f5e9; padding:12px; border-radius:6px; font-size:15px; color:#2e7d32;">
      '.$message.'
    </div>
    <p style="margin-top:15px; font-size:14px; color:#555;">
      Merci de votre fidélité,<br>
      <b>Shopesa</b>
    </p>
  </form>
</div>';

    $mail->send();
    echo "Email envoyé avec succès";
} catch (Exception $e) {
    echo "Erreur : {$mail->ErrorInfo}";
}
}