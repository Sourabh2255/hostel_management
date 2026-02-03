<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = getCurrentUserId();
$user_type = getCurrentUserType();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
  $conn = getDBConnection();

  // Role-isolated update query
  $table = ($user_type === 'staff') ? 'staff' : 'student';
  $id_col = ($user_type === 'staff') ? 'staff_id' : 'student_id';

  $stmt = $conn->prepare("UPDATE $table SET password = ? WHERE $id_col = ?");
  $stmt->bind_param("si", $new_pass, $user_id);
  if ($stmt->execute()) $msg = "Password updated successfully!";
}
