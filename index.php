<?php
// Main Login Page
// Smart Hostel Management System

require_once 'includes/auth.php';
require_once 'includes/functions.php';

// If already logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    $user_type = getCurrentUserType();
    header("Location: pages/{$user_type}_dashboard.php");
    exit();
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $user_type = sanitizeInput($_POST['user_type']);
    
    if (empty($username) || empty($password) || empty($user_type)) {
        $error = 'All fields are required';
    } else {
        if (login($username, $password, $user_type)) {
            header("Location: pages/{$user_type}_dashboard.php");
            exit();
        } else {
            $error = 'Invalid credentials or account inactive';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Hostel Management System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-10 col-lg-8">
                <div class="row shadow-lg rounded overflow-hidden">
                    <!-- Left side - Welcome section -->
                    <div class="col-md-6 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                        <div class="text-center">
                            <i class="fas fa-building fa-5x mb-4"></i>
                            <h2 class="mb-3">Smart Hostel</h2>
                            <h4 class="mb-3">Management System</h4>
                            <p class="lead">Efficient Room Allocation & Maintenance Management</p>
                            <hr class="bg-white">
                            <p><i class="fas fa-check-circle me-2"></i> Room Management</p>
                            <p><i class="fas fa-check-circle me-2"></i> Student Management</p>
                            <p><i class="fas fa-check-circle me-2"></i> Payment Tracking</p>
                            <p><i class="fas fa-check-circle me-2"></i> Complaint Management</p>
                        </div>
                    </div>
                    
                    <!-- Right side - Login form -->
                    <div class="col-md-6 bg-white p-5">
                        <div class="login-form">
                            <h3 class="text-center mb-4">Login to Your Account</h3>
                            
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select name="user_type" class="form-select" required>
                                        <option value="">Select User Type</option>
                                        <option value="admin">Admin (Hostel Owner)</option>
                                        <option value="staff">Staff</option>
                                        <option value="student">Student</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                    </div>
                                </div>
                                
                                <button type="submit" name="login" class="btn btn-primary w-100 mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </form>
                            
                            <hr>
                            
                            <div class="text-center">
                                <p class="mb-2"><strong>New Student?</strong></p>
                                <a href="pages/apply.php" class="btn btn-outline-success">
                                    <i class="fas fa-file-alt me-2"></i>Apply for Hostel Admission
                                </a>
                            </div>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Staff and Student accounts are created by Admin
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Demo Credentials -->
                <div class="card mt-4 border-info">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-info-circle me-2"></i>Demo Credentials (For Testing)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Admin:</strong><br>
                                Username: <code>admin</code><br>
                                Password: <code>admin123</code>
                            </div>
                            <div class="col-md-4">
                                <strong>Staff:</strong><br>
                                <small class="text-muted">Created by Admin</small>
                            </div>
                            <div class="col-md-4">
                                <strong>Student:</strong><br>
                                <small class="text-muted">Created by Staff/Admin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
