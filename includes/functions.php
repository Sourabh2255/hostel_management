<?php
// Common Utility Functions
// Smart Hostel Management System

require_once __DIR__ . '/../config/database.php';

// Sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (Indian format)
function validatePhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) === 10;
}

// Format date
function formatDate($date) {
    return date('d-M-Y', strtotime($date));
}

// Format datetime
function formatDateTime($datetime) {
    return date('d-M-Y h:i A', strtotime($datetime));
}

// Calculate age from date of birth
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

// Get hostel information
function getHostelInfo() {
    $conn = getDBConnection();
    $sql = "SELECT * FROM hostel LIMIT 1";
    $result = $conn->query($sql);
    $hostel = $result->fetch_assoc();
    closeDBConnection($conn);
    return $hostel;
}

// Get total counts for dashboard
function getDashboardCounts() {
    $conn = getDBConnection();
    
    $counts = [];
    
    // Total active students
    $sql = "SELECT COUNT(*) as count FROM student WHERE student_status = 'active'";
    $result = $conn->query($sql);
    $counts['total_students'] = $result->fetch_assoc()['count'];
    
    // Total staff
    $sql = "SELECT COUNT(*) as count FROM staff WHERE status = 'active'";
    $result = $conn->query($sql);
    $counts['total_staff'] = $result->fetch_assoc()['count'];
    
    // Total rooms
    $sql = "SELECT COUNT(*) as count FROM room";
    $result = $conn->query($sql);
    $counts['total_rooms'] = $result->fetch_assoc()['count'];
    
    // Available rooms
    $sql = "SELECT COUNT(*) as count FROM room WHERE room_status = 'available'";
    $result = $conn->query($sql);
    $counts['available_rooms'] = $result->fetch_assoc()['count'];
    
    // Pending applications
    $sql = "SELECT COUNT(*) as count FROM student_application WHERE application_status = 'pending'";
    $result = $conn->query($sql);
    $counts['pending_applications'] = $result->fetch_assoc()['count'];
    
    // Pending complaints
    $sql = "SELECT COUNT(*) as count FROM complaint WHERE complaint_status IN ('pending', 'in_progress')";
    $result = $conn->query($sql);
    $counts['pending_complaints'] = $result->fetch_assoc()['count'];
    
    // Total payments this month
    $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM payment WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    $counts['monthly_revenue'] = $result->fetch_assoc()['total'];
    
    closeDBConnection($conn);
    return $counts;
}

// Upload file
function uploadFile($file, $destination_folder, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
    $target_dir = __DIR__ . "/../uploads/" . $destination_folder . "/";
    
    // Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if file type is allowed
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Check file size (max 5MB)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'File is too large. Max size: 5MB'];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $new_filename, 'path' => $target_file];
    } else {
        return ['success' => false, 'message' => 'Error uploading file'];
    }
}

// Send email notification (placeholder - configure SMTP)
function sendEmail($to, $subject, $message) {
    // Configure your SMTP settings here
    // For now, this is a placeholder
    
    $headers = "From: noreply@smarthostel.com\r\n";
    $headers .= "Reply-To: noreply@smarthostel.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Uncomment when email is configured
    // return mail($to, $subject, $message, $headers);
    
    return true; // Placeholder
}

// Log activity
function logActivity($user_id, $user_type, $action, $description) {
    $conn = getDBConnection();
    
    $sql = "INSERT INTO activity_log (user_id, user_type, action, description, created_at) VALUES (?, ?, ?, ?, NOW())";
    
    // Note: activity_log table needs to be created if you want to use this feature
    // For now, this is optional
    
    closeDBConnection($conn);
}

// Generate report in different formats
function exportToCSV($data, $filename, $headers) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add headers
    fputcsv($output, $headers);
    
    // Add data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

// Check if room has available space
function isRoomAvailable($room_id) {
    $conn = getDBConnection();
    
    $sql = "SELECT capacity, current_occupancy FROM room WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
        closeDBConnection($conn);
        return $room['current_occupancy'] < $room['capacity'];
    }
    
    closeDBConnection($conn);
    return false;
}

// Get available rooms
function getAvailableRooms() {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM room WHERE current_occupancy < capacity AND room_status IN ('available', 'occupied') ORDER BY room_number";
    $result = $conn->query($sql);
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    closeDBConnection($conn);
    return $rooms;
}

// Check if student has active allocation
function hasActiveAllocation($student_id) {
    $conn = getDBConnection();
    
    $sql = "SELECT COUNT(*) as count FROM room_allocation WHERE student_id = ? AND allocation_status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    closeDBConnection($conn);
    return $row['count'] > 0;
}

// Generate invoice number
function generateInvoiceNumber() {
    return 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));
}

// Get month name
function getMonthName($month_number) {
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    return $months[$month_number] ?? '';
}

// Format currency
function formatCurrency($amount) {
    return '₹ ' . number_format($amount, 2);
}

// Get room type badge color
function getRoomTypeBadge($room_type) {
    $badges = [
        'single' => 'primary',
        'double' => 'success',
        'triple' => 'warning',
        'quadruple' => 'info'
    ];
    return $badges[$room_type] ?? 'secondary';
}

// Get status badge color
function getStatusBadge($status) {
    $badges = [
        'active' => 'success',
        'inactive' => 'secondary',
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'paid' => 'success',
        'partial' => 'warning',
        'resolved' => 'success',
        'in_progress' => 'info',
        'closed' => 'secondary',
        'available' => 'success',
        'occupied' => 'warning',
        'maintenance' => 'danger'
    ];
    return $badges[$status] ?? 'secondary';
}
?>
