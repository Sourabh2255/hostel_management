-- Sample Test Data for Smart Hostel Management System
-- This file contains sample data for testing purposes
-- Run this AFTER running schema.sql

USE hostel_management_system;

-- Insert sample hostel (if not exists)
INSERT INTO hostel (hostel_name, address, city, state, pincode, contact_number, email, total_rooms)
VALUES ('Smart Hostel', '123 College Road, University Area', 'Mumbai', 'Maharashtra', '400001', '022-12345678', 'contact@smarthostel.com', 20)
ON DUPLICATE KEY UPDATE hostel_id=hostel_id;

-- Get hostel_id
SET @hostel_id = (SELECT hostel_id FROM hostel LIMIT 1);

-- Insert sample rooms
INSERT INTO room (hostel_id, room_number, room_type, capacity, floor_number, room_status, monthly_rent) VALUES
(@hostel_id, '101', 'single', 1, 1, 'available', 5000.00),
(@hostel_id, '102', 'double', 2, 1, 'available', 4000.00),
(@hostel_id, '103', 'double', 2, 1, 'available', 4000.00),
(@hostel_id, '104', 'triple', 3, 1, 'available', 3500.00),
(@hostel_id, '105', 'triple', 3, 1, 'available', 3500.00),
(@hostel_id, '201', 'single', 1, 2, 'available', 5500.00),
(@hostel_id, '202', 'double', 2, 2, 'available', 4500.00),
(@hostel_id, '203', 'double', 2, 2, 'available', 4500.00),
(@hostel_id, '204', 'quadruple', 4, 2, 'available', 3000.00),
(@hostel_id, '205', 'quadruple', 4, 2, 'available', 3000.00),
(@hostel_id, '301', 'single', 1, 3, 'available', 6000.00),
(@hostel_id, '302', 'double', 2, 3, 'available', 5000.00),
(@hostel_id, '303', 'triple', 3, 3, 'available', 4000.00),
(@hostel_id, '304', 'triple', 3, 3, 'available', 4000.00),
(@hostel_id, '305', 'quadruple', 4, 3, 'available', 3500.00);

-- Update total rooms count
UPDATE hostel SET total_rooms = (SELECT COUNT(*) FROM room WHERE hostel_id = @hostel_id) WHERE hostel_id = @hostel_id;

-- Insert sample staff members
-- Password for all staff: staff123
INSERT INTO staff (username, password, full_name, email, phone, designation, status, created_by) VALUES
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rajesh Kumar', 'rajesh.kumar@hostel.com', '9876543211', 'Hostel Warden', 'active', 1),
('staff2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Priya Sharma', 'priya.sharma@hostel.com', '9876543212', 'Assistant Manager', 'active', 1),
('staff3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amit Patel', 'amit.patel@hostel.com', '9876543213', 'Maintenance Staff', 'active', 1);

-- Insert sample student applications
INSERT INTO student_application (
    full_name, date_of_birth, gender, email, phone, address, city, state, pincode,
    guardian_name, guardian_phone, guardian_relation, course_name, institution_name,
    year_of_study, id_proof_type, id_proof_number, application_status
) VALUES
('Rahul Verma', '2003-05-15', 'male', 'rahul.verma@email.com', '9876543220', '45 MG Road', 'Mumbai', 'Maharashtra', '400002', 'Suresh Verma', '9876543230', 'father', 'B.Tech Computer Science', 'Mumbai University', '1st Year', 'aadhar', '123456789012', 'pending'),
('Sneha Reddy', '2002-08-22', 'female', 'sneha.reddy@email.com', '9876543221', '78 Park Street', 'Mumbai', 'Maharashtra', '400003', 'Lakshmi Reddy', '9876543231', 'mother', 'B.Tech Information Technology', 'Mumbai University', '2nd Year', 'aadhar', '234567890123', 'pending'),
('Arjun Singh', '2003-01-10', 'male', 'arjun.singh@email.com', '9876543222', '12 Station Road', 'Mumbai', 'Maharashtra', '400004', 'Vikram Singh', '9876543232', 'father', 'B.Sc Physics', 'Mumbai University', '1st Year', 'aadhar', '345678901234', 'approved'),
('Ananya Gupta', '2002-11-30', 'female', 'ananya.gupta@email.com', '9876543223', '56 Hill Road', 'Mumbai', 'Maharashtra', '400005', 'Rajesh Gupta', '9876543233', 'father', 'B.Com', 'Mumbai University', '3rd Year', 'aadhar', '456789012345', 'approved');

-- Insert sample students (approved applications)
-- Password for all students: student123
INSERT INTO student (
    username, password, application_id, full_name, date_of_birth, gender, email, phone,
    address, city, state, pincode, guardian_name, guardian_phone, guardian_relation,
    course_name, institution_name, year_of_study, id_proof_type, id_proof_number,
    admission_date, student_status, created_by
) VALUES
('arjunsingh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'Arjun Singh', '2003-01-10', 'male', 'arjun.singh@email.com', '9876543222',
    '12 Station Road', 'Mumbai', 'Maharashtra', '400004', 'Vikram Singh', '9876543232', 'father',
    'B.Sc Physics', 'Mumbai University', '1st Year', 'aadhar', '345678901234',
    '2024-06-15', 'active', 1),
('ananyagupta', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'Ananya Gupta', '2002-11-30', 'female', 'ananya.gupta@email.com', '9876543223',
    '56 Hill Road', 'Mumbai', 'Maharashtra', '400005', 'Rajesh Gupta', '9876543233', 'father',
    'B.Com', 'Mumbai University', '3rd Year', 'aadhar', '456789012345',
    '2024-06-15', 'active', 1);

-- Get student IDs and room IDs for allocations
SET @student1_id = (SELECT student_id FROM student WHERE username = 'arjunsingh');
SET @student2_id = (SELECT student_id FROM student WHERE username = 'ananyagupta');
SET @room1_id = (SELECT room_id FROM room WHERE room_number = '102');
SET @room2_id = (SELECT room_id FROM room WHERE room_number = '103');

-- Allocate rooms to students
INSERT INTO room_allocation (student_id, room_id, allocation_date, allocation_status, allocated_by, remarks)
VALUES
(@student1_id, @room1_id, '2024-06-15', 'active', 1, 'Initial allocation'),
(@student2_id, @room2_id, '2024-06-15', 'active', 1, 'Initial allocation');

-- Insert sample payments
INSERT INTO payment (student_id, payment_type, amount, payment_date, payment_month, payment_year, payment_mode, transaction_id, payment_status, received_by)
VALUES
(@student1_id, 'admission_fee', 5000.00, '2024-06-15', NULL, 2024, 'online', 'TXN1234567890', 'paid', 1),
(@student1_id, 'monthly_rent', 4000.00, '2024-07-01', 'July', 2024, 'online', 'TXN1234567891', 'paid', 1),
(@student1_id, 'monthly_rent', 4000.00, '2024-08-01', 'August', 2024, 'cash', NULL, 'paid', 1),
(@student2_id, 'admission_fee', 5000.00, '2024-06-15', NULL, 2024, 'online', 'TXN1234567892', 'paid', 1),
(@student2_id, 'monthly_rent', 4000.00, '2024-07-01', 'July', 2024, 'card', 'TXN1234567893', 'paid', 1),
(@student2_id, 'monthly_rent', 4000.00, '2024-08-01', 'August', 2024, 'online', 'TXN1234567894', 'paid', 1);

-- Insert sample complaints
INSERT INTO complaint (student_id, room_id, complaint_type, complaint_title, complaint_description, complaint_status, priority)
VALUES
(@student1_id, @room1_id, 'electrical', 'Light not working', 'The ceiling light in my room is not working. Needs immediate attention.', 'pending', 'high'),
(@student2_id, @room2_id, 'plumbing', 'Water leakage', 'There is water leakage from the bathroom tap.', 'in_progress', 'medium'),
(@student1_id, @room1_id, 'wifi', 'Slow internet', 'Internet speed is very slow in room 102.', 'resolved', 'low');

-- Insert sample archived student
INSERT INTO archived_student (
    original_student_id, username, application_id, full_name, date_of_birth, gender, email, phone,
    address, city, state, pincode, guardian_name, guardian_phone, guardian_relation,
    course_name, institution_name, year_of_study, id_proof_type, id_proof_number,
    admission_date, leaving_date, leaving_reason, created_by, admission_created_at, archived_by
) VALUES
(999, 'rohanmehta', NULL, 'Rohan Mehta', '2001-03-20', 'male', 'rohan.mehta@email.com', '9876543224',
    '89 Lake Road', 'Mumbai', 'Maharashtra', '400006', 'Sunil Mehta', '9876543234', 'father',
    'MBA', 'Mumbai University', '2nd Year', 'aadhar', '567890123456',
    '2023-06-15', '2024-05-31', 'Course completed', 1, '2023-06-15 10:00:00', 1);

-- Update room occupancy (this is done automatically by triggers, but included for completeness)
UPDATE room SET current_occupancy = (
    SELECT COUNT(*) FROM room_allocation 
    WHERE room_allocation.room_id = room.room_id AND allocation_status = 'active'
), room_status = CASE 
    WHEN current_occupancy >= capacity THEN 'occupied'
    ELSE 'available'
END;

-- Summary
SELECT 'Sample data inserted successfully!' as Status;
SELECT COUNT(*) as 'Total Rooms' FROM room;
SELECT COUNT(*) as 'Total Staff' FROM staff;
SELECT COUNT(*) as 'Total Students' FROM student;
SELECT COUNT(*) as 'Pending Applications' FROM student_application WHERE application_status = 'pending';
SELECT COUNT(*) as 'Total Payments' FROM payment;
SELECT COUNT(*) as 'Total Complaints' FROM complaint;

-- Display test credentials
SELECT '=== TEST CREDENTIALS ===' as '';
SELECT 'Admin' as Role, 'admin' as Username, 'admin123' as Password
UNION ALL
SELECT 'Staff', 'staff1', 'staff123'
UNION ALL
SELECT 'Staff', 'staff2', 'staff123'
UNION ALL
SELECT 'Student', 'arjunsingh', 'student123'
UNION ALL
SELECT 'Student', 'ananyagupta', 'student123';
