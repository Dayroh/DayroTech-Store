<?php
session_start(); // Make sure session is started
include 'config.php'; // DB connection
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $message = trim($_POST['message']);

  if ($name && $email && $message) {
    // 1. Save to DB
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();

    // 2. Send email to admin
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com'; // or your host
      $mail->SMTPAuth = true;
      $mail->Username = 'kendayroh1@gmail.com'; // your email
      $mail->Password = 'chej piuz elqp lxxu';   // Gmail App password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->setFrom($email, $name);
      $mail->addAddress('admin@dayrohtech.com', 'Admin');

      $mail->isHTML(true);
      $mail->Subject = 'New Contact Message';
      $mail->Body = "
        <h3>Message from $name</h3>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong><br>$message</p>
      ";

      $mail->send();
      $success = "Thanks, $name! We've received your message.";
    } catch (Exception $e) {
      $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    $error = "Please fill in all fields.";
  }
}

// Determine where to send the Back button
$backLink = isset($_SESSION['user_id']) ? 'user.php' : 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contact Us | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="whatsapp.css">
<script src="whatsapp.js" defer></script>
</head>
<body>
<div class="container my-5">
  <h2>📞 Contact Us</h2>
  <p>Need help, have questions or a custom request? Fill in the form below.</p>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Message</label>
      <textarea name="message" class="form-control" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>

  <!-- Back to Homepage or Dashboard -->
  <a href="<?= $backLink ?>" class="btn btn-outline-secondary mt-4">← Back to Homepage</a>

  <!-- Optional Map -->
 <div class="mt-5">
  <h5>Our Location</h5>
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127628.79501276038!2d39.61683959136741!3d-4.043477204503341!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x18404429e3215d07%3A0xa6bd7a2ff291abf2!2sTechnical%20University%20of%20Mombasa!5e0!3m2!1sen!2ske!4v1620111679056!5m2!1sen!2ske" 
    width="100%" height="300" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy">
  </iframe>
</div>

</div>
</body>
</html>
