<?php
/**
 * DEBUG SCRIPT - LOGIN TROUBLESHOOTING
 * Shows all users, their passwords, and password verification results
 */

require 'config/database.php';

echo "<h2>🔍 HOSTEL MANAGEMENT SYSTEM - LOGIN DEBUG</h2>";
echo "<hr>";

// 1. Test Database Connection
echo "<h3>1️⃣ DATABASE CONNECTION TEST</h3>";
$conn = getDBConnection();
if ($conn->connect_error) {
    echo "<span style='color:red;'>❌ FAILED: " . $conn->connect_error . "</span>";
    exit;
} else {
    echo "<span style='color:green;'>✅ SUCCESS: Connected to database</span>";
}
echo "<hr>";

// 2. Check ADMIN Users
echo "<h3>2️⃣ ADMIN USERS</h3>";
$sql = "SELECT admin_id, username, password FROM admin";
$result = $conn->query($sql);
if (!$result) {
    echo "<span style='color:red;'>❌ Query failed: " . $conn->error . "</span>";
} else {
    echo "<table border='1' cellpadding='10' style='width:100%;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password Hash (first 50 chars)</th><th>Status</th><th>Verify admin123</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $hash = $row['password'];
        $verify = password_verify('admin123', $hash) ? "✅ YES" : "❌ NO";
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['admin_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td><code>" . htmlspecialchars(substr($hash, 0, 50)) . "...</code></td>";
        echo "<td><span style='color:green;'>always active</span></td>";
        echo "<td>{$verify}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
echo "<hr>";

// 3. Check STAFF Users
echo "<h3>3️⃣ STAFF USERS</h3>";
$sql = "SELECT staff_id, username, password, status FROM staff";
$result = $conn->query($sql);
if (!$result) {
    echo "<span style='color:red;'>❌ Query failed: " . $conn->error . "</span>";
} else {
    echo "<table border='1' cellpadding='10' style='width:100%;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password Hash (first 50 chars)</th><th>Status</th><th>Verify staff123</th></tr>";
    if ($result->num_rows === 0) {
        echo "<tr><td colspan='5' style='text-align:center; color:orange;'>⚠️ NO STAFF USERS FOUND</td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $hash = $row['password'];
            $verify = password_verify('staff123', $hash) ? "✅ YES" : "❌ NO";
            $status_color = ($row['status'] ?? '') === 'active' ? 'green' : 'red';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td><code>" . htmlspecialchars(substr($hash, 0, 50)) . "...</code></td>";
            echo "<td><span style='color:{$status_color};'>" . htmlspecialchars($row['status'] ?? 'NULL') . "</span></td>";
            echo "<td>{$verify}</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}
echo "<hr>";

// 4. Check STUDENT Users
echo "<h3>4️⃣ STUDENT USERS</h3>";
$sql = "SELECT student_id, username, password, student_status FROM student";
$result = $conn->query($sql);
if (!$result) {
    echo "<span style='color:red;'>❌ Query failed: " . $conn->error . "</span>";
} else {
    echo "<table border='1' cellpadding='10' style='width:100%;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password Hash (first 50 chars)</th><th>Status</th><th>Verify student123</th></tr>";
    if ($result->num_rows === 0) {
        echo "<tr><td colspan='5' style='text-align:center; color:orange;'>⚠️ NO STUDENT USERS FOUND</td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $hash = $row['password'];
            $verify = password_verify('student123', $hash) ? "✅ YES" : "❌ NO";
            $status_color = ($row['student_status'] ?? '') === 'active' ? 'green' : 'red';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td><code>" . htmlspecialchars(substr($hash, 0, 50)) . "...</code></td>";
            echo "<td><span style='color:{$status_color};'>" . htmlspecialchars($row['student_status'] ?? 'NULL') . "</span></td>";
            echo "<td>{$verify}</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3>✏️ INTERPRETATION GUIDE</h3>";
echo "<ul>";
echo "<li><span style='color:green;'>✅ YES</span> = Password hash matches the test password</li>";
echo "<li><span style='color:red;'>❌ NO</span> = Password hash does NOT match (need to reset)</li>";
echo "<li><span style='color:green;'>active</span> = User is enabled</li>";
echo "<li><span style='color:red;'>inactive/NULL</span> = User is disabled (check login code)</li>";
echo "</ul>";

closeDBConnection($conn);
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
h2 { color: #333; }
h3 { color: #0066cc; margin-top: 20px; }
table { background: white; border-collapse: collapse; }
th { background: #0066cc; color: white; text-align: left; }
td { padding: 8px; border: 1px solid #ddd; }
code { background: #f0f0f0; padding: 2px 5px; border-radius: 3px; }
</style>
