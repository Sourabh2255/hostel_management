<nav class="col-md-2 d-md-block sidebar">
    <div class="sidebar-header text-white text-center">
        <i class="fas fa-building fa-3x mb-2"></i>
        <h5>Smart Hostel</h5>
        <p class="mb-0 small">Admin Panel</p>
    </div>
    
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>STAFF MANAGEMENT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_staff.php' ? 'active' : ''; ?>" href="manage_staff.php">
                    <i class="fas fa-users-cog"></i>
                    Manage Staff
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_staff.php' ? 'active' : ''; ?>" href="add_staff.php">
                    <i class="fas fa-user-plus"></i>
                    Add New Staff
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>STUDENT MANAGEMENT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_students.php' ? 'active' : ''; ?>" href="view_students.php">
                    <i class="fas fa-users"></i>
                    View All Students
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_student.php' ? 'active' : ''; ?>" href="add_student.php">
                  <i class="fas fa-user-plus"></i>
                     Add Student
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_applications.php' ? 'active' : ''; ?>" href="view_applications.php">
                    <i class="fas fa-file-alt"></i>
                    Hostel Applications
                    <?php
                    $conn = getDBConnection();
                    $sql = "SELECT COUNT(*) as count FROM student_application WHERE application_status = 'pending'";
                    $result = $conn->query($sql);
                    $pending = $result->fetch_assoc()['count'];
                    //closeDBConnection($conn);
                    if ($pending > 0):
                    ?>
                        <span class="badge bg-danger rounded-pill float-end"><?php echo $pending; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>ROOM MANAGEMENT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_rooms.php' ? 'active' : ''; ?>" href="manage_rooms.php">
                    <i class="fas fa-door-open"></i>
                    Manage Rooms
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_room.php' ? 'active' : ''; ?>" href="add_room.php">
                    <i class="fas fa-plus-circle"></i>
                    Add New Room
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'room_allocations.php' ? 'active' : ''; ?>" href="room_allocations.php">
                    <i class="fas fa-bed"></i>
                    Room Allocations
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>PAYMENTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_payments.php' ? 'active' : ''; ?>" href="view_payments.php">
                    <i class="fas fa-money-bill-wave"></i>
                    View Payments
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'payment_reports.php' ? 'active' : ''; ?>" href="payment_reports.php">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Payment Reports
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>COMPLAINTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_complaints.php' ? 'active' : ''; ?>" href="view_complaints.php">
                    <i class="fas fa-tools"></i>
                    View Complaints
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>REPORTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" href="reports.php">
                    <i class="fas fa-chart-bar"></i>
                    All Reports
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>ACCOUNT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_profile.php' ? 'active' : ''; ?>" href="admin_profile.php">
                    <i class="fas fa-user-circle"></i>
                    My Profile
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'hostel_settings.php' ? 'active' : ''; ?>" href="hostel_settings.php">
                    <i class="fas fa-cog"></i>
                    Hostel Settings
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
