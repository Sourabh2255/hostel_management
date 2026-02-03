<?php
// Admin Dashboard
// Smart Hostel Management System

require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
requireRole('admin');

$admin_name = getCurrentUserName();
$counts = getDashboardCounts();
$hostel_info = getHostelInfo();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Hostel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../includes/admin_sidebar.php'; ?>
            
            <!-- Main Content -->
            <div class="col-md-10 ms-sm-auto px-md-4">
                <!-- Header -->
                <div class="dashboard-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>
                                <p class="mb-0">Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-0"><i class="far fa-calendar me-2"></i><?php echo date('l, F d, Y'); ?></p>
                                <p class="mb-0"><i class="far fa-clock me-2"></i><?php echo date('h:i A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="container-fluid mb-4">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-primary text-white">
                                <i class="fas fa-users"></i>
                                <h3><?php echo $counts['total_students']; ?></h3>
                                <p>Total Students</p>
                                <a href="view_students.php" class="btn btn-light btn-sm mt-2">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-success text-white">
                                <i class="fas fa-user-tie"></i>
                                <h3><?php echo $counts['total_staff']; ?></h3>
                                <p>Total Staff</p>
                                <a href="manage_staff.php" class="btn btn-light btn-sm mt-2">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-warning text-white">
                                <i class="fas fa-door-open"></i>
                                <h3><?php echo $counts['available_rooms']; ?>/<?php echo $counts['total_rooms']; ?></h3>
                                <p>Available Rooms</p>
                                <a href="manage_rooms.php" class="btn btn-light btn-sm mt-2">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-info text-white">
                                <i class="fas fa-rupee-sign"></i>
                                <h3><?php echo formatCurrency($counts['monthly_revenue']); ?></h3>
                                <p>This Month Revenue</p>
                                <a href="payment_reports.php" class="btn btn-light btn-sm mt-2">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-danger text-white">
                                <i class="fas fa-file-alt"></i>
                                <h3><?php echo $counts['pending_applications']; ?></h3>
                                <p>Pending Applications</p>
                                <a href="view_applications.php" class="btn btn-light btn-sm mt-2">
                                    Review Now <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card bg-secondary text-white">
                                <i class="fas fa-tools"></i>
                                <h3><?php echo $counts['pending_complaints']; ?></h3>
                                <p>Pending Complaints</p>
                                <a href="view_complaints.php" class="btn btn-light btn-sm mt-2">
                                    View All <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="container-fluid">
                    <div class="row">
                        <!-- Recent Applications -->
                        <div class="col-lg-6 mb-4">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <i class="fas fa-file-alt me-2"></i>Recent Applications
                                </div>
                                <div class="card-body">
                                    <?php
                                    $conn = getDBConnection();
                                    $sql = "SELECT * FROM student_application WHERE application_status = 'pending' ORDER BY applied_date DESC LIMIT 5";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0):
                                    ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($app = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($app['course_name']); ?></td>
                                                        <td><?php echo formatDate($app['applied_date']); ?></td>
                                                        <td>
                                                            <a href="view_applications.php?id=<?php echo $app['application_id']; ?>" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h5>No Pending Applications</h5>
                                            <p>All applications have been processed</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Complaints -->
                        <div class="col-lg-6 mb-4">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <i class="fas fa-tools me-2"></i>Recent Complaints
                                </div>
                                <div class="card-body">
                                    <?php
                                    $sql = "SELECT c.*, s.full_name, r.room_number 
                                            FROM complaint c 
                                            JOIN student s ON c.student_id = s.student_id 
                                            JOIN room r ON c.room_id = r.room_id 
                                            WHERE c.complaint_status IN ('pending', 'in_progress') 
                                            ORDER BY c.complaint_date DESC LIMIT 5";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0):
                                    ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Room</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($complaint = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($complaint['full_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($complaint['room_number']); ?></td>
                                                        <td><span class="badge bg-info"><?php echo ucfirst($complaint['complaint_type']); ?></span></td>
                                                        <td><span class="badge bg-<?php echo getStatusBadge($complaint['complaint_status']); ?>">
                                                            <?php echo ucfirst($complaint['complaint_status']); ?>
                                                        </span></td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle"></i>
                                            <h5>No Pending Complaints</h5>
                                            <p>All complaints have been resolved</p>
                                        </div>
                                    <?php endif; 
                                    closeDBConnection($conn);
                                    ?>
                                </div>
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
                                    <a href="add_staff.php" class="btn btn-outline-primary w-100 p-3">
                                        <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                                        Add New Staff
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="add_room.php" class="btn btn-outline-success w-100 p-3">
                                        <i class="fas fa-door-open fa-2x d-block mb-2"></i>
                                        Add New Room
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="view_applications.php" class="btn btn-outline-warning w-100 p-3">
                                        <i class="fas fa-file-alt fa-2x d-block mb-2"></i>
                                        Review Applications
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="reports.php" class="btn btn-outline-info w-100 p-3">
                                        <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                                        View Reports
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
