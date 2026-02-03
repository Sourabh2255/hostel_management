<?php
// pages/student_raise_complaint.php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Ensure only logged-in students can access this page
requireRole('student');

$student_id = getCurrentUserId(); //
$conn = getDBConnection(); //
$success = '';
$error = '';

// Step 1: Fetch the student's current room ID
$room_sql = "SELECT room_id FROM room_allocation 
             WHERE student_id = ? AND allocation_status = 'active'";
$stmt = $conn->prepare($room_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$room_data = $stmt->get_result()->fetch_assoc();
$room_id = $room_data['room_id'] ?? null;

// Step 2: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
  if (!$room_id) {
    $error = "You must be allocated to a room to raise a complaint.";
  } else {
    // Sanitize inputs as defined in functions.php
    $type = sanitizeInput($_POST['complaint_type']);
    $title = sanitizeInput($_POST['complaint_title']);
    $description = sanitizeInput($_POST['complaint_description']);
    $priority = sanitizeInput($_POST['priority']);

    // Insert into the complaint table
    $sql = "INSERT INTO complaint (student_id, room_id, complaint_type, complaint_title, complaint_description, priority, complaint_status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $student_id, $room_id, $type, $title, $description, $priority);

    if ($stmt->execute()) {
      $success = "Complaint raised successfully! Our staff will review it soon.";
    } else {
      $error = "Error submitting complaint. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Raise Complaint - Smart Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <?php include '../includes/student_sidebar.php'; ?> <div class="col-md-10 ms-sm-auto px-md-4 py-4">
        <div class="card custom-card col-lg-8 mx-auto">
          <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Raise Maintenance Complaint</h4>
          </div>
          <div class="card-body">
            <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label class="form-label">Complaint Category *</label>
                <select name="complaint_type" class="form-select" required>
                  <option value="electrical">Electrical</option>
                  <option value="plumbing">Plumbing</option>
                  <option value="furniture">Furniture</option>
                  <option value="cleanliness">Cleanliness</option>
                  <option value="wifi">WiFi / Internet</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Title / Subject *</label>
                <input type="text" name="complaint_title" class="form-control" placeholder="e.g., Fan not working" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Detailed Description *</label>
                <textarea name="complaint_description" class="form-control" rows="4" placeholder="Describe the issue in detail..." required></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Priority Level</label>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" value="low" id="p1">
                    <label class="form-check-label" for="p1">Low</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" value="medium" id="p2" checked>
                    <label class="form-check-label" for="p2">Medium</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" value="high" id="p3">
                    <label class="form-check-label" for="p3">High</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" value="urgent" id="p4">
                    <label class="form-check-label" for="p4">Urgent</label>
                  </div>
                </div>
              </div>

              <div class="text-center mt-4">
                <button type="submit" name="submit_complaint" class="btn btn-primary px-5">
                  <i class="fas fa-paper-plane me-2"></i>Submit Complaint
                </button>
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