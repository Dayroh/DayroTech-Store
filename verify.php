<?php
require 'config.php'; // Your DB connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE verify_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Mark the user as verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        echo "Email verified successfully. You can now <a href='login.php'>login</a>.";
    } else {
        echo "Invalid or expired verification link.";
    }
} else {
    echo "No verification token provided.";
}
?>
