<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
$mail = new PHPMailer(true);
try {
 $mail->isSMTP();
 $mail->Host = 'smtp.gmail.com';
 $mail->SMTPAuth = true;
 $mail->Username = 'testeenvioemailphp5@gmail.com';
 $mail->Password = 'mjyb lwxk fvqr spta';
 $mail->SMTPSecure = 'tls';
 $mail->Port = 587;
 $mail->setFrom('testeenvioemailphp5@gmail.com', 'sistema');
 $mail->addAddress('marcusfernandesprado@gmail.com');
 $mail->isHTML(true);
 $mail->Subject = 'teste de email';
 $mail->Body = '
 <h1>email enviado com sucesso</h1>
 <p>phpmailer funcionando corretamente</p>
 ';
 $mail->send();
echo "email enviado com sucesso";
} catch (Exception $e) {
 echo "erro: {$mail->ErrorInfo}";
}
?>