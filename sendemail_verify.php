<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kendayroh1gmail@gmail.com';
        $mail->Password   = 'gqop mpgc gtwl mhcl'; // App password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('yourgmail@gmail.com', 'DayrohTech Store');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification from DayrohTech Store';
        $mail->Body    = "
            Hi $name,<br><br>
            Please click the link below to verify your email:<br><br>
            <a href='http://localhost/school/verify-email.php?token=$verify_token&email=$email'>Click to Verify</a><br><br>
            If you did not register, ignore this email.
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>
