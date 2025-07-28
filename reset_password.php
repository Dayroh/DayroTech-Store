<?php
require_once 'config.php';

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE reset_token=?");
        $update->bind_param("ss", $newPassword, $token);
        $update->execute();
        echo "Password has been reset. <a href='login.php'>Login</a>";
    } else {
        echo "Invalid or expired token.";
    }
}
?>

<?php if ($token): ?>
<form method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <label>New Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Update Password</button>
</form>
<?php else: ?>
<p>Invalid token.</p>
<?php endif; ?>
