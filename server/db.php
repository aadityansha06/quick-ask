<?php
$host = "localhost"; // Cleaned up
$dbname = "ask";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Connected to DB successfully!";
} catch (PDOException $e) {
    die("❌ unable to Connect : " . $e->getMessage());
}
?>
