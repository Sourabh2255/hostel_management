<nav class="col-md-2 d-md-block sidebar">
    <div class="sidebar-header text-white text-center">
        <i class="fas fa-building fa-3x mb-2"></i>
        <h5>Smart Hostel</h5>
        <p class="mb-0 small">Staff Panel</p>
    </div>
    
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'staff_dashboard.php' ? 'active' : ''; ?>" href="staff_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>STUDENT MANAGEMENT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_view_students.php">
                    <i class="fas fa-users"></i>
                    View All Students
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_add_student.php">
                    <i class="fas fa-user-plus"></i>
                    Add New Student
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_view_applications.php">
                    <i class="fas fa-file-alt"></i>
                    Hostel Applications
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>ROOM MANAGEMENT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_view_rooms.php">
                    <i class="fas fa-door-open"></i>
                    View Rooms
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_allocate_room.php">
                    <i class="fas fa-bed"></i>
                    Allocate Room
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>PAYMENTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_view_payments.php">
                    <i class="fas fa-money-bill-wave"></i>
                    View Payments
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_record_payment.php">
                    <i class="fas fa-plus-circle"></i>
                    Record Payment
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>COMPLAINTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_view_complaints.php">
                    <i class="fas fa-tools"></i>
                    View Complaints
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>ACCOUNT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="staff_profile.php">
                    <i class="fas fa-user-circle"></i>
                    My Profile
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
