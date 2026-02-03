<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Staff role
requireRole('staff');

$conn = getDBConnection();

// Fetch all payment records with student names
$sql = "SELECT p.*, s.full_name 
        FROM payment p 
        JOIN student s ON p.student_id = s.student_id 
        ORDER BY p.payment_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payments - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-money-bill-wave me-2 text-warning"></i>Payment Records</h2>
                    <a href="staff_record_payment.php" class="btn btn-warning">
                        <i class="fas fa-plus-circle me-2"></i>Record New Payment
                    </a>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student Name</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Reference No.</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo formatDate($row['payment_date']); ?></td>
                                            <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                                            <td><?php echo formatCurrency($row['amount']); ?></td>
                                            <td>
    <td>
    <?php 
        // Checks 'payment_method' first, then 'payment_mode' as a backup
        $method = $row['payment_method'] ?? $row['payment_mode'] ?? 'Not Specified'; 
        echo htmlspecialchars(ucfirst($method)); 
    ?>
</td>
                                            <td><code><?php echo htmlspecialchars($row['transaction_id']); ?></code></td>
                                            <td><span class="badge bg-success">Paid</span></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center py-5">No payment records found.</td></tr>
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