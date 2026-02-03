<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
$sql = "SELECT * FROM archived_student ORDER BY archived_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Students - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-archive me-2"></i>Archived Students</h2>
                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Admission Date</th>
                                    <th>Leaving Date</th>
                                    <th>Reason</th>
                                    <th>Archived By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo formatDate($row['admission_date']); ?></td>
                                    <td><?php echo formatDate($row['leaving_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['leaving_reason']); ?></td>
                                    <td>Staff ID: <?php echo $row['archived_by']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>