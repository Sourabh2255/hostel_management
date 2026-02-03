<?php
// Logout Page
// Smart Hostel Management System

session_start();
session_unset();
session_destroy();

header("Location: ../index.php");
exit();
?>
