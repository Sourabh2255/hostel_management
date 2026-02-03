<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$conn = getDBConnection();
$msg = '';

if (isset($_POST['allocate'])) {
    $student_id = intval($_POST['student_id']);
    $room_id = intval($_POST['room_id']);
    $staff_id = getCurrentUserId(); // Admin is acting as staff here

    $stmt = $conn->prepare("INSERT INTO room_allocation (student_id, room_id, allocation_date, allocated_by) VALUES (?, ?, CURDATE(), ?)");
    $stmt->bind_param("iii", $student_id, $room_id, $staff_id);
    
    if ($stmt->execute()) {
        $msg = "Success: Student allocated to room!";
    } else {
        $msg = "Error: Allocation failed.";
    }
}

// Get unallocated active students
$students_sql = "SELECT student_id, full_name FROM student WHERE student_id NOT IN (SELECT student_id FROM room_allocation WHERE allocation_status = 'active') AND student_status = 'active'";
$students_res = $conn->query($students_sql);

// Get rooms with available space
$rooms_sql = "SELECT room_id, room_number, room_type, (capacity - current_occupancy) as available FROM room WHERE room_status != 'maintenance' AND current_occupancy < capacity";
$rooms_res = $conn->query($rooms_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Allocation - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2><i class="fas fa-bed me-2"></i>Allocate Room</h2>
                
                <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

                <div class="card custom-card mt-4">
                    <div class="card-body">
                        <form method="POST" class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Select Student</label>
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
                            <div class="col-md-4 mb-3">
                                <button type="submit" name="allocate" class="btn btn-success w-100">Confirm Allocation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>