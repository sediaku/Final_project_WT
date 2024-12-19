<?php


$servername = "localhost";
$username = "sedinam.senaya";
$password = "Munchserver@004";
$dbname = "webtech_fall2024_sedinam_senaya";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>