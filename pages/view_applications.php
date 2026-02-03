<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

// 1. Establish connection early
$conn = getDBConnection();

// 2. Process Approval or Rejection logic
if (isset($_POST['action']) && isset($_POST['app_id'])) {
    $app_id = intval($_POST['app_id']);
    $status = $_POST['action'] == 'approve' ? 'approved' : 'rejected';
    $admin_id = getCurrentUserId();
    
    $stmt = $conn->prepare("UPDATE student_application SET application_status = ?, processed_by = ?, processed_date = NOW() WHERE application_id = ?");
    $stmt->bind_param("sii", $status, $admin_id, $app_id);
    $stmt->execute();
    header("Location: view_applications.php?msg=" . $status);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hostel Applications - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // 3. Include sidebar (which might reset the connection state)
            include '../includes/admin_sidebar.php'; 
            
            // 4. RE-RUN THE QUERY after sidebar to ensure data is fetched
            $sql = "SELECT * FROM student_application WHERE application_status = 'pending' ORDER BY applied_date DESC";
            $result = $conn->query($sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-file-alt me-2"></i>Pending Applications</h2>
                <div class="row mt-4">
                    <?php 
                    // 5. Check if the query returned any results
                    if ($result && $result->num_rows > 0): 
                        while($app = $result->fetch_assoc()): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card">
                                <div class="card-header d-flex justify-content-between">
                                    <span><?php echo htmlspecialchars($app['full_name']); ?></span>
                                    <small><?php echo formatDate($app['applied_date']); ?></small>
                                </div>
                                <div class="card-body">
                                    <p><strong>Course:</strong> <?php echo htmlspecialchars($app['course_name']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email']); ?></p>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone']); ?></p>
                                    <hr>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="app_id" value="<?php echo $app['application_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm w-50">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm w-50">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-muted mb-3"></i>
                            <p class="lead text-muted">No pending applications at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
// 6. Close connection at the very end
closeDBConnection($conn); 
?>