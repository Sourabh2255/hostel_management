<?php
// pages/student_view_profile.php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Ensure only logged-in students can access this page
requireRole('student');

$student_id = getCurrentUserId();
$conn = getDBConnection();
$success = '';
$error = '';

// Handle Profile Update for contact info and photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);

    // 1. PRESERVE PHOTO LOGIC: Start with the existing photo filename from the hidden field
    $photo_name = $_POST['current_photo'];

    // 2. UPLOAD LOGIC: Only update $photo_name if a NEW file is actually selected and uploaded
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $upload = uploadFile($_FILES['profile_photo'], 'students/photos', ['jpg', 'jpeg', 'png']);
        if ($upload['success']) {
            $photo_name = $upload['filename'];
        } else {
            $error = $upload['message'];
        }
    }

    if (empty($error)) {
        // 3. DATABASE UPDATE: Uses $photo_name (either the old one or the new one)
        $update_sql = "UPDATE student SET email = ?, phone = ?, profile_photo = ? WHERE student_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $email, $phone, $photo_name, $student_id);

        if ($stmt->execute()) {
            $success = "Profile details updated successfully!";
            $_SESSION['email'] = $email;
        } else {
            $error = "Update failed. Email might already be in use.";
        }
    }
}

// Fetch complete student details including room info
$sql = "SELECT s.*, r.room_number, r.room_type, r.floor_number, ra.allocation_date 
        FROM student s 
        LEFT JOIN room_allocation ra ON s.student_id = ra.student_id AND ra.allocation_status = 'active'
        LEFT JOIN room r ON ra.room_id = r.room_id
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Photo Path Logic for display
$photo_path = "../assets/img/default-user.png"; // Default fallback
if (!empty($student['profile_photo'])) {
    $target_file = "../uploads/students/photos/" . $student['profile_photo'];
    if (file_exists($target_file)) {
        $photo_path = $target_file;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Hostel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/student_sidebar.php'; ?>

            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card custom-card text-center p-3 shadow-sm">
                            <div class="mb-3 position-relative">
                                <img src="<?php echo $photo_path; ?>" class="rounded-circle img-thumbnail shadow" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <h4 class="mb-1"><?php echo htmlspecialchars($student['full_name']); ?></h4>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($student['course_name']); ?></p>
                            <div class="mb-3">
                                <span class="badge bg-success">Status: <?php echo ucfirst($student['student_status']); ?></span>
                            </div>

                            </div>

                        <div class="card custom-card mt-4 shadow-sm">
                            <div class="card-header bg-light"><i class="fas fa-bed me-2 text-primary"></i>Hostel Allocation</div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="small text-muted">Room Number:</span>
                                        <span class="fw-bold"><?php echo $student['room_number'] ?? 'Not Allocated'; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="small text-muted">Room Type:</span>
                                        <span class="badge bg-info text-dark"><?php echo ucfirst($student['room_type'] ?? 'N/A'); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="small text-muted">Floor:</span>
                                        <span><?php echo $student['floor_number'] ?? 'N/A'; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="small text-muted">Allocated On:</span>
                                        <span class="small"><?php echo !empty($student['allocation_date']) ? formatDate($student['allocation_date']) : 'N/A'; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card custom-card shadow-sm">
                            <div class="card-header bg-white"><i class="fas fa-id-card me-2 text-primary"></i>Full Profile Information</div>
                            <div class="card-body">
                                <?php if ($success) echo "<div class='alert alert-success alert-dismissible fade show'>$success<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
                                <?php if ($error) echo "<div class='alert alert-danger alert-dismissible fade show'>$error<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($student['profile_photo'] ?? ''); ?>">

                                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-graduation-cap me-2"></i>Academic Details</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted small d-block">Institution</label>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($student['institution_name']); ?></span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted small d-block">Year of Study</label>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($student['year_of_study']); ?></span>
                                        </div>
                                    </div>

                                    <h6 class="text-primary border-bottom pb-2 mt-2"><i class="fas fa-edit me-2"></i>Update Contact & Photo</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">Email Address</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label small">Update Profile Picture</label>
                                            <input type="file" name="profile_photo" class="form-control">
                                            <small class="text-muted">Leave empty to keep your current photo.</small>
                                        </div>
                                    </div>

                                    <h6 class="text-primary border-bottom pb-2 mt-2"><i class="fas fa-map-marker-alt me-2"></i>Permanent Address</h6>
                                    <div class="mb-4 bg-light p-3 rounded">
                                        <p class="mb-0 small">
                                            <?php echo nl2br(htmlspecialchars($student['address'])); ?><br>
                                            <strong><?php echo htmlspecialchars($student['city']); ?>, <?php echo htmlspecialchars($student['state']); ?> - <?php echo htmlspecialchars($student['pincode']); ?></strong>
                                        </p>
                                    </div>

                                    <h6 class="text-primary border-bottom pb-2 mt-2"><i class="fas fa-users me-2"></i>Guardian Information</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted small d-block">Guardian Name</label>
                                            <span><?php echo htmlspecialchars($student['guardian_name']); ?> (<?php echo htmlspecialchars($student['guardian_relation']); ?>)</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted small d-block">Guardian Contact</label>
                                            <span><?php echo htmlspecialchars($student['guardian_phone']); ?></span>
                                        </div>
                                    </div>

                                    <div class="text-end border-top pt-3">
                                        <button type="submit" name="update_profile" class="btn btn-primary px-4 shadow-sm">
                                            <i class="fas fa-save me-2"></i>Update Profile Details
                                        </button>
                                    </div>
                                </form>
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