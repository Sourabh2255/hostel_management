<?php
// pages/student_view_room.php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Ensure only logged-in students can access this page
requireRole('student');

$student_id = getCurrentUserId();
$conn = getDBConnection();

// Fetch the current room allocation details for the logged-in student
$sql = "SELECT r.*, ra.allocation_date, h.hostel_name, h.address, h.city
        FROM room r
        JOIN room_allocation ra ON r.room_id = ra.room_id
        JOIN hostel h ON r.hostel_id = h.hostel_id
        WHERE ra.student_id = ? AND ra.allocation_status = 'active'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Room Details - Smart Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <?php include '../includes/student_sidebar.php'; ?>

      <div class="col-md-10 ms-sm-auto px-md-4 py-4">
        <div class="dashboard-header mb-4">
          <h2><i class="fas fa-bed me-2 text-primary"></i>My Room Details</h2>
          <p class="text-muted">Detailed information about your current hostel accommodation.</p>
        </div>

        <div class="row">
          <?php if ($room): ?>
            <div class="col-lg-6 mb-4">
              <div class="card custom-card shadow-sm h-100">
                <div class="card-header bg-white">
                  <h5 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>Allocation Info</h5>
                </div>
                <div class="card-body">
                  <table class="table table-borderless">
                    <tr>
                      <th class="text-muted w-50">Room Number:</th>
                      <td class="fw-bold"><?php echo htmlspecialchars($room['room_number']); ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Room Type:</th>
                      <td><span class="badge bg-<?php echo getRoomTypeBadge($room['room_type']); ?>"><?php echo ucfirst($room['room_type']); ?></span></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Floor Number:</th>
                      <td><?php echo $room['floor_number']; ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Monthly Rent:</th>
                      <td class="text-success fw-bold"><?php echo formatCurrency($room['monthly_rent']); ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Allocation Date:</th>
                      <td><?php echo formatDate($room['allocation_date']); ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-lg-6 mb-4">
              <div class="card custom-card shadow-sm h-100">
                <div class="card-header bg-white">
                  <h5 class="mb-0 text-primary"><i class="fas fa-map-marker-alt me-2"></i>Hostel Details</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="text-muted small d-block">Hostel Name</label>
                    <span class="fw-semibold"><?php echo htmlspecialchars($room['hostel_name']); ?></span>
                  </div>
                  <div class="mb-3">
                    <label class="text-muted small d-block">Full Address</label>
                    <p class="mb-0">
                      <?php echo nl2br(htmlspecialchars($room['address'])); ?><br>
                      <strong><?php echo htmlspecialchars($room['city']); ?></strong>
                    </p>
                  </div>
                  <div class="alert alert-warning mb-0 mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Maintenance Note:</strong> For any issues in this room, please use the <a href="student_raise_complaint.php" class="alert-link">Raise Complaint</a> section.
                  </div>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="col-12">
              <div class="card custom-card shadow-sm text-center py-5">
                <div class="card-body">
                  <i class="fas fa-hotel fa-4x text-muted mb-3"></i>
                  <h3>No Active Allocation</h3>
                  <p class="text-muted mb-4">You have not been assigned a room yet. Please contact the hostel office if you have already submitted your application.</p>
                  <a href="student_dashboard.php" class="btn btn-primary px-4">Return to Dashboard</a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php closeDBConnection($conn); ?>