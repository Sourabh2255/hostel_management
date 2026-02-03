<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();

// Handle deletion/archiving trigger
if (isset($_GET['archive_id'])) {
    $student_id = intval($_GET['archive_id']);
    $admin_id = getCurrentUserId();
    // Using the stored procedure defined in schema.sql
    $stmt = $conn->prepare("CALL sp_archive_student(?, 'Moved to archive by admin', ?)");
    $stmt->bind_param("ii", $student_id, $admin_id);
    $stmt->execute();
    header("Location: view_students.php?msg=archived");
    exit();
}

// UPDATED: Use the view to get room information
$sql = "SELECT * FROM vw_student_details WHERE student_status = 'active' ORDER BY admission_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Students - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users me-2"></i>Active Students</h2>
                    <a href="add_student.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Student
                    </a>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Room</th>
                                        <th>Course</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php 
    // Optimized query to get room info along with student data
    $sql = "SELECT s.*, r.room_number 
            FROM student s 
            LEFT JOIN room_allocation ra ON s.student_id = ra.student_id AND ra.allocation_status = 'active'
            LEFT JOIN room r ON ra.room_id = r.room_id
            WHERE s.student_status = 'active' 
            ORDER BY s.admission_date DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0): 
        while($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                <small class="text-muted">ID: <?php echo $row['student_id']; ?></small>
            </td>
            <td>
                <?php echo !empty($row['room_number']) ? 'Room ' . htmlspecialchars($row['room_number']) : '<span class="text-danger">Unallocated</span>'; ?>
            </td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td>
                <a href="student_details.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-info text-white">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="view_students.php?archive_id=<?php echo $row['student_id']; ?>" 
                   class="btn btn-sm btn-warning" 
                   onclick="return confirm('Archive this student?')">
                    <i class="fas fa-archive"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; 
    else: ?>
        <tr>
            <td colspan="4" class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                    <h5>No Active Students Found</h5>
                    <p>Add a student or approve an application to see them here.</p>
                </div>
            </td>
        </tr>
    <?php endif; ?>
</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>