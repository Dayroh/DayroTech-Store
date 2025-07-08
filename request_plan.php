<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);

    $sql = "INSERT INTO plan_requests (name, email, phone, package, request_date)
            VALUES ('$name', '$email', '$phone', '$package', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Your request has been submitted!'); window.location.href='plans.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
