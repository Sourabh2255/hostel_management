<?php
// Test script to verify staff display issue
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/auth.php';
require_once '../includes/functions.php';

echo "<h2>🔍 STAFF DISPLAY TEST</h2>";
echo "<hr>";

// Check if logged in
echo "<h3>Session Status:</h3>";
if (isLoggedIn()) {
    echo "<span style='color:green;'>✅ LOGGED IN</span><br>";
    echo "User Type: " . htmlspecialchars(getCurrentUserType()) . "<br>";
    echo "User ID: " . htmlspecialchars(getCurrentUserId()) . "<br>";
} else {
    echo "<span style='color:red;'>❌ NOT LOGGED IN</span><br>";
}

echo "<hr>";
echo "<h3>Database Test:</h3>";

$conn = getDBConnection();
if (!$conn) {
    echo "<span style='color:red;'>❌ Database connection failed</span>";
    exit;
}
echo "<span style='color:green;'>✅ Database connected</span><br>";

// Check staff table
$sql = "SELECT COUNT(*) as total FROM staff";
$result = $conn->query($sql);
if (!$result) {
    echo "<span style='color:red;'>❌ Query failed: " . $conn->error . "</span>";
    exit;
}

$row = $result->fetch_assoc();
echo "Total staff in database: <strong>" . $row['total'] . "</strong><br>";

echo "<hr>";
echo "<h3>Staff List:</h3>";

$sql = "SELECT staff_id, full_name, username, designation, email, phone, status FROM staff ORDER BY staff_id DESC";
$result = $conn->query($sql);

if (!$result) {
    echo "<span style='color:red;'>❌ Query error: " . $conn->error . "</span>";
} else {
    echo "<table border='1' cellpadding='10' style='width:100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Designation</th><th>Email</th><th>Phone</th><th>Status</th></tr>";
    
    if ($result->num_rows === 0) {
        echo "<tr><td colspan='7' style='text-align:center;'>⚠️ NO STAFF FOUND</td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['designation'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

closeDBConnection($conn);
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
h2, h3 { color: #0066cc; }
table { background: white; width: 100%; }
th { background: #0066cc; color: white; padding: 10px; }
td { padding: 8px; border-bottom: 1px solid #ddd; }
</style>
