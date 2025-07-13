<?php
// Database configuration for localhost
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Default XAMPP username
define('DB_PASS', '');           // Default XAMPP password (empty)
define('DB_NAME', 'hmtech_db');  // Database name

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin credentials
define('ADMIN_USERNAME', 'hmadmin');
define('ADMIN_PASSWORD', 'hmpassword@123');

// Start session
session_start();

// Create tables if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->multi_query($sql)) {
    // Wait for all queries to finish
    while ($conn->next_result()) {;}
}

// Insert admin user if not exists
$check_user = "SELECT * FROM users WHERE username = '".ADMIN_USERNAME."'";
$result = $conn->query($check_user);

if ($result->num_rows == 0) {
    $hashed_password = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
    $insert_user = "INSERT INTO users (username, password) VALUES ('".ADMIN_USERNAME."', '$hashed_password')";
    $conn->query($insert_user);
}
?>