<?php
// Staff Dashboard
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('staff');

$staff_name = getCurrentUserName();
$counts = getDashboardCounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Smart Hostel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <!-- Main Content -->
            <div class="col-md-10 ms-sm-auto px-md-4">
                <div class="dashboard-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2><i class="fas fa-tachometer-alt me-2"></i>Staff Dashboard</h2>
                                <p class="mb-0">Welcome, <?php echo htmlspecialchars($staff_name); ?>!</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-0"><i class="far fa-calendar me-2"></i><?php echo date('l, F d, Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="container-fluid mb-4">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-primary text-white">
                                <i class="fas fa-users"></i>
                                <h3><?php echo $counts['total_students']; ?></h3>
                                <p>Active Students</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-success text-white">
                                <i class="fas fa-door-open"></i>
                                <h3><?php echo $counts['available_rooms']; ?></h3>
                                <p>Available Rooms</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-warning text-white">
                                <i class="fas fa-file-alt"></i>
                                <h3><?php echo $counts['pending_applications']; ?></h3>
                                <p>Pending Applications</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-danger text-white">
                                <i class="fas fa-tools"></i>
                                <h3><?php echo $counts['pending_complaints']; ?></h3>
                                <p>Pending Complaints</p>
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
                                <div class="col-md-3 mb-3">
                                    <a href="staff_add_student.php" class="btn btn-outline-primary w-100 p-3">
                                        <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                                        Add New Student
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="staff_allocate_room.php" class="btn btn-outline-success w-100 p-3">
                                        <i class="fas fa-bed fa-2x d-block mb-2"></i>
                                        Allocate Room
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="staff_record_payment.php" class="btn btn-outline-warning w-100 p-3">
                                        <i class="fas fa-money-bill fa-2x d-block mb-2"></i>
                                        Record Payment
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="staff_view_applications.php" class="btn btn-outline-info w-100 p-3">
                                        <i class="fas fa-file-alt fa-2x d-block mb-2"></i>
                                        View Applications
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
