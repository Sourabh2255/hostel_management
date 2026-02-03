<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Student role
requireRole('student');

$student_id = getCurrentUserId();
$conn = getDBConnection();

// Fetch only payment records belonging to the logged-in student
$sql = "SELECT * FROM payment 
        WHERE student_id = ? 
        ORDER BY payment_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Payment History - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/student_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-invoice-dollar me-2 text-success"></i>My Payment History</h2>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo formatDate($row['payment_date']); ?></td>
                                            <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $row['payment_type']))); ?></td>
                                            <td><strong><?php echo formatCurrency($row['amount']); ?></strong></td>
                                            <td><?php echo htmlspecialchars(ucfirst($row['payment_method'] ?? $row['payment_mode'] ?? 'N/A')); ?></td>
                                            <td><code><?php echo htmlspecialchars($row['transaction_id']); ?></code></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['payment_status'] == 'completed' || $row['payment_status'] == 'paid') ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($row['payment_status'] ?? 'Paid'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-receipt fa-3x mb-3 text-muted"></i>
                                                    <h5>No Payment Records Found</h5>
                                                    <p>You haven't made any payments yet.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
$stmt->close();
closeDBConnection($conn); 
?>