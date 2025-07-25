<?php
session_start();
require_once "config.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $query = "SELECT * FROM users WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['email_verified'] == 1) {
            $_SESSION['status'] = "Your email is already verified.";
            header("Location: login.php");
            exit();
        }

        // Update: mark as verified
        $update_query = "UPDATE users SET email_verified = 1, token = NULL WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $user['id']);
        $update_stmt->execute();

        $_SESSION['status'] = "Email verified successfully. You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['status'] = "Invalid or expired verification link.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['status'] = "No verification token provided.";
    header("Location: login.php");
    exit();
}
?>
