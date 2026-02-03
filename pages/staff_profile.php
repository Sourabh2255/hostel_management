<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('staff');

$conn = getDBConnection();
$staff_id = getCurrentUserId();
$success = '';
$error = '';

// Handle Profile Information Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);

    $sql = "UPDATE staff SET full_name = ?, email = ?, phone = ? WHERE staff_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $full_name, $email, $phone, $staff_id);
    
    if ($stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile. Email might already be in use.";
    }
}

// Handle Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE staff SET password = ? WHERE staff_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_pass, $staff_id);
        
        if ($stmt->execute()) {
            $success = "Password updated successfully!";
        } else {
            $error = "Failed to update password.";
        }
    } else {
        $error = "Passwords do not match.";
    }
}

// Fetch current staff data
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-user-circle me-2"></i>My Profile</h2>
                
                <?php if($success) echo "<div class='alert alert-success alert-dismissible fade show'>$success<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
                <?php if($error) echo "<div class='alert alert-danger alert-dismissible fade show'>$error<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card custom-card mb-4">
                            <div class="card-header bg-primary text-white">Edit Personal Information</div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="<?php echo $staff['username']; ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($staff['full_name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($staff['phone']); ?>" required>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card custom-card mb-4">
                            <div class="card-header bg-danger text-white">Security - Change Password</div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="new_password" class="form-control" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control" required minlength="6">
                                    </div>
                                    <button type="submit" name="update_password" class="btn btn-danger w-100">Update Password</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted small">
                                <i class="fas fa-info-circle me-1"></i> Staff ID: <?php echo $staff_id; ?> | Designation: <?php echo htmlspecialchars($staff['designation']); ?>
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
<?php closeDBConnection($conn); ?>