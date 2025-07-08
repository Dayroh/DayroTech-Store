<?php
$host = "mysql.railway.internal";        
$port = "3306";                
$user = "root";
$pass = "rrdsmfeUcAgQUYjXYGGtNsUkXEnsKzvQ;
$dbname = "railway";

$conn = new mysqli("$host:$port", $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

