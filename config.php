<?php

$host = "mysql.railway.internal";   
//$host = "localhost"; // Use this for local development
$port = "3306";                
$user = "root";
$pass = "rrdsmfeUcAgQUYjXYGGtNsUkXEnsKzvQ"; 
//$pass = "";
$dbname = "railway";

$conn = new mysqli("$host:$port", $user, $pass, $dbname);
//$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
