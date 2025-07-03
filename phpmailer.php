<?php
// Inclui os arquivos necessários
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuração SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'antoniodiogo1725@gmail.com';       // Teu e-mail Gmail
    $mail->Password = 'btcdgpvfwdpjsrtr';            // Tua senha de App do Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Remetente e Destinatário
    $mail->setFrom('antoniodiogo1725@gmail.com', 'Teu Nome');
    $mail->addAddress('antoniodiogo1725@gmail.com', 'Destinatário');

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Teste de Envio de E-mail';
    $mail->Body    = '<b>Este é um teste via PHPMailer sem Composer</b>';
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'  => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
?>
