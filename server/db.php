<?php
$host="localhost";
$username="root";
$password="";
$db ="ask";


try {
    $conn = new PDO("mysql:host=localhost;dbname=ask", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

