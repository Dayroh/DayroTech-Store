<?php
session_start();
require 'vendor/autoload.php'; // Composer PHPMailer
include 'config.php'; // Your DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle the form submission
$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Look up user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['is_verified']) {
            $message = "<p style='color: green;'>Your account is already verified. You can log in.</p>";
        } else {
            $verify_token = $user['verify_token'];

            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'kendayroh1@gmail.com';
                $mail->Password   = 'chej piuz elqp lxxu';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('your-email@gmail.com', 'DayrohTech');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Resend: Verify your email address";
                $mail->Body    = "
                    <h2>Verify your email</h2>
                    <p>Click the link below to verify:</p>
                    <a href='https://dayrotech-store-production.up.railway.app/app/verify.php?token=$verify_token'>Verify Email</a>
                ";

                $mail->send();
                $message = "<p style='color: green;'>Verification email has been resent. Check your inbox.</p>";
            } catch (Exception $e) {
                $message = "<p style='color: red;'>Email failed to send: {$mail->ErrorInfo}</p>";
            }
        }
    } else {
        $message = "<p style='color: red;'>No account found with that email address.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resend Verification</title>
</head>
<body>
    <h2>Resend Verification Email</h2>
    <?= $message ?>

    <form method="POST" action="">
        <label for="email">Enter your email address:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Resend Verification Email</button>
    </form>

    <p><a href="login.php">Back to login</a></p>
</body>
</html>
