<?php

$servername = "localhost:3377";
$database = "bsoft_web_db";
$username = "root";
$password = "118159.besar";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
mysqli_set_charset($conn, "utf8mb4");
?>