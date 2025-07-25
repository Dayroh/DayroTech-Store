<?php
session_start();
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'config.php'; // adjust if your DB config is somewhere else

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && !$user['is_verified']) {
        $verify_token = $user['verify_token'];

        // Send the email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // your SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kendayroh1@gmail.com'; // your Gmail
            $mail->Password   = 'chej piuz elqp lxxu'; // your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            //Recipients
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
            echo "Verification email resent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Either user not found or already verified.";
    }
} else {
    echo "You're not logged in.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resend Verification Email</title>
</head>
<body>
    <h2>Resend Verification Link</h2>
    <?php
    if (isset($_SESSION['status'])) {
        echo "<p style='color:blue;'>" . $_SESSION['status'] . "</p>";
        unset($_SESSION['status']);
    }
    ?>
    <form method="POST" action="">
        <label>Enter your registered email:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Resend Verification Link</button>
    </form>
</body>
</html>
