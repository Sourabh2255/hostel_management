<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireRole('admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    $room_no = sanitizeInput($_POST['room_number']);
    $type = sanitizeInput($_POST['room_type']);
    $floor = intval($_POST['floor_number']);
    $rent = floatval($_POST['monthly_rent']);
    
    // Set capacity based on type
    $capacity_map = ['single' => 1, 'double' => 2, 'triple' => 3, 'quadruple' => 4];
    $capacity = $capacity_map[$type];
    
    // Get default hostel ID
    $hostel = getHostelInfo();
    $hostel_id = $hostel['hostel_id'];

    $sql = "INSERT INTO room (hostel_id, room_number, room_type, capacity, floor_number, monthly_rent, room_status) VALUES (?, ?, ?, ?, ?, ?, 'available')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issidi", $hostel_id, $room_no, $type, $capacity, $floor, $rent);

    if ($stmt->execute()) {
        $success = "Room $room_no added successfully!";
    } else {
        $error = "Error: Room number might already exist.";
    }
    closeDBConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Room - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            <div class="col-md-6 mx-auto py-5">
                <div class="card custom-card">
                    <div class="card-header"><i class="fas fa-plus-circle me-2"></i>Register New Room</div>
                    <div class="card-body">
                        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Room Number</label>
                                <input type="text" name="room_number" class="form-control" placeholder="e.g. 101A" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Room Type</label>
                                <select name="room_type" class="form-select" required>
                                    <option value="single">Single (1 Bed)</option>
                                    <option value="double">Double (2 Beds)</option>
                                    <option value="triple">Triple (3 Beds)</option>
                                    <option value="quadruple">Quadruple (4 Beds)</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Floor Number</label>
                                    <input type="number" name="floor_number" class="form-control" min="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Monthly Rent (₹)</label>
                                    <input type="number" name="monthly_rent" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Room to System</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>