-- Smart Hostel Room Allocation and Maintenance Management System
-- Database Schema

-- Create Database
CREATE DATABASE IF NOT EXISTS hostel_management_system;
USE hostel_management_system;

-- 1. ADMIN TABLE (Only one admin - Hostel Owner)
CREATE TABLE admin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- 2. STAFF TABLE
CREATE TABLE staff (
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    designation VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES admin(admin_id) ON DELETE RESTRICT
);

-- 3. HOSTEL TABLE
CREATE TABLE hostel (
    hostel_id INT PRIMARY KEY AUTO_INCREMENT,
    hostel_name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    total_rooms INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. ROOM TABLE
CREATE TABLE room (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    hostel_id INT NOT NULL,
    room_number VARCHAR(20) UNIQUE NOT NULL,
    room_type ENUM('single', 'double', 'triple', 'quadruple') NOT NULL,
    capacity INT NOT NULL,
    current_occupancy INT DEFAULT 0,
    floor_number INT NOT NULL,
    room_status ENUM('available', 'occupied', 'maintenance', 'reserved') DEFAULT 'available',
    monthly_rent DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hostel_id) REFERENCES hostel(hostel_id) ON DELETE CASCADE,
    CHECK (current_occupancy <= capacity),
    CHECK (capacity > 0)
);

-- 5. STUDENT APPLICATION TABLE
CREATE TABLE student_application (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    guardian_phone VARCHAR(15) NOT NULL,
    guardian_relation VARCHAR(50) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    institution_name VARCHAR(150) NOT NULL,
    year_of_study VARCHAR(20) NOT NULL,
    id_proof_type VARCHAR(50) NOT NULL,
    id_proof_number VARCHAR(50) NOT NULL,
    application_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    remarks TEXT,
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_date TIMESTAMP NULL,
    processed_by INT NULL,
    FOREIGN KEY (processed_by) REFERENCES admin(admin_id) ON DELETE SET NULL
);

