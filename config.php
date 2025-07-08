<?php
$host = "${{MySQL.MYSQLHOST}}";        
$port = "3306";                
$user = "root";
$pass = "${{MySQL.MYSQL_ROOT_PASSWORD}}";
$dbname = "${{MySQL.MYSQL_DATABASE}}";

$conn = new mysqli("$host:$port", $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

