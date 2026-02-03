<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
$success = '';
$error = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    $name = sanitizeInput($_POST['hostel_name']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $pincode = sanitizeInput($_POST['pincode']);
    $contact = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);

    $sql = "UPDATE hostel SET hostel_name = ?, address = ?, city = ?, state = ?, pincode = ?, contact_number = ?, email = ? WHERE hostel_id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $address, $city, $state, $pincode, $contact, $email);
    
    if ($stmt->execute()) {
        $success = "Hostel settings updated successfully!";
    } else {
        $error = "Failed to update settings.";
    }
}

// Fetch current hostel info
$hostel = getHostelInfo();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hostel Settings - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-cog me-2"></i>Hostel Configuration</h2>
                
                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hostel Name</label>
                                    <input type="text" name="hostel_name" class="form-control" value="<?php echo htmlspecialchars($hostel['hostel_name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Official Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($hostel['email']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Full Address</label>
                                <textarea name="address" class="form-control" rows="2" required><?php echo htmlspecialchars($hostel['address']); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($hostel['city']); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($hostel['state']); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control" value="<?php echo htmlspecialchars($hostel['pincode']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($hostel['contact_number']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Room Count (System Calculated)</label>
                                    <input type="text" class="form-control" value="<?php echo $hostel['total_rooms']; ?>" disabled>
                                </div>
                            </div>
                            
                            <div class="text-end mt-3">
                                <button type="submit" name="update_settings" class="btn btn-primary px-5">
                                    <i class="fas fa-save me-2"></i>Update Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>