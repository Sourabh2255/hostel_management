<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Staff role
requireRole('staff');

$conn = getDBConnection();

// Fetch room inventory with occupancy calculations
$sql = "SELECT *, 
        CASE 
            WHEN capacity > 0 THEN ROUND((current_occupancy / capacity) * 100, 2) 
            ELSE 0 
        END as occupancy_percentage 
        FROM room 
        ORDER BY floor_number ASC, room_number ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Inventory - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-door-open me-2 text-primary"></i>Room Inventory</h2>
                
                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th>Room No.</th>
                                        <th>Type</th>
                                        <th>Floor</th>
                                        <th>Occupancy</th>
                                        <th>Status</th>
                                        <th>Monthly Rent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['room_number']); ?></strong></td>
                                            <td><span class="badge bg-info"><?php echo ucfirst($row['room_type']); ?></span></td>
                                            <td><?php echo $row['floor_number']; ?></td>
                                            <td>
                                                <?php echo $row['current_occupancy']; ?> / <?php echo $row['capacity']; ?>
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $row['occupancy_percentage']; ?>%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-<?php echo getStatusBadge($row['room_status']); ?>"><?php echo ucfirst($row['room_status']); ?></span></td>
                                            <td><?php echo formatCurrency($row['monthly_rent']); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center py-5">No rooms found in the system.</td></tr>
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