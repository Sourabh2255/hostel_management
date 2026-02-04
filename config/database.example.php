<?php
// Database Configuration File
// Smart Hostel Management System

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change this to your MySQL username
define('DB_PASS', '');      // Change this to your MySQL password
define('DB_NAME', 'hostel_management_system');

// Create database connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Set charset to utf8mb4 for better Unicode support
        $conn->set_charset("utf8mb4");
        
        return $conn;
        
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Close database connection
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

// Test database connection
function testConnection() {
    $conn = getDBConnection();
    if ($conn) {
        echo "Database connection successful!";
        closeDBConnection($conn);
        return true;
    }
    return false;
}
?>

