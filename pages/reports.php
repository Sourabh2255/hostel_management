<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
$counts = getDashboardCounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Reports - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            include '../includes/admin_sidebar.php'; 
            
            // Room Occupancy Query
            $room_sql = "SELECT * FROM vw_room_occupancy ORDER BY occupancy_percentage DESC";
            $room_result = $conn->query($room_sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-chart-bar me-2"></i>System-wide Reports</h2>
                    <button onclick="window.print()" class="btn btn-outline-primary no-print">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card p-3 text-center shadow-sm">
                            <h6 class="text-muted">Capacity Utilization</h6>
                            <h4 class="text-primary"><?php echo round(($counts['total_students'] / ($counts['total_rooms'] * 2)) * 100, 1); ?>%</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center shadow-sm">
                            <h6 class="text-muted">Total Active Students</h6>
                            <h4 class="text-success"><?php echo $counts['total_students']; ?></h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center shadow-sm">
                            <h6 class="text-muted">Unresolved Complaints</h6>
                            <h4 class="text-danger"><?php echo $counts['pending_complaints']; ?></h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center shadow-sm">
                            <h6 class="text-muted">Monthly Revenue</h6>
                            <h4 class="text-info"><?php echo formatCurrency($counts['monthly_revenue']); ?></h4>
                        </div>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-header">Detailed Room Occupancy Report</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Room No.</th>
                                        <th>Type</th>
                                        <th>Total Capacity</th>
                                        <th>Current Occupancy</th>
                                        <th>Available Beds</th>
                                        <th>Usage %</th>
                                        <th>Rent/Bed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($room = $room_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($room['room_number']); ?></strong></td>
                                        <td><?php echo ucfirst($room['room_type']); ?></td>
                                        <td><?php echo $room['capacity']; ?></td>
                                        <td><?php echo $room['current_occupancy']; ?></td>
                                        <td><?php echo $room['available_beds']; ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-<?php echo ($room['occupancy_percentage'] > 80) ? 'danger' : 'success'; ?>" 
                                                     role="progressbar" style="width: <?php echo $room['occupancy_percentage']; ?>%">
                                                    <?php echo $room['occupancy_percentage']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo formatCurrency($room['monthly_rent']); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
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