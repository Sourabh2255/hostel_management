<?php
// pages/student_view_complaints.php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Ensure only logged-in students can access this page
requireRole('student');

$student_id = getCurrentUserId();
$conn = getDBConnection();

// Query to get all complaints raised by this specific student
$sql = "SELECT c.*, r.room_number 
        FROM complaint c 
        JOIN room r ON c.room_id = r.room_id 
        WHERE c.student_id = ? 
        ORDER BY c.complaint_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>My Complaints - Smart Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <?php include '../includes/student_sidebar.php'; ?>

      <div class="col-md-10 ms-sm-auto px-md-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2><i class="fas fa-tools me-2"></i>My Raised Complaints</h2>
          <a href="student_raise_complaint.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Raise New Complaint
          </a>
        </div>

        <div class="card custom-card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover custom-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Resolution Info</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                      <tr>
                        <td><?php echo formatDate($row['complaint_date']); ?></td>
                        <td>Room <?php echo htmlspecialchars($row['room_number']); ?></td>
                        <td><span class="badge bg-info"><?php echo ucfirst($row['complaint_type']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['complaint_title']); ?></td>
                        <td>
                          <span class="badge bg-<?php echo ($row['priority'] == 'high' || $row['priority'] == 'urgent') ? 'danger' : 'secondary'; ?>">
                            <?php echo ucfirst($row['priority']); ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge bg-<?php echo getStatusBadge($row['complaint_status']); ?>">
                            <?php echo str_replace('_', ' ', ucfirst($row['complaint_status'])); ?>
                          </span>
                        </td>
                        <td>
                          <?php if ($row['complaint_status'] == 'resolved' || $row['complaint_status'] == 'closed'): ?>
                            <small class="text-success">
                              <strong>Resolved on:</strong> <?php echo formatDate($row['resolved_date']); ?><br>
                              <strong>Remarks:</strong> <?php echo htmlspecialchars($row['resolution_remarks']); ?>
                            </small>
                          <?php else: ?>
                            <span class="text-muted italic">Awaiting resolution...</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                          <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                          <h5>No Complaints Found</h5>
                          <p>You haven't raised any maintenance complaints yet.</p>
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
<?php closeDBConnection($conn); ?>