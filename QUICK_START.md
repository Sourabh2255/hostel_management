# QUICK START GUIDE
## Get Your Hostel Management System Running in 15 Minutes!

---

## 🚀 Super Quick Setup (For Beginners)

### Prerequisites
- Windows Computer
- Internet Connection
- 30 minutes of time

---

## STEP 1: Install XAMPP (5 minutes)

1. Download XAMPP: https://www.apachefriends.org/download.html
2. Run installer → Click "Next" → "Next" → "Install"
3. Open XAMPP Control Panel
4. Click "Start" for Apache
5. Click "Start" for MySQL
6. Both should show GREEN

✅ **Test:** Open browser, go to `http://localhost` - You should see XAMPP page

---

## STEP 2: Setup Database (3 minutes)

1. Open: `http://localhost/phpmyadmin`
2. Click "New" (left sidebar)
3. Database name: `hostel_management_system`
4. Click "Create"
5. Click on the database name
6. Click "Import" tab
7. Click "Choose File"
8. Select `database/schema.sql` from project folder
9. Click "Go"
10. Wait for success message

✅ **Test:** You should see 10 tables in left sidebar

---

## STEP 3: Put Files in Correct Location (2 minutes)

1. Extract the project ZIP file
2. Copy the entire `hostel_management` folder
3. Paste it here: `C:\xampp\htdocs\`
4. Final path should be: `C:\xampp\htdocs\hostel_management\`

---

## STEP 4: Run the Application (1 minute)

1. Open browser
2. Go to: `http://localhost/hostel_management`
3. You should see the login page!

---

## STEP 5: Login & Test (4 minutes)

### Test Admin Login
```
User Type: Admin (Hostel Owner)
Username: admin
Password: admin123
```

Click "Login" → You should see Admin Dashboard!

### Test Application Form
1. From login page, click "Apply for Hostel Admission"
2. Fill the form with any test data
3. Submit
4. Login as admin to see the application

---

## 🎉 CONGRATULATIONS!

Your hostel management system is now running!

---

## What to Do Next?

### 1. Create Staff Account (as Admin)
- Login as admin
- Go to "Add New Staff"
- Fill details and submit
- Note the generated username and password

### 2. Test Staff Login
- Logout from admin
- Login with staff credentials
- Explore staff features

### 3. Add Test Student
- As staff, go to "Add New Student"
- Fill details
- System generates login credentials
- Note them down

### 4. Test Student Login
- Logout from staff
- Login with student credentials
- Explore student features

---

## Common URLs

| Purpose | URL |
|---------|-----|
| Application | http://localhost/hostel_management |
| phpMyAdmin | http://localhost/phpmyadmin |
| Application Form | http://localhost/hostel_management/pages/apply.php |

---

## Default Passwords

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Staff | (created by admin) | (set by admin) |
| Student | (created by staff) | (auto-generated) |

---

## Optional: Add Sample Data

Want to test with some pre-filled data?

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `hostel_management_system` database
3. Click "Import"
4. Choose `database/sample_data.sql`
5. Click "Go"

This adds:
- 15 Sample Rooms
- 3 Staff Members (password: staff123)
- 2 Students (password: student123)
- 2 Pending Applications
- Sample Payments
- Sample Complaints

**Sample Login After Importing:**
```
Username: staff1
Password: staff123

Username: arjunsingh
Password: student123
```

---

## Troubleshooting

### Problem: Can't access localhost/hostel_management
**Fix:** 
- Check if Apache is running (GREEN in XAMPP)
- Try: `http://127.0.0.1/hostel_management`

### Problem: Database connection error
**Fix:**
- Check if MySQL is running (GREEN in XAMPP)
- Verify database name is correct in `config/database.php`

### Problem: Login not working
**Fix:**
- Clear browser cache (Ctrl + Shift + Delete)
- Try different browser
- Verify database has admin record

### Problem: Blank page
**Fix:**
- Check Apache error log: `C:\xampp\apache\logs\error.log`
- Enable errors: Add to top of `index.php`:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```

---

## Need More Help?

📖 **Full Guide:** See `INSTALLATION_GUIDE.md`

📖 **Complete Documentation:** See `README.md`

---

## Security Reminder ⚠️

**Before deploying online:**
1. Change admin password
2. Use strong passwords
3. Enable HTTPS
4. Update database credentials

---

## Features to Explore

### Admin Panel
✓ Manage staff
✓ Approve/reject applications
✓ View all students
✓ Allocate rooms
✓ Track payments
✓ View complaints
✓ Generate reports

### Staff Panel
✓ Add students
✓ Allocate rooms
✓ Record payments
✓ Handle complaints
✓ View applications

### Student Panel
✓ View profile
✓ Check room details
✓ View payment history
✓ Raise complaints
✓ Track complaint status

---

## Success Checklist

- [ ] XAMPP installed and running
- [ ] Database created
- [ ] SQL imported successfully
- [ ] Files in htdocs folder
- [ ] Can access login page
- [ ] Admin login works
- [ ] Can submit application
- [ ] Dashboard loads properly

---

**All Done! Your system is ready to use! 🎊**

For deployment to internet, see `INSTALLATION_GUIDE.md` Part 5.

---

Happy Managing! 😊
