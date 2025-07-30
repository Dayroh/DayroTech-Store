<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = $_POST['name'] ?? '';
    $customer_email = $_POST['email'] ?? '';
    $order_details = $_POST['order_details'] ?? '';
    $order_id = uniqid('ORD-');

    // Setup mailer instance
    $mailAdmin = new PHPMailer(true);
    $mailUser = new PHPMailer(true);

    try {
        // === SMTP Settings ===
        foreach ([$mailAdmin, $mailUser] as $mail) {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kendayroh1@gmail.com'; // your Gmail
            $mail->Password = 'chej piuz elqp lxxu';  // your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);
        }

        // === Send to Admins ===
        $mailAdmin->setFrom($customer_email, $customer_name);

        // Add multiple admin addresses
        $adminEmails = ['admin1@example.com', 'admin2@example.com'];
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

        // === Send Invoice to User ===
        $mailUser->setFrom('kendayroh1@gmail.com', 'DayrohTech Store');
        $mailUser->addAddress($customer_email, $customer_name);

        $mailUser->Subject = "🧾 Your Invoice for Order #$order_id";
        $mailUser->Body = "
            <h2>Invoice Confirmation</h2>
            <p>Thank you <strong>$customer_name</strong> for your order.</p>
            <p><strong>Order ID:</strong> $order_id</p>
            <p><strong>Details:</strong><br>$order_details</p>
            <p>We will contact you shortly for delivery.</p>
        ";
        $mailUser->send();

        echo "✅ Order submitted and emails sent successfully!";
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
