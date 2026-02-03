<?php
// Authentication and Session Management
// Smart Hostel Management System

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

// Login function
function login($username, $password, $user_type) {
    $conn = getDBConnection();
    
    // Sanitize inputs
    $username = $conn->real_escape_string($username);
    $user_type = $conn->real_escape_string($user_type);
    
    // Determine which table to query based on user type
    $table = '';
    $id_field = '';
    
    switch ($user_type) {
        case 'admin':
            $table = 'admin';
            $id_field = 'admin_id';
            break;
        case 'staff':
            $table = 'staff';
            $id_field = 'staff_id';
            break;
        case 'student':
            $table = 'student';
            $id_field = 'student_id';
            break;
        default:
            closeDBConnection($conn);
            return false;
    }
    
    // Query to get user
    $sql = "SELECT * FROM $table WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Check if staff/student is active
            if ($user_type === 'staff' && $user['status'] !== 'active') {
                closeDBConnection($conn);
                return false;
            }
            if ($user_type === 'student' && $user['student_status'] !== 'active') {
                closeDBConnection($conn);
                return false;
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user[$id_field];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user_type;
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            // Update last login
            $update_sql = "UPDATE $table SET last_login = NOW() WHERE $id_field = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user[$id_field]);
            $update_stmt->execute();
            
            closeDBConnection($conn);
            return true;
        }
    }
    
    closeDBConnection($conn);
    return false;
}

// Logout function
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check if user has specific role
function hasRole($role) {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $role;
}

// Require login - redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../index.php");
        exit();
    }
}

// Require specific role
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header("Location: ../pages/unauthorized.php");
        exit();
    }
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user type
function getCurrentUserType() {
    return $_SESSION['user_type'] ?? null;
}

// Get current user full name
function getCurrentUserName() {
    return $_SESSION['full_name'] ?? 'Guest';
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Generate random password
function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
    $password = '';
    $char_length = strlen($chars);
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, $char_length - 1)];
    }
    
    return $password;
}

// Generate username from name
function generateUsername($full_name) {
    $conn = getDBConnection();
    
    // Remove special characters and spaces
    $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $full_name));
    
    // Limit to 20 characters
    $username = substr($username, 0, 20);
    
    // Check if username exists
    $counter = 1;
    $original_username = $username;
    
    while (usernameExists($username)) {
        $username = $original_username . $counter;
        $counter++;
    }
    
    closeDBConnection($conn);
    return $username;
}

// Check if username exists
function usernameExists($username) {
    $conn = getDBConnection();
    
    // Check in all user tables
    $tables = ['admin', 'staff', 'student'];
    
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) as count FROM $table WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            closeDBConnection($conn);
            return true;
        }
    }
    
    closeDBConnection($conn);
    return false;
}
?>
