<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Complaints - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            include '../includes/admin_sidebar.php'; 
            
            // Execute query after sidebar to ensure fresh connection
            $sql = "SELECT * FROM vw_complaint_report ORDER BY complaint_date DESC";
            $result = $conn->query($sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-tools me-2"></i>Maintenance Complaints</h2>

                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student (Room)</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Pending Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo formatDate($row['complaint_date']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($row['student_name']); ?><br>
                                                <small class="text-muted">Room: <?php echo htmlspecialchars($row['room_number']); ?></small>
                                            </td>
                                            <td><span class="badge bg-info"><?php echo ucfirst($row['complaint_type']); ?></span></td>
                                            <td><?php echo htmlspecialchars($row['complaint_title']); ?></td>
                                            <td><span class="badge bg-<?php echo ($row['priority'] == 'high' || $row['priority'] == 'urgent') ? 'danger' : 'secondary'; ?>"><?php echo ucfirst($row['priority']); ?></span></td>
                                            <td><span class="badge bg-<?php echo getStatusBadge($row['complaint_status']); ?>"><?php echo ucfirst($row['complaint_status']); ?></span></td>
                                            <td><?php echo $row['days_pending']; ?> Days</td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center py-4">No complaints recorded.</td></tr>
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