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
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kendayroh1@gmail.com';
            $mail->Password = 'chej piuz elqp lxxu';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($email, $name);
            $mail->addAddress('kendayroh1@gmail.com', 'Admin');

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .contact-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .contact-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .contact-header p {
            color: var(--text-color);
            font-size: 1.1rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            transition: all 0.3s;
        }
        
        .btn-outline-secondary:hover {
            transform: translateX(-3px);
        }
        
        .contact-map {
            margin-top: 3rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .contact-map h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .contact-card {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .contact-card h6 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border-radius: 0.35rem;
            padding: 1rem;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <h2><i class="fas fa-envelope-open-text me-2"></i>Contact Us</h2>
            <p>Have questions, feedback, or need support? We'd love to hear from you!</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-7">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Message</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                    <a href="<?= $backLink ?>" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="contact-info">
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h6>Our Location</h6>
                        <p>Technical University of Mombasa<br>Mombasa, Kenya</p>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <h6>Email Us</h6>
                        <p>info@dayrohtech.com<br>support@dayrohtech.com</p>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-phone-alt"></i>
                        <h6>Call Us</h6>
                        <p>+254 712 345 678<br>+254 111 324 234<br>Mon-Fri, 9am-5pm</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-map">
            <h5><i class="fas fa-map-marked-alt me-2"></i>Find Us on Map</h5>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127628.79501276038!2d39.61683959136741!3d-4.043477204503341!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x18404429e3215d07%3A0xa6bd7a2ff291abf2!2sTechnical%20University%20of%20Mombasa!5e0!3m2!1sen!2ske!4v1620111679056!5m2!1sen!2ske" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
