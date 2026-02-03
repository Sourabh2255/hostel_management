<nav class="col-md-2 d-md-block sidebar">
    <div class="sidebar-header text-white text-center">
        <i class="fas fa-building fa-3x mb-2"></i>
        <h5>Smart Hostel</h5>
        <p class="mb-0 small">Student Panel</p>
    </div>
    
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'student_dashboard.php' ? 'active' : ''; ?>" href="student_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>MY INFORMATION</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_view_profile.php">
                    <i class="fas fa-user-circle"></i>
                    My Profile
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_room_details.php">
                    <i class="fas fa-door-open"></i>
                    Room Details
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>PAYMENTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_view_payments.php">
                    <i class="fas fa-money-bill-wave"></i>
                    Payment History
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>COMPLAINTS</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_raise_complaint.php">
                    <i class="fas fa-plus-circle"></i>
                    Raise Complaint
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_view_complaints.php">
                    <i class="fas fa-tools"></i>
                    My Complaints
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white-50">
                    <span>ACCOUNT</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="student_change_password.php">
                    <i class="fas fa-key"></i>
                    Change Password
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
