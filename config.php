<?php
$host = "localhost";
//$host = "mysql.railway.internal";        
//$port = "3306";                
$user = "root";
//$pass = "rrdsmfeUcAgQUYjXYGGtNsUkXEnsKzvQ"; 
$pass = ""; // Updated password
$dbname = "railway";

//$conn = new mysqli("$host:$port", $user, $pass, $dbname);
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
