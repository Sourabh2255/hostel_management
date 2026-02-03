<?php
// pages/change_password.php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Ensure the user is logged in (either staff or student)
requireLogin();

$user_id = getCurrentUserId(); //
$user_type = getCurrentUserType(); //
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
  $conn = getDBConnection(); //

  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // 1. Basic Validation
  if ($new_password !== $confirm_password) {
    $error = "New password and confirmation do not match.";
  } elseif (strlen($new_password) < 6) {
    $error = "New password must be at least 6 characters long.";
  } else {
    // 2. Identify the correct table based on session user_type
    $table = ($user_type === 'staff') ? 'staff' : 'student';
    $id_field = ($user_type === 'staff') ? 'staff_id' : 'student_id';

    // 3. Verify current password
    $sql = "SELECT password FROM $table WHERE $id_field = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($current_password, $user['password'])) {
      // 4. Hash and update new password
      $hashed_password = hashPassword($new_password);

      $update_sql = "UPDATE $table SET password = ? WHERE $id_field = ?";
      $update_stmt = $conn->prepare($update_sql);
      $update_stmt->bind_param("si", $hashed_password, $user_id);

      if ($update_stmt->execute()) {
        $success = "Password updated successfully!";
      } else {
        $error = "Database error. Please try again.";
      }
    } else {
      $error = "Current password is incorrect.";
    }
  }
  closeDBConnection($conn); //
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Change Password - Smart Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <?php
      if ($user_type === 'staff') {
        include '../includes/staff_sidebar.php'; //
      } else {
        include '../includes/student_sidebar.php'; //
      }
      ?>

      <div class="col-md-10 ms-sm-auto px-md-4 py-4">
        <div class="card custom-card col-lg-6 mx-auto mt-5">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-key me-2"></i>Update Password</h4>
          </div>
          <div class="card-body">
            <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
              </div>

              <hr>

              <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
                <div class="form-text">Min. 6 characters. Use letters and numbers.</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>

              <div class="text-center mt-4">
                <button type="submit" name="update_password" class="btn btn-primary px-5">
                  Change Password
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