<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
$admin_id = getCurrentUserId();
$success = '';
$error = '';

// Handle Update Profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);

    $sql = "UPDATE admin SET full_name = ?, email = ?, phone = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $full_name, $email, $phone, $admin_id);
    
    if ($stmt->execute()) {
        $_SESSION['full_name'] = $full_name; // Update session name
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile. Email might already be in use.";
    }
}

// Fetch current admin data
$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-user-circle me-2"></i>Admin Profile</h2>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header">Edit Personal Information</div>
                            <div class="card-body">
                                <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                                <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                                
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="<?php echo $admin['username']; ?>" disabled>
                                        <small class="text-muted">Username cannot be changed.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($admin['full_name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($admin['phone']); ?>" required>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary w-100">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header">Account Details</div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Member Since:</span>
                                        <strong><?php echo formatDate($admin['created_at']); ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Last Login:</span>
                                        <strong><?php echo $admin['last_login'] ? formatDateTime($admin['last_login']) : 'N/A'; ?></strong>
                                    </li>
                                </ul>
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Security Tip: Regularly update your contact details to ensure system notifications reach you.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>