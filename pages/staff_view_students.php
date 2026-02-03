<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('staff');

$conn = getDBConnection();
// Query to get active students with their room information
$sql = "SELECT s.*, r.room_number 
        FROM student s 
        LEFT JOIN room_allocation ra ON s.student_id = ra.student_id AND ra.allocation_status = 'active'
        LEFT JOIN room r ON ra.room_id = r.room_id
        WHERE s.student_status = 'active' 
        ORDER BY s.admission_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-users me-2"></i>All Active Students</h2>
                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Room</th>
                                        <th>Course</th>
                                        <th>Phone</th>
                                        <th>Admission Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                                            <td><?php echo !empty($row['room_number']) ? 'Room ' . htmlspecialchars($row['room_number']) : '<span class="text-danger">Not Allocated</span>'; ?></td>
                                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo formatDate($row['admission_date']); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-5">No active students found.</td></tr>
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
<?php closeDBConnection($conn); ?>