<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

// 1. Establish connection early
$conn = getDBConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payments - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // 2. Include sidebar (which might use $conn internally)
            include '../includes/admin_sidebar.php'; 
            
            // 3. RE-ESTABLISH/REFRESH QUERY after sidebar to ensure data is fetched correctly
            // This follows the exact logic used to fix the room management display
            $sql = "SELECT p.*, s.full_name 
                    FROM payment p 
                    JOIN student s ON p.student_id = s.student_id 
                    ORDER BY p.payment_date DESC";
            $result = $conn->query($sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-money-bill-wave me-2"></i>Transaction History</h2>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Mode</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // 4. Verify result exists before looping
                                    if ($result && $result->num_rows > 0): 
                                        while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo formatDate($row['payment_date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo str_replace('_', ' ', ucfirst($row['payment_type'])); ?></span></td>
                                            <td><strong><?php echo formatCurrency($row['amount']); ?></strong></td>
                                            <td><?php echo ucfirst($row['payment_mode']); ?></td>
                                            <td><code><?php echo htmlspecialchars($row['transaction_id'] ?? 'N/A'); ?></code></td>
                                            <td><span class="badge bg-<?php echo getStatusBadge($row['payment_status']); ?>"><?php echo ucfirst($row['payment_status']); ?></span></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-search-dollar fa-3x mb-3 text-muted"></i>
                                                    <h5>No Transactions Found</h5>
                                                    <p>Check the 'payment' table in your database.</p>
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
<?php 
// 5. Always close connection at the very end
closeDBConnection($conn); 
?>