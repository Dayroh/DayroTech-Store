<?php
$host = "localhost";
$user = "root";
$pass = ""; // default for XAMPP/WAMP
$dbname = "dayrohtech_store";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
