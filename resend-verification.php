<?php
session_start();
include('config.php'); // Your DB connection
include 'includes/mail.php'; // PHPMailer setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // 1. Check if email exists
    $stmt = $conn->prepare("SELECT name, verify_token, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($name, $verify_token, $is_verified);
    $stmt->fetch();
    $stmt->close();

    if ($name) {
        if ($is_verified == 1) {
            $_SESSION['status'] = "Your account is already verified.";
        } else {
            // Resend email
            $mail->addAddress($email);
            $mail->Subject = "Verify your email address";
            $mail->isHTML(true);
            $mail->Body = "
                <h2>Welcome back, $name!</h2>
                <p>Please click the link below to verify your email:</p>
                <a href='https://dayrotech-store-production.up.railway.app/app/verify.php?token=$verify_token'>Verify Email</a>
            ";

            if ($mail->send()) {
                $_SESSION['status'] = "Verification email resent. Check your inbox.";
            } else {
                $_SESSION['status'] = "Email could not be sent. Please try again.";
            }
        }
    } else {
        $_SESSION['status'] = "No account found with that email.";
    }

    header("Location: resend-verification.php");
    exit();
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
