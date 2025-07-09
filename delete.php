<?php
session_start();
include 'config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = (int)$_GET['id'];

    switch ($type) {
        case 'user':
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            header("Location: admin_users.php?deleted=1");
            exit();

        case 'message':
            $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            header("Location: admin_messages.php?deleted=1");
            exit();

        case 'plan':
            $stmt = $conn->prepare("DELETE FROM plan_requests WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            header("Location: request_plan.php?deleted=1#plans");
            exit();

        case 'order':
            // First delete order items (if exists)
            $conn->query("DELETE FROM order_items WHERE order_id = $id");
            // Then delete the order itself
            $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            header("Location: admin_orders.php?deleted=1");
            exit();

        default:
            header("Location: index.php");
            exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
