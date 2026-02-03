<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restricted to Staff role
requireRole('staff');

$conn = getDBConnection();
$msg = '';

// Handle Allocation Logic
if (isset($_POST['allocate'])) {
    $student_id = intval($_POST['student_id']);
    $room_id = intval($_POST['room_id']);
    $staff_id = getCurrentUserId();

    // Insert allocation record (triggers in database will update room occupancy automatically)
    $stmt = $conn->prepare("INSERT INTO room_allocation (student_id, room_id, allocation_date, allocated_by) VALUES (?, ?, CURDATE(), ?)");
    $stmt->bind_param("iii", $student_id, $room_id, $staff_id);
    
    if ($stmt->execute()) {
        $msg = "Success: Student has been allocated to the room!";
    } else {
        $msg = "Error: Allocation failed. Please ensure the student is not already allocated.";
    }
}

// Get active students who don't have a room yet
$students_sql = "SELECT student_id, full_name FROM student WHERE student_id NOT IN (SELECT student_id FROM room_allocation WHERE allocation_status = 'active') AND student_status = 'active'";
$students_res = $conn->query($students_sql);

// Get rooms that have available beds and are not under maintenance
$rooms_sql = "SELECT room_id, room_number, room_type, (capacity - current_occupancy) as available FROM room WHERE room_status != 'maintenance' AND current_occupancy < capacity";
$rooms_res = $conn->query($rooms_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Allocation - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/staff_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-bed me-2 text-primary"></i>Room Allocation</h2>
                
                <?php if($msg) echo "<div class='alert alert-info mt-3'>$msg</div>"; ?>

                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <form method="POST" class="row align-items-end">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Select Unallocated Student</label>
                                <select name="student_id" class="form-select" required>
                                    <option value="">-- Choose Student --</option>
                                    <?php while($s = $students_res->fetch_assoc()): ?>
                                        <option value="<?php echo $s['student_id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Select Available Room</label>
                                <select name="room_id" class="form-select" required>
                                    <option value="">-- Choose Room --</option>
                                    <?php while($r = $rooms_res->fetch_assoc()): ?>
                                        <option value="<?php echo $r['room_id']; ?>">Room <?php echo $r['room_number']; ?> (<?php echo $r['available']; ?> beds left)</option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" name="allocate" class="btn btn-success w-100">Allocate Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>