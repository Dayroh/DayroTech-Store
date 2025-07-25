<?php
include 'config.php';

$email = $_GET['email'];
$token = $_GET['token'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND verify_token=?");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $update = $conn->prepare("UPDATE users SET is_verified=1, verify_token=NULL WHERE email=?");
    $update->bind_param("s", $email);
    $update->execute();
    echo "Email verified successfully. You can now <a href='login.php'>login</a>.";
} else {
    echo "Invalid or expired verification link.";
}
?>