-- 6. STUDENT TABLE (Active Students Only)
CREATE TABLE student (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    application_id INT NULL,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    guardian_phone VARCHAR(15) NOT NULL,
    guardian_relation VARCHAR(50) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    institution_name VARCHAR(150) NOT NULL,
    year_of_study VARCHAR(20) NOT NULL,
    id_proof_type VARCHAR(50) NOT NULL,
    id_proof_number VARCHAR(50) NOT NULL,
    admission_date DATE NOT NULL,
    student_status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    FOREIGN KEY (application_id) REFERENCES student_application(application_id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES staff(staff_id) ON DELETE RESTRICT
);

-- 7. ARCHIVED STUDENT TABLE
CREATE TABLE archived_student (
    archived_id INT PRIMARY KEY AUTO_INCREMENT,
    original_student_id INT NOT NULL,
    username VARCHAR(50) NOT NULL,
    application_id INT NULL,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    guardian_phone VARCHAR(15) NOT NULL,
    guardian_relation VARCHAR(50) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    institution_name VARCHAR(150) NOT NULL,
    year_of_study VARCHAR(20) NOT NULL,
    id_proof_type VARCHAR(50) NOT NULL,
    id_proof_number VARCHAR(50) NOT NULL,
    admission_date DATE NOT NULL,
    leaving_date DATE NOT NULL,
    leaving_reason TEXT,
    created_by INT NOT NULL,
    admission_created_at TIMESTAMP NOT NULL,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    archived_by INT NOT NULL,
    FOREIGN KEY (archived_by) REFERENCES staff(staff_id) ON DELETE RESTRICT
);

-- 8. ROOM ALLOCATION TABLE
CREATE TABLE room_allocation (
    allocation_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    allocation_date DATE NOT NULL,
    allocation_status ENUM('active', 'deallocated') DEFAULT 'active',
    deallocate_date DATE NULL,
    allocated_by INT NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES room(room_id) ON DELETE RESTRICT,
    FOREIGN KEY (allocated_by) REFERENCES staff(staff_id) ON DELETE RESTRICT
);

-- 9. PAYMENT TABLE
CREATE TABLE payment (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    payment_type ENUM('admission_fee', 'monthly_rent', 'maintenance', 'security_deposit', 'other') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_month VARCHAR(20) NULL,
    payment_year INT NULL,
    payment_mode ENUM('cash', 'online', 'cheque', 'card') NOT NULL,
    transaction_id VARCHAR(100) NULL,
    payment_status ENUM('paid', 'pending', 'partial') DEFAULT 'paid',
    remarks TEXT,
    received_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (received_by) REFERENCES staff(staff_id) ON DELETE RESTRICT,
    CHECK (amount > 0)
);

-- 10. COMPLAINT TABLE
CREATE TABLE complaint (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    complaint_type ENUM('electrical', 'plumbing', 'furniture', 'cleanliness', 'wifi', 'other') NOT NULL,
    complaint_title VARCHAR(150) NOT NULL,
    complaint_description TEXT NOT NULL,
    complaint_status ENUM('pending', 'in_progress', 'resolved', 'closed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    complaint_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_date TIMESTAMP NULL,
    resolved_by INT NULL,
    resolution_remarks TEXT,
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES room(room_id) ON DELETE RESTRICT,
    FOREIGN KEY (resolved_by) REFERENCES staff(staff_id) ON DELETE SET NULL
);

-- CREATE INDEXES FOR BETTER PERFORMANCE
CREATE INDEX idx_staff_status ON staff(status);
CREATE INDEX idx_student_status ON student(student_status);
CREATE INDEX idx_room_status ON room(room_status);
CREATE INDEX idx_application_status ON student_application(application_status);
CREATE INDEX idx_allocation_status ON room_allocation(allocation_status);
CREATE INDEX idx_payment_student ON payment(student_id);
CREATE INDEX idx_complaint_status ON complaint(complaint_status);
CREATE INDEX idx_complaint_student ON complaint(student_id);

-- INSERT DEFAULT ADMIN (Password: admin123)
-- Password is hashed using PHP password_hash() function
INSERT INTO admin (username, password, full_name, email, phone) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@hostel.com', '9876543210');

-- INSERT DEFAULT HOSTEL
INSERT INTO hostel (hostel_name, address, city, state, pincode, contact_number, email, total_rooms)
VALUES ('Smart Hostel', '123 College Road, University Area', 'Mumbai', 'Maharashtra', '400001', '022-12345678', 'contact@smarthostel.com', 0);

-- TRIGGERS

-- Trigger to update room occupancy when allocation is made
DELIMITER //
CREATE TRIGGER after_room_allocation_insert
AFTER INSERT ON room_allocation
FOR EACH ROW
BEGIN
    IF NEW.allocation_status = 'active' THEN
        UPDATE room 
        SET current_occupancy = current_occupancy + 1,
            room_status = CASE 
                WHEN current_occupancy + 1 >= capacity THEN 'occupied'
                ELSE 'available'
            END
        WHERE room_id = NEW.room_id;
    END IF;
END//

-- Trigger to update room occupancy when allocation is deallocated
DELIMITER //
CREATE TRIGGER after_room_allocation_update
AFTER UPDATE ON room_allocation
FOR EACH ROW
BEGIN
    IF OLD.allocation_status = 'active' AND NEW.allocation_status = 'deallocated' THEN
        UPDATE room 
        SET current_occupancy = GREATEST(0, current_occupancy - 1),
            room_status = CASE 
                WHEN current_occupancy - 1 < capacity THEN 'available'
                ELSE room_status
            END
        WHERE room_id = NEW.room_id;
    END IF;
END//
DELIMITER ;

-- VIEWS FOR REPORTS

-- View: Student Details with Room Information
CREATE VIEW vw_student_details AS
SELECT 
    s.student_id,
    s.username,
    s.full_name,
    s.email,
    s.phone,
    s.course_name,
    s.institution_name,
    s.year_of_study,
    s.admission_date,
    s.student_status,
    r.room_number,
    r.room_type,
    r.floor_number,
    ra.allocation_date,
    TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
FROM student s
LEFT JOIN room_allocation ra ON s.student_id = ra.student_id AND ra.allocation_status = 'active'
LEFT JOIN room r ON ra.room_id = r.room_id;

-- View: Room Occupancy Report
CREATE VIEW vw_room_occupancy AS
SELECT 
    r.room_id,
    r.room_number,
    r.room_type,
    r.capacity,
    r.current_occupancy,
    r.room_status,
    r.floor_number,
    r.monthly_rent,
    (r.capacity - r.current_occupancy) as available_beds,
    ROUND((r.current_occupancy / r.capacity) * 100, 2) as occupancy_percentage
FROM room r;

-- View: Payment Summary
CREATE VIEW vw_payment_summary AS
SELECT 
    s.student_id,
    s.full_name,
    s.email,
    s.phone,
    COUNT(p.payment_id) as total_payments,
    SUM(p.amount) as total_amount_paid,
    MAX(p.payment_date) as last_payment_date
FROM student s
LEFT JOIN payment p ON s.student_id = p.student_id
GROUP BY s.student_id, s.full_name, s.email, s.phone;

-- View: Complaint Status Report
CREATE VIEW vw_complaint_report AS
SELECT 
    c.complaint_id,
    s.full_name as student_name,
    s.phone as student_phone,
    r.room_number,
    c.complaint_type,
    c.complaint_title,
    c.complaint_status,
    c.priority,
    c.complaint_date,
    c.resolved_date,
    st.full_name as resolved_by_name,
    DATEDIFF(COALESCE(c.resolved_date, CURDATE()), c.complaint_date) as days_pending
FROM complaint c
JOIN student s ON c.student_id = s.student_id
JOIN room r ON c.room_id = r.room_id
LEFT JOIN staff st ON c.resolved_by = st.staff_id;

-- STORED PROCEDURES

-- Procedure to archive a student
DELIMITER //
CREATE PROCEDURE sp_archive_student(
    IN p_student_id INT,
    IN p_leaving_reason TEXT,
    IN p_archived_by INT
)
BEGIN
    DECLARE v_count INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error archiving student';
    END;
    
    START TRANSACTION;
    
    -- Check if student exists
    SELECT COUNT(*) INTO v_count FROM student WHERE student_id = p_student_id;
    
    IF v_count = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Student not found';
    END IF;
    
    -- Insert into archived_student
    INSERT INTO archived_student (
        original_student_id, username, application_id, full_name, date_of_birth,
        gender, email, phone, address, city, state, pincode,
        guardian_name, guardian_phone, guardian_relation,
        course_name, institution_name, year_of_study,
        id_proof_type, id_proof_number, admission_date,
        leaving_date, leaving_reason, created_by, admission_created_at, archived_by
    )
    SELECT 
        student_id, username, application_id, full_name, date_of_birth,
        gender, email, phone, address, city, state, pincode,
        guardian_name, guardian_phone, guardian_relation,
        course_name, institution_name, year_of_study,
        id_proof_type, id_proof_number, admission_date,
        CURDATE(), p_leaving_reason, created_by, created_at, p_archived_by
    FROM student
    WHERE student_id = p_student_id;
    
    -- Deallocate room
    UPDATE room_allocation 
    SET allocation_status = 'deallocated', 
        deallocate_date = CURDATE()
    WHERE student_id = p_student_id 
    AND allocation_status = 'active';
    
    -- Delete student record
    DELETE FROM student WHERE student_id = p_student_id;
    
    COMMIT;
END//
DELIMITER ;

-- Grant permissions (adjust as needed for your MySQL user)
-- GRANT ALL PRIVILEGES ON hostel_management_system.* TO 'hostel_user'@'localhost' IDENTIFIED BY 'hostel_password';
-- FLUSH PRIVILEGES;
