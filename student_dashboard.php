<?php
// Student Dashboard
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('student');

$student_id = getCurrentUserId();
$student_name = getCurrentUserName();
$conn = getDBConnection();

// Get student details with room allocation
$sql = "SELECT s.*, r.room_number, r.room_type, r.floor_number, r.monthly_rent,
        ra.allocation_date
        FROM student s
        LEFT JOIN room_allocation ra ON s.student_id = ra.student_id AND ra.allocation_status = 'active'
        LEFT JOIN room r ON ra.room_id = r.room_id
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Get payment summary
$sql = "SELECT 
        COUNT(*) as total_payments,
        SUM(amount) as total_paid,
        MAX(payment_date) as last_payment_date
        FROM payment 
        WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$payment_summary = $stmt->get_result()->fetch_assoc();

// Get complaints count
$sql = "SELECT COUNT(*) as total_complaints FROM complaint WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$complaints_count = $stmt->get_result()->fetch_assoc()['total_complaints'];

closeDBConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Smart Hostel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../includes/student_sidebar.php'; ?>
            
            <!-- Main Content -->
            <div class="col-md-10 ms-sm-auto px-md-4">
                <div class="dashboard-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2><i class="fas fa-tachometer-alt me-2"></i>Student Dashboard</h2>
                                <p class="mb-0">Welcome, <?php echo htmlspecialchars($student_name); ?>!</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-0"><i class="far fa-calendar me-2"></i><?php echo date('l, F d, Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Profile and Room Info -->
                <div class="container-fluid mb-4">
                    <div class="row">
                        <!-- Profile Card -->
                        <div class="col-md-4 mb-4">
                            <div class="profile-card">
                                <div class="text-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px; font-size: 3rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <h4><?php echo htmlspecialchars($student['full_name']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($student['course_name']); ?></p>
                                <hr>
                                <div class="text-start">
                                    <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i><?php echo htmlspecialchars($student['email']); ?></p>
                                    <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i><?php echo htmlspecialchars($student['phone']); ?></p>
                                    <p class="mb-2"><i class="fas fa-calendar me-2 text-primary"></i>Age: <?php echo calculateAge($student['date_of_birth']); ?> years</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Room Information -->
                        <div class="col-md-8 mb-4">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <i class="fas fa-door-open me-2"></i>Room Information
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($student['room_number'])): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-muted small">Room Number</label>
                                                    <h4 class="mb-0"><?php echo htmlspecialchars($student['room_number']); ?></h4>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-muted small">Room Type</label>
                                                    <p class="mb-0"><span class="badge bg-<?php echo getRoomTypeBadge($student['room_type']); ?>"><?php echo ucfirst($student['room_type']); ?></span></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-muted small">Floor Number</label>
                                                    <h4 class="mb-0"><?php echo $student['floor_number']; ?></h4>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-muted small">Monthly Rent</label>
                                                    <h4 class="mb-0 text-success"><?php echo formatCurrency($student['monthly_rent']); ?></h4>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-info mb-0">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Allocated on: <?php echo formatDate($student['allocation_date']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            <i class="fas fa-bed"></i>
                                            <h5>No Room Allocated</h5>
                                            <p>Please contact the hostel administration</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="container-fluid mb-4">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="stat-card bg-success text-white">
                                <i class="fas fa-money-bill-wave"></i>
                                <h3><?php echo formatCurrency($payment_summary['total_paid'] ?? 0); ?></h3>
                                <p>Total Payments Made</p>
                                <small>Payments: <?php echo $payment_summary['total_payments'] ?? 0; ?></small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="stat-card bg-warning text-white">
                                <i class="fas fa-tools"></i>
                                <h3><?php echo $complaints_count; ?></h3>
                                <p>Total Complaints</p>
                                <a href="student_view_complaints.php" class="btn btn-light btn-sm mt-2">View All</a>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="stat-card bg-info text-white">
                                <i class="fas fa-calendar"></i>
                                <h3><?php echo $payment_summary['last_payment_date'] ? formatDate($payment_summary['last_payment_date']) : 'N/A'; ?></h3>
                                <p>Last Payment Date</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="container-fluid mb-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <a href="student_view_profile.php" class="btn btn-outline-primary w-100 p-3">
                                        <i class="fas fa-user fa-2x d-block mb-2"></i>
                                        View My Profile
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="student_raise_complaint.php" class="btn btn-outline-danger w-100 p-3">
                                        <i class="fas fa-exclamation-circle fa-2x d-block mb-2"></i>
                                        Raise Complaint
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="student_view_payments.php" class="btn btn-outline-success w-100 p-3">
                                        <i class="fas fa-file-invoice fa-2x d-block mb-2"></i>
                                        View Payment History
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
