<?php
session_start();
include 'config.php';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    $query = "SELECT * FROM users WHERE verify_token = ? AND email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if ($user['email_verified'] == 0) {
            $update = "UPDATE users SET email_verified = 1, verify_token = NULL WHERE email = ?";
            $stmt_update = $conn->prepare($update);
            $stmt_update->bind_param("s", $email);
            $stmt_update->execute();

            $_SESSION['status'] = "✅ Email verified successfully. You may now login.";
        } else {
            $_SESSION['status'] = "⚠️ Email already verified.";
        }
    } else {
        $_SESSION['status'] = "❌ Invalid verification link.";
    }
} else {
    $_SESSION['status'] = "❌ Missing verification parameters.";
}

header("Location: login.php");
exit();
?>
