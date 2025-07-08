<?php
$host = "${{MySQL.MYSQLHOST}}";        
$port = "${{MySQL.MYSQLPORT}}";                
$user = "root";
$pass = "${{MySQL.MYSQL_ROOT_PASSWORD}}";
$dbname = "${{MySQL.MYSQL_DATABASE}}";

$conn = new mysqli("$host:$port", $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

