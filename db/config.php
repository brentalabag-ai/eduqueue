<?php
session_start();
// Check if session is not already started
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

$host = "localhost";
$username = "root";
$database = "queue_db";
$password = "";

try{
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}
?>