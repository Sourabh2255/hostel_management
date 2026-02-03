<?php
// pages/manage_staff.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('admin');

$conn = getDBConnection();

// Status Toggle Logic
if (isset($_GET['id']) && isset($_GET['status'])) {
    $staff_id = intval($_GET['id']);
    $new_status = ($_GET['status'] == 'active') ? 'inactive' : 'active';
    
    $stmt = $conn->prepare("UPDATE staff SET status = ? WHERE staff_id = ?");
    $stmt->bind_param("si", $new_status, $staff_id);
    $stmt->execute();
    header("Location: manage_staff.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4">
                <div class="dashboard-header">
                    <h2><i class="fas fa-users-cog me-2"></i>Staff Management</h2>
                </div>

                <?php
                // Run query AFTER sidebar to ensure $conn is fresh and open
                $sql = "SELECT * FROM staff ORDER BY staff_id ASC";
                $result = $conn->query($sql);
                $total_staff = ($result) ? $result->num_rows : 0;
                ?>

                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Total Registered Staff: <?php echo $total_staff; ?></span>
                        <a href="add_staff.php" class="btn btn-primary btn-sm">Add New Staff</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NAME</th>
                                        <th>USERNAME</th>
                                        <th>DESIGNATION</th>
                                        <th>EMAIL/PHONE</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($total_staff > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['staff_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                <td><code><?php echo htmlspecialchars($row['username']); ?></code></td>
                                                <td><?php echo htmlspecialchars($row['designation'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <small>
                                                        <?php echo htmlspecialchars($row['email']); ?><br>
                                                        <?php echo htmlspecialchars($row['phone']); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo getStatusBadge($row['status']); ?>">
                                                        <?php echo ucfirst($row['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="manage_staff.php?id=<?php echo $row['staff_id']; ?>&status=<?php echo $row['status']; ?>" 
                                                       class="btn btn-sm btn-outline-secondary">Toggle Status</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-users-slash fa-3x mb-3"></i>
                                                    <h5>No Records Found</h5>
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
<?php closeDBConnection($conn); ?>