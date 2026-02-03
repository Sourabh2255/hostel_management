<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Staff role only
requireRole('staff');

$conn = getDBConnection();
$msg = '';

// Handle Application Processing
if (isset($_POST['action']) && isset($_POST['app_id'])) {
    $app_id = intval($_POST['app_id']);
    $status = $_POST['action'] == 'approve' ? 'approved' : 'rejected';
    $staff_id = getCurrentUserId();
    
    // Update application status
    $stmt = $conn->prepare("UPDATE student_application SET application_status = ?, processed_by = NULL, processed_date = NOW(), remarks = 'Processed by Staff' WHERE application_id = ?");
    $stmt->bind_param("si", $status, $app_id);
    
    if ($stmt->execute()) {
        $msg = "Application " . ucfirst($status) . " successfully!";
    }
}

// Fetch pending applications
$sql = "SELECT * FROM student_application WHERE application_status = 'pending' ORDER BY applied_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Applications - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-file-signature me-2"></i>Review Hostel Applications</h2>
                
                <?php if($msg): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($app = $result->fetch_assoc()): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card">
                                <div class="card-header d-flex justify-content-between">
                                    <span><?php echo htmlspecialchars($app['full_name']); ?></span>
                                    <small><?php echo formatDate($app['applied_date']); ?></small>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Course:</strong> <?php echo htmlspecialchars($app['course_name']); ?></p>
                                            <p><strong>Gender:</strong> <?php echo ucfirst($app['gender']); ?></p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone']); ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="app_id" value="<?php echo $app['application_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm w-50">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm w-50">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-clipboard-check fa-4x text-muted mb-3"></i>
                            <p class="lead text-muted">No pending hostel applications to review.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>