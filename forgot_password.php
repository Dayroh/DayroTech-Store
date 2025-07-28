<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // Email content
        $resetLink = "https://yourdomain.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n$resetLink\n\nThis link expires in 1 hour.";
        $headers = "From: no-reply@yourdomain.com";

        mail($email, $subject, $message, $headers);
        echo "Check your email for password reset instructions.";
    } else {
        echo "No account found with that email.";
    }
}
?>

<!-- HTML form -->
<form method="POST">
    <label>Enter your email:</label>
    <input type="email" name="email" required>
    <button type="submit">Reset Password</button>
</form>
