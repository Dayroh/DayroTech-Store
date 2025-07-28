<?php
require 'config.php'; // DB connection

$message = "";
$success = false;

// Check if token is passed via GET
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Look for the user with this token
    $stmt = $conn->prepare("SELECT * FROM users WHERE verify_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Update the user's verification status
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        $message = "✅ Email verified successfully!";
        $success = true;
    } else {
        $message = "❌ Invalid or expired verification link.";
    }
} else {
    $message = "⚠️ No verification token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2><?php echo htmlspecialchars($message); ?></h2>
        <?php if ($success): ?>
            <p>Redirecting to login...</p>
            <script>
                setTimeout(() => {
                    window.location.href = "login.php"; // Use relative path or full URL if needed
                }, 2500);
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
