<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

// 1. Establish database connection
$conn = getDBConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Reports - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // 2. Include sidebar first
            include '../includes/admin_sidebar.php'; 

            // 3. EXECUTE QUERIES AFTER SIDEBAR
            // Calculate total revenue for the top card
            $total_res = $conn->query("SELECT SUM(amount) as grand_total FROM payment");
            $grand_total = $total_res->fetch_assoc()['grand_total'] ?? 0;

            // Fetch summary data from the database view
            $sql = "SELECT * FROM vw_payment_summary ORDER BY total_amount_paid DESC";
            $result = $conn->query($sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-file-invoice-dollar me-2"></i>Financial Reports</h2>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-success text-white">
                            <i class="fas fa-vault"></i>
                            <h3><?php echo formatCurrency($grand_total); ?></h3>
                            <p class="text-white">Total Hostel Revenue</p>
                        </div>
                    </div>
                </div>

                <div class="card custom-card mt-4">
                    <div class="card-header">Student-wise Payment Summary</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Total Payments</th>
                                        <th>Total Amount Paid</th>
                                        <th>Last Transaction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // 4. Verify result and display table rows
                                    if ($result && $result->num_rows > 0): 
                                        while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo $row['total_payments']; ?></td>
                                            <td><strong><?php echo formatCurrency($row['total_amount_paid'] ?? 0); ?></strong></td>
                                            <td><?php echo !empty($row['last_payment_date']) ? formatDate($row['last_payment_date']) : 'N/A'; ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-chart-pie fa-3x mb-3 text-muted"></i>
                                                    <h5>No Data Available</h5>
                                                    <p>Either no payments have been recorded yet, or the database view 'vw_payment_summary' is missing.</p>
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
// 5. Close connection at the very end
closeDBConnection($conn); 
?>