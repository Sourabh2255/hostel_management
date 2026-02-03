<?php
/**
 * PASSWORD GENERATOR
 * Generates bcrypt hashes for test users
 */

echo "<h2>🔐 PASSWORD HASH GENERATOR</h2>";
echo "<hr>";

// Generate hashes for test passwords
$passwords = [
    'admin123' => 'Admin password',
    'staff123' => 'Staff password',
    'student123' => 'Student password'
];

echo "<table border='1' cellpadding='10' style='width:100%; font-family: monospace;'>";
echo "<tr><th>Plain Password</th><th>Description</th><th>Bcrypt Hash</th><th>Copy SQL</th></tr>";

foreach ($passwords as $plain => $desc) {
    $hash = password_hash($plain, PASSWORD_BCRYPT);
    $sql = "UPDATE admin SET password = '$hash' WHERE username='admin';";
    echo "<tr>";
    echo "<td><strong>$plain</strong></td>";
    echo "<td>$desc</td>";
    echo "<td><code style='font-size: 11px; word-break: break-all;'>$hash</code></td>";
    echo "<td><button onclick='copy(\"$hash\")'>Copy Hash</button></td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>📋 COPY & PASTE THIS SQL IN PHPMYADMIN</h3>";
echo "<pre style='background: #f0f0f0; padding: 15px; border-radius: 5px; overflow-x: auto;'>";

$admin_hash = password_hash('admin123', PASSWORD_BCRYPT);
$staff_hash = password_hash('staff123', PASSWORD_BCRYPT);
$student_hash = password_hash('student123', PASSWORD_BCRYPT);

echo "UPDATE admin SET password = '$admin_hash' WHERE username='admin';\n";
echo "UPDATE staff SET password = '$staff_hash';\n";
echo "UPDATE student SET password = '$student_hash';\n";

echo "</pre>";

echo "<h3>✅ AFTER RUNNING SQL, TEST LOGIN WITH:</h3>";
echo "<ul>";
echo "<li><strong>Admin:</strong> username=<code>admin</code> password=<code>admin123</code></li>";
echo "<li><strong>Staff:</strong> username=<code>staff1</code> password=<code>staff123</code></li>";
echo "<li><strong>Student:</strong> username=<code>(any student)</code> password=<code>student123</code></li>";
echo "</ul>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
h2, h3 { color: #0066cc; }
table { background: white; width: 100%; }
th { background: #0066cc; color: white; padding: 10px; text-align: left; }
td { padding: 10px; border-bottom: 1px solid #ddd; }
code { background: #f0f0f0; padding: 3px 6px; border-radius: 3px; }
button { padding: 5px 10px; background: #0066cc; color: white; border: none; cursor: pointer; border-radius: 3px; }
button:hover { background: #0055aa; }
pre { background: #f0f0f0; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>

<script>
function copy(text) {
    navigator.clipboard.writeText(text);
    alert('Hash copied to clipboard!');
}
</script>
