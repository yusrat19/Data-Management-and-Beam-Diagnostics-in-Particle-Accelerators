<?php
$conn = new mysqli("localhost", "phpuser", "php123", "PlabDB"); // Connection String

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Database connected successfully!";
?>
