<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Staff role
requireRole('staff');

$conn = getDBConnection();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['record_payment'])) {
    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);
    $method = sanitizeInput($_POST['payment_method']);
    $txn_id = sanitizeInput($_POST['transaction_id']);
    $staff_id = getCurrentUserId();

    $sql = "INSERT INTO payment (student_id, amount, payment_date, payment_method, transaction_id, status) 
            VALUES (?, ?, CURDATE(), ?, ?, 'completed')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $student_id, $amount, $method, $txn_id);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Payment recorded successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: Could not record payment.</div>";
    }
}

// Fetch active students for the dropdown
$students = $conn->query("SELECT student_id, full_name FROM student WHERE student_status = 'active' ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Payment - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-cash-register me-2 text-warning"></i>Record Student Payment</h2>
                
                <div class="col-md-6 mt-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <?php echo $msg; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Select Student</label>
                                    <select name="student_id" class="form-select" required>
                                        <option value="">-- Choose Student --</option>
                                        <?php while($s = $students->fetch_assoc()): ?>
                                            <option value="<?php echo $s['student_id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount Paid</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="cash">Cash</option>
                                        <option value="upi">UPI / Online</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Transaction ID / Receipt No.</label>
                                    <input type="text" name="transaction_id" class="form-control" placeholder="Optional for cash">
                                </div>
                                <button type="submit" name="record_payment" class="btn btn-warning w-100">
                                    <i class="fas fa-save me-2"></i>Save Payment Record
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>