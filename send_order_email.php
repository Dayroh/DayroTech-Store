<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = trim($_POST['name'] ?? '');
    $customer_email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $order_details = trim($_POST['order_details'] ?? '');
    $order_id = uniqid('ORD-');

    if (!$customer_name || !$customer_email || !$order_details) {
        echo "❌ Missing or invalid order data.";
        exit;
    }

    // Setup PHPMailer instances
    $mailAdmin = new PHPMailer(true);
    $mailUser = new PHPMailer(true);

    try {
        foreach ([$mailAdmin, $mailUser] as $mail) {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kendayroh1@gmail.com'; // Your Gmail
            $mail->Password = 'chej piuz elqp lxxu';  // Your App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);
        }

        // === Email to Admins ===
        $mailAdmin->setFrom($customer_email, $customer_name);
        $adminEmails = ['kendayroh1@gmail.com', 'ochiengtilen5@gmail.com'];
        foreach ($adminEmails as $adminEmail) {
            $mailAdmin->addAddress($adminEmail);
        }

        $mailAdmin->Subject = "📬 New Order Received: #$order_id";
        $mailAdmin->Body = "
            <h2>New Order Notification</h2>
            <p><strong>Order ID:</strong> $order_id</p>
            <p><strong>Customer Name:</strong> $customer_name</p>
            <p><strong>Email:</strong> $customer_email</p>
            <p><strong>Order Details:</strong><br>$order_details</p>
        ";
        $mailAdmin->send();

        // === Invoice to Customer ===
        $mailUser->setFrom('kendayroh1@gmail.com', 'DayrohTech Store');
        $mailUser->addAddress($customer_email, $customer_name);

        $mailUser->Subject = "🧾 Your Invoice for Order #$order_id";
        $mailUser->Body = "
            <h2>Invoice Confirmation</h2>
            <p>Dear <strong>$customer_name</strong>,</p>
            <p>Thank you for your order. Below are your order details:</p>
            <p><strong>Order ID:</strong> $order_id</p>
            <p><strong>Order Details:</strong><br>$order_details</p>
            <p>We will contact you shortly for delivery.</p>
            <p>Regards,<br>DayrohTech Store</p>
        ";
       $mailUser->send();
$email_status = 'success'; // <-- THIS is what checkout.php checks for
return true;


    } catch (Exception $e) {
        echo "❌ Email Error: " . $e->getMessage();
    }
}
?>
