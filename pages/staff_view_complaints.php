<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('staff');

$conn = getDBConnection();
$msg = '';

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $new_status = sanitizeInput($_POST['status']);
    
    // CHANGED: Column name is 'complaint_status'
    $stmt = $conn->prepare("UPDATE complaint SET complaint_status = ?, resolved_date = IF(?='resolved', NOW(), NULL) WHERE complaint_id = ?");
    $stmt->bind_param("ssi", $new_status, $new_status, $complaint_id);
    
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Complaint status updated successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error updating status: " . $conn->error . "</div>";
    }
}

// Fetch all complaints
// CHANGED: Used 'complaint_status' in the ORDER BY clause
$sql = "SELECT c.*, s.full_name 
        FROM complaint c 
        JOIN student s ON c.student_id = s.student_id 
        ORDER BY CASE WHEN c.complaint_status = 'pending' THEN 1 ELSE 2 END, c.complaint_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Complaints - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-tools me-2 text-danger"></i>Student Complaints</h2>
                <?php echo $msg; ?>

                <div class="row mt-4">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 mb-4">
            <div class="card custom-card border-start border-4 border-<?php echo ($row['complaint_status'] == 'pending') ? 'danger' : 'success'; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($row['complaint_title']); ?></h5>
                        <span class="badge bg-<?php echo ($row['complaint_status'] == 'pending') ? 'danger' : 'success'; ?>">
                            <?php echo ucfirst($row['complaint_status']); ?>
                        </span>
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($row['full_name']); ?> | 
                        <i class="fas fa-calendar me-1"></i> <?php echo formatDate($row['complaint_date']); ?>
                    </p>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['complaint_description'])); ?></p>
                    <hr>
                    
                    <form method="POST" class="row g-2">
                        <input type="hidden" name="complaint_id" value="<?php echo $row['complaint_id']; ?>">
                        <div class="col-8">
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" <?php if($row['complaint_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="in_progress" <?php if($row['complaint_status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                                <option value="resolved" <?php if($row['complaint_status'] == 'resolved') echo 'selected'; ?>>Resolved</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-clipboard-check fa-4x text-muted mb-3"></i>
                            <p class="lead text-muted">No student complaints found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>