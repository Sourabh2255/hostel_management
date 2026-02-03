<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

// 1. Establish connection
$conn = getDBConnection();

// 2. Handle Status Changes (Maintenance toggle)
if (isset($_GET['id']) && isset($_GET['status'])) {
    $room_id = intval($_GET['id']);
    $new_status = ($_GET['status'] == 'maintenance') ? 'available' : 'maintenance';
    $stmt = $conn->prepare("UPDATE room SET room_status = ? WHERE room_id = ?");
    $stmt->bind_param("si", $new_status, $room_id);
    $stmt->execute();
    header("Location: manage_rooms.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // Include sidebar which also uses $conn
            include '../includes/admin_sidebar.php'; 
            
            // 3. RE-ESTABLISH/REFRESH QUERY after sidebar to ensure data fetch
            $sql = "SELECT *, 
                    CASE 
                        WHEN capacity > 0 THEN ROUND((current_occupancy / capacity) * 100, 2) 
                        ELSE 0 
                    END as occupancy_percentage 
                    FROM room 
                    ORDER BY floor_number ASC, room_number ASC";
            $result = $conn->query($sql);
            ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-door-open me-2"></i>Room Inventory</h2>
                    <a href="add_room.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Room</a>
                </div>

                <div class="card custom-card">
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Check if query returned results
                                    if ($result && $result->num_rows > 0): 
                                        while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['room_number']); ?></strong></td>
                                            <td><span class="badge bg-info"><?php echo ucfirst($row['room_type']); ?></span></td>
                                            <td><?php echo $row['floor_number']; ?></td>
                                            <td>
                                                <?php echo ($row['current_occupancy'] ?? 0); ?> / <?php echo $row['capacity']; ?>
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $row['occupancy_percentage']; ?>%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success"><?php echo ucfirst($row['room_status']); ?></span></td>
                                            <td>₹<?php echo number_format($row['monthly_rent'], 2); ?></td>
                                            <td>
                                                <a href="manage_rooms.php?id=<?php echo $row['room_id']; ?>&status=<?php echo $row['room_status']; ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Toggle Maintenance">
                                                    <i class="fas fa-tools"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-door-closed fa-3x mb-3 text-muted"></i>
                                                    <h5>No Rooms Found</h5>
                                                    <p>Please check your database table 'room'.</p>
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