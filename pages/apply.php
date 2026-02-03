<?php
// Student Hostel Application Form
// No login required - Public access

require_once '../includes/functions.php';
require_once '../config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    $conn = getDBConnection();
    
    // Sanitize all inputs
    $full_name = sanitizeInput($_POST['full_name']);
    $date_of_birth = sanitizeInput($_POST['date_of_birth']);
    $gender = sanitizeInput($_POST['gender']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $pincode = sanitizeInput($_POST['pincode']);
    $guardian_name = sanitizeInput($_POST['guardian_name']);
    $guardian_phone = sanitizeInput($_POST['guardian_phone']);
    $guardian_relation = sanitizeInput($_POST['guardian_relation']);
    $course_name = sanitizeInput($_POST['course_name']);
    $institution_name = sanitizeInput($_POST['institution_name']);
    $year_of_study = sanitizeInput($_POST['year_of_study']);
    $id_proof_type = sanitizeInput($_POST['id_proof_type']);
    $id_proof_number = sanitizeInput($_POST['id_proof_number']);
    
    // Validate required fields
    if (empty($full_name) || empty($date_of_birth) || empty($email) || empty($phone)) {
        $error = 'Please fill in all required fields';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email address';
    } elseif (!validatePhone($phone)) {
        $error = 'Invalid phone number (should be 10 digits)';
    } else {
        // Insert application
        $sql = "INSERT INTO student_application (
            full_name, date_of_birth, gender, email, phone, address, city, state, pincode,
            guardian_name, guardian_phone, guardian_relation, course_name, institution_name,
            year_of_study, id_proof_type, id_proof_number, application_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssssss",
            $full_name, $date_of_birth, $gender, $email, $phone, $address, $city, $state, $pincode,
            $guardian_name, $guardian_phone, $guardian_relation, $course_name, $institution_name,
            $year_of_study, $id_proof_type, $id_proof_number
        );
        
        if ($stmt->execute()) {
            $success = 'Application submitted successfully! You will be contacted soon.';
            // Clear form
            $_POST = array();
        } else {
            $error = 'Error submitting application. Please try again.';
        }
    }
    
    closeDBConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Application Form - Smart Hostel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-building me-2"></i>Smart Hostel Management
            </a>
            <div class="ms-auto">
                <a href="../index.php" class="btn btn-light">
                    <i class="fas fa-sign-in-alt me-2"></i>Back to Login
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom-card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Hostel Admission Application Form</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <div class="text-center">
                                <a href="../index.php" class="btn btn-primary">Go to Login</a>
                            </div>
                        <?php else: ?>
                            
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <!-- Personal Information -->
                                <h5 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Personal Information</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name *</label>
                                        <input type="text" name="full_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Birth *</label>
                                        <input type="date" name="date_of_birth" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Gender *</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email *</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number *</label>
                                        <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" placeholder="10 digit mobile number" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Address *</label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">City *</label>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">State *</label>
                                        <input type="text" name="state" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pincode *</label>
                                        <input type="text" name="pincode" class="form-control" pattern="[0-9]{6}" required>
                                    </div>
                                </div>
                                
                                <!-- Guardian Information -->
                                <h5 class="mb-3 text-primary"><i class="fas fa-users me-2"></i>Guardian Information</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Guardian Name *</label>
                                        <input type="text" name="guardian_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Guardian Phone *</label>
                                        <input type="tel" name="guardian_phone" class="form-control" pattern="[0-9]{10}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Relation *</label>
                                        <select name="guardian_relation" class="form-select" required>
                                            <option value="">Select</option>
                                            <option value="father">Father</option>
                                            <option value="mother">Mother</option>
                                            <option value="guardian">Guardian</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Academic Information -->
                                <h5 class="mb-3 text-primary"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Course Name *</label>
                                        <input type="text" name="course_name" class="form-control" placeholder="e.g., B.Tech Computer Science" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institution Name *</label>
                                        <input type="text" name="institution_name" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Year of Study *</label>
                                        <select name="year_of_study" class="form-select" required>
                                            <option value="">Select Year</option>
                                            <option value="1st Year">1st Year</option>
                                            <option value="2nd Year">2nd Year</option>
                                            <option value="3rd Year">3rd Year</option>
                                            <option value="4th Year">4th Year</option>
                                            <option value="5th Year">5th Year</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- ID Proof -->
                                <h5 class="mb-3 text-primary"><i class="fas fa-id-card me-2"></i>Identity Proof</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">ID Proof Type *</label>
                                        <select name="id_proof_type" class="form-select" required>
                                            <option value="">Select ID Type</option>
                                            <option value="aadhar">Aadhar Card</option>
                                            <option value="pan">PAN Card</option>
                                            <option value="voter">Voter ID</option>
                                            <option value="passport">Passport</option>
                                            <option value="college_id">College ID</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ID Proof Number *</label>
                                        <input type="text" name="id_proof_number" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> After submitting this application, the hostel administration will review it. 
                                    If approved, you will receive login credentials via email or phone.
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" name="submit_application" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Application
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
