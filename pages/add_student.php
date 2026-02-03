<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Restrict access to Admin and Staff only
requireRole('admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $conn = getDBConnection();
    
    // Sanitize basic inputs
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $gender = sanitizeInput($_POST['gender']);
    $dob = sanitizeInput($_POST['date_of_birth']);
    $course = sanitizeInput($_POST['course_name']);
    $institution = sanitizeInput($_POST['institution_name']);
    $year = sanitizeInput($_POST['year_of_study']);
    
    // Address and Guardian info
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $pincode = sanitizeInput($_POST['pincode']);
    $guardian_name = sanitizeInput($_POST['guardian_name']);
    $guardian_phone = sanitizeInput($_POST['guardian_phone']);
    $guardian_relation = sanitizeInput($_POST['guardian_relation']);
    
    // ID Proof
    $id_type = sanitizeInput($_POST['id_proof_type']);
    $id_number = sanitizeInput($_POST['id_proof_number']);

    // Auto-generate credentials
    $username = generateUsername($full_name);
    $plain_password = generatePassword(8);
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    $creator_id = getCurrentUserId();

    $sql = "INSERT INTO student (
                username, password, full_name, date_of_birth, gender, email, phone, 
                address, city, state, pincode, guardian_name, guardian_phone, 
                guardian_relation, course_name, institution_name, year_of_study, 
                id_proof_type, id_proof_number, admission_date, student_status, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active', ?)";
    
    $stmt = $conn->prepare($sql);
// UPDATED LINE 51
// UPDATED LINE 51 in pages/add_student.php
$stmt->bind_param("sssssssssssssssssssi", 
    $username, $hashed_password, $full_name, $dob, $gender, $email, $phone,
    $address, $city, $state, $pincode, $guardian_name, $guardian_phone,
    $guardian_relation, $course, $institution, $year, $id_type, $id_number, $creator_id
);

    if ($stmt->execute()) {
        $success = "Student account created successfully! <br> 
                    Username: <strong>$username</strong> <br> 
                    Temporary Password: <strong>$plain_password</strong>";
    } else {
        $error = "Failed to add student. Email or Username may already exist.";
    }
    closeDBConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin_sidebar.php'; ?>
            
            <div class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Register New Student</h4>
                    </div>
                    <div class="card-body">
                        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <form method="POST" action="">
                            <h5 class="text-primary mb-3">Personal & Academic Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">DOB</label>
                                    <input type="date" name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Course</label>
                                    <input type="text" name="course_name" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Year of Study</label>
                                    <input type="text" name="year_of_study" class="form-control" placeholder="e.g. 2nd Year" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Institution Name</label>
                                    <input type="text" name="institution_name" class="form-control" required>
                                </div>
                            </div>

                            <h5 class="text-primary mb-3">Guardian & Identity Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Guardian Name</label>
                                    <input type="text" name="guardian_name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Guardian Relation</label>
                                    <input type="text" name="guardian_relation" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Guardian Phone</label>
                                    <input type="text" name="guardian_phone" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">ID Proof Type</label>
                                    <select name="id_proof_type" class="form-select">
                                        <option value="aadhar">Aadhar Card</option>
                                        <option value="pan">PAN Card</option>
                                        <option value="voter">Voter ID</option>
                                        <option value="college_id">College ID</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ID Number</label>
                                    <input type="text" name="id_proof_number" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" name="add_student" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-save me-2"></i>Complete Registration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>