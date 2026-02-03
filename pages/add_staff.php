<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $designation = sanitizeInput($_POST['designation']);
    
    // Auto-generate credentials
    $username = generateUsername($full_name);
    $plain_password = generatePassword(8);
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    $admin_id = getCurrentUserId();

    $sql = "INSERT INTO staff (username, password, full_name, email, phone, designation, status, created_by) VALUES (?, ?, ?, ?, ?, ?, 'active', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $hashed_password, $full_name, $email, $phone, $designation, $admin_id);

    if ($stmt->execute()) {
        $success = "Staff added! <br>Username: <b>$username</b> <br>Password: <b>$plain_password</b>";
    } else {
        $error = "Registration failed. Email or Username might already exist.";
    }
    closeDBConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Staff - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-6 mx-auto py-5">
                <div class="card custom-card">
                    <div class="card-header">Add New Staff Member</div>
                    <div class="card-body">
                        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form method="POST">
                            <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
                            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="mb-3"><label>Phone</label><input type="text" name="phone" class="form-control" required></div>
                            <div class="mb-3"><label>Designation</label><input type="text" name="designation" class="form-control" required></div>
                            <button type="submit" class="btn btn-primary w-100">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>