# COMPLETE STEP-BY-STEP GUIDE
## Smart Hostel Management System - Installation & Deployment

---

## 📋 PART 1: LOCAL SETUP (XAMPP + WINDOWS)

### Step 1.1: Download and Install XAMPP

1. **Download XAMPP**
   - Open browser: https://www.apachefriends.org/download.html
   - Click "Download" for Windows version (PHP 8.x)
   - Wait for download (150MB approximately)

2. **Install XAMPP**
   - Double-click downloaded file `xampp-windows-x64-installer.exe`
   - Click "Next" on security warning
   - Select components (keep Apache, MySQL, PHP, phpMyAdmin checked)
   - Choose installation folder: `C:\xampp` (default - recommended)
   - Uncheck "Learn more about Bitnami"
   - Click "Next" → "Next" → "Finish"

3. **First Run**
   - XAMPP Control Panel opens automatically
   - If not, go to `C:\xampp` and run `xampp-control.exe`
   - Click "Start" next to Apache
   - Click "Start" next to MySQL
   - Both should show green "Running" status

### Step 1.2: Verify Installation

1. **Test Apache**
   - Open browser
   - Type: `http://localhost`
   - Should see XAMPP welcome page

2. **Test phpMyAdmin**
   - Type: `http://localhost/phpmyadmin`
   - Should see phpMyAdmin login page

---

## 📂 PART 2: PROJECT SETUP

### Step 2.1: Download Project Files

**Option A: If on GitHub**
```bash
# Open Command Prompt
cd C:\xampp\htdocs
git clone https://github.com/yourusername/hostel-management-system.git hostel_management
```

**Option B: Manual Download**
1. Download ZIP file
2. Extract to: `C:\xampp\htdocs\hostel_management`
3. Make sure folder structure looks like:
   ```
   C:\xampp\htdocs\hostel_management\
   ├── assets/
   ├── config/
   ├── database/
   ├── includes/
   ├── pages/
   ├── index.php
   └── README.md
   ```

### Step 2.2: Create Database

1. **Open phpMyAdmin**
   - Browser: `http://localhost/phpmyadmin`
   - Username: `root`
   - Password: (leave blank)
   - Click "Go"

2. **Create New Database**
   - Click "New" in left sidebar
   - Database name: `hostel_management_system`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

3. **Import Database Structure**
   - Click on `hostel_management_system` database (left sidebar)
   - Click "Import" tab (top menu)
   - Click "Choose File" button
   - Navigate to: `C:\xampp\htdocs\hostel_management\database\schema.sql`
   - Click "Go" at bottom
   - Wait for green success message: "Import has been successfully finished"

4. **Verify Import**
   - Click `hostel_management_system` in left sidebar
   - Should see 10 tables: admin, staff, student, etc.
   - Click "admin" table
   - Click "Browse" - should see 1 admin record

### Step 2.3: Configure Database Connection

1. **Open Database Config File**
   - Navigate to: `C:\xampp\htdocs\hostel_management\config\database.php`
   - Right-click → Open with → Notepad or any text editor

2. **Verify/Update Credentials**
   ```php
   define('DB_HOST', 'localhost');     // Keep as is
   define('DB_USER', 'root');          // Default XAMPP user
   define('DB_PASS', '');              // Empty for XAMPP
   define('DB_NAME', 'hostel_management_system');
   ```

3. **Save and Close**

---

## 🚀 PART 3: RUNNING THE APPLICATION

### Step 3.1: Start the Application

1. **Ensure Services are Running**
   - Open XAMPP Control Panel
   - Apache: Green "Running" status
   - MySQL: Green "Running" status

2. **Access Application**
   - Open browser
   - Type: `http://localhost/hostel_management`
   - Should see login page

### Step 3.2: Test Admin Login

1. **Login Credentials**
   - User Type: Select "Admin (Hostel Owner)"
   - Username: `admin`
   - Password: `admin123`
   - Click "Login"

2. **Expected Result**
   - Redirects to Admin Dashboard
   - Shows statistics and menu options

### Step 3.3: Test Student Application (No Login Required)

1. **Go to Application Page**
   - From login page, click "Apply for Hostel Admission"
   - OR directly: `http://localhost/hostel_management/pages/apply.php`

2. **Fill Application Form**
   - Enter all required details
   - Click "Submit Application"
   - Should see success message

3. **View Application as Admin**
   - Login as admin
   - Go to "Hostel Applications"
   - Should see the submitted application

---

## 🐙 PART 4: UPLOADING TO GITHUB

### Step 4.1: Install Git (If Not Installed)

1. **Download Git**
   - Visit: https://git-scm.com/download/win
   - Download 64-bit installer
   - Run installer with default settings

2. **Verify Installation**
   - Open Command Prompt
   - Type: `git --version`
   - Should show version number

### Step 4.2: Create GitHub Repository

1. **Create GitHub Account** (if you don't have one)
   - Visit: https://github.com
   - Click "Sign up"
   - Follow registration steps

2. **Create New Repository**
   - Click "+" icon (top right)
   - Select "New repository"
   - Repository name: `hostel-management-system`
   - Description: "Smart Hostel Room Allocation and Maintenance Management System"
   - Keep "Public" selected
   - DON'T initialize with README (we have one)
   - Click "Create repository"

3. **Copy Repository URL**
   - After creation, copy the HTTPS URL
   - Example: `https://github.com/yourusername/hostel-management-system.git`

### Step 4.3: Upload Code to GitHub

1. **Open Command Prompt**
   - Press `Windows + R`
   - Type `cmd` and press Enter

2. **Navigate to Project**
   ```bash
   cd C:\xampp\htdocs\hostel_management
   ```

3. **Initialize Git**
   ```bash
   git init
   ```

4. **Configure Git (First Time Only)**
   ```bash
   git config --global user.name "Your Name"
   git config --global user.email "your.email@example.com"
   ```

5. **Add All Files**
   ```bash
   git add .
   ```

6. **Commit Files**
   ```bash
   git commit -m "Initial commit - Smart Hostel Management System"
   ```

7. **Connect to GitHub**
   ```bash
   git remote add origin https://github.com/yourusername/hostel-management-system.git
   git branch -M main
   ```

8. **Push to GitHub**
   ```bash
   git push -u origin main
   ```
   - Enter GitHub username when prompted
   - Enter password or Personal Access Token

9. **Verify Upload**
   - Go to your GitHub repository page
   - Refresh - should see all files

### Step 4.4: Create GitHub Personal Access Token (If Password Doesn't Work)

1. **Generate Token**
   - GitHub → Click profile picture → Settings
   - Scroll down → Developer settings (left sidebar)
   - Personal access tokens → Tokens (classic)
   - Generate new token (classic)
   - Note: "Git operations"
   - Select "repo" checkbox
   - Scroll down → Generate token
   - COPY THE TOKEN (can't see it again!)

2. **Use Token as Password**
   - When git push asks for password
   - Paste the token instead

---

## 🌐 PART 5: FREE DEPLOYMENT (InfinityFree)

### Step 5.1: Create InfinityFree Account

1. **Sign Up**
   - Visit: https://infinityfree.net
   - Click "Sign Up"
   - Enter email and create password
   - Verify email

2. **Create Website**
   - After login, click "Create Account"
   - Choose subdomain: `yourhostel.infinityfreeapp.com`
   - OR use your own domain if you have one
   - Enter account label: "Hostel Management"
   - Click "Create Account"
   - Wait 2-3 minutes for activation

### Step 5.2: Get FTP Credentials

1. **Access Control Panel**
   - Click on your website in the accounts list
   - Go to "Accounts" → View your account

2. **Note These Details**
   - FTP Hostname: `ftpupload.net`
   - FTP Username: `epiz_XXXXXXXX`
   - FTP Password: (shown in control panel)
   - FTP Port: `21`

### Step 5.3: Upload Files Using FileZilla

1. **Download FileZilla**
   - Visit: https://filezilla-project.org/download.php?type=client
   - Download FileZilla Client
   - Install with default settings

2. **Connect to Server**
   - Open FileZilla
   - Top bar:
     - Host: `ftpupload.net`
     - Username: Your FTP username
     - Password: Your FTP password
     - Port: `21`
   - Click "Quickconnect"
   - Accept certificate if prompted

3. **Upload Files**
   - Left side: Navigate to `C:\xampp\htdocs\hostel_management`
   - Right side: Navigate to `/htdocs` folder
   - Select all files and folders on left
   - Right-click → Upload
   - Wait for upload to complete (may take 5-10 minutes)

### Step 5.4: Create Database on InfinityFree

1. **Create MySQL Database**
   - In InfinityFree control panel
   - Go to "MySQL Databases"
   - Click "Create Database"
   - Database name: `hostel`
   - Click "Create"

2. **Note Database Details**
   - Database name: `epiz_XXXXXXXX_hostel`
   - Database hostname: `sql***`.infinityfree.net
   - Database username: `epiz_XXXXXXXX`
   - Database password: (your password)

3. **Import Database**
   - In control panel, click "phpMyAdmin"
   - Login with database credentials
   - Select your database (left sidebar)
   - Click "Import" tab
   - Click "Choose File"
   - Select `schema.sql` from your computer
   - Click "Go"
   - Wait for success message

### Step 5.5: Update Configuration File

1. **Edit config/database.php on Server**
   - In InfinityFree control panel
   - Go to "File Manager" → "Online File Manager"
   - Navigate to `htdocs/config/database.php`
   - Right-click → Edit

2. **Update Database Credentials**
   ```php
   define('DB_HOST', 'sql***.infinityfree.net');  // Your DB hostname
   define('DB_USER', 'epiz_XXXXXXXX');             // Your DB username
   define('DB_PASS', 'your_password');             // Your DB password
   define('DB_NAME', 'epiz_XXXXXXXX_hostel');      // Your DB name
   ```

3. **Save Changes**

### Step 5.6: Test Your Live Website

1. **Access Website**
   - Open browser
   - Go to: `http://yourhostel.infinityfreeapp.com`
   - Should see login page

2. **Login**
   - Username: `admin`
   - Password: `admin123`
   - Should work!

---

## 🔧 PART 6: TROUBLESHOOTING

### Problem 1: Cannot Access localhost/hostel_management

**Solution:**
- Ensure Apache is running in XAMPP
- Check URL is exactly: `http://localhost/hostel_management`
- Try: `http://127.0.0.1/hostel_management`

### Problem 2: Database Connection Error

**Solution:**
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Verify database `hostel_management_system` exists
- Check `config/database.php` credentials
- Restart MySQL in XAMPP

### Problem 3: Login Not Working

**Solution:**
- Clear browser cache (Ctrl + Shift + Delete)
- Try different browser
- Verify admin record exists in database:
  ```sql
  SELECT * FROM admin WHERE username = 'admin';
  ```

### Problem 4: Blank Page After Login

**Solution:**
- Enable error reporting: Add to `index.php` top:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Check Apache error log: `C:\xampp\apache\logs\error.log`

### Problem 5: Cannot Upload to GitHub

**Solution:**
- Use Personal Access Token instead of password
- Or use GitHub Desktop application
- Check internet connection

### Problem 6: Free Hosting Site Not Working

**Solution:**
- Wait 24 hours after signup (propagation time)
- Check database credentials are correct
- Look for error messages in InfinityFree control panel
- Try using www: `http://www.yourhostel.infinityfreeapp.com`

### Problem 7: File Upload Issues

**Solution:**
- Check `uploads/` folder permissions
- Create folder if it doesn't exist
- In FileZilla, set folder permissions to 755

---

## 📞 NEED HELP?

### Common URLs
- **Local Application:** http://localhost/hostel_management
- **phpMyAdmin:** http://localhost/phpmyadmin
- **XAMPP Dashboard:** http://localhost/dashboard

### Check If Services Are Running
```bash
# Windows Command Prompt
netstat -an | find "80"     # Apache
netstat -an | find "3306"   # MySQL
```

### Reset Admin Password (if forgotten)
1. Open phpMyAdmin
2. Go to `admin` table
3. Click "Edit" for admin row
4. Replace password with: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
5. This sets password to: `admin123`

---

## ✅ CHECKLIST

### Before Running Locally:
- [ ] XAMPP installed
- [ ] Apache running (green in XAMPP)
- [ ] MySQL running (green in XAMPP)
- [ ] Database created
- [ ] SQL file imported
- [ ] Config file updated
- [ ] Can access http://localhost/hostel_management

### Before GitHub Upload:
- [ ] Git installed
- [ ] GitHub account created
- [ ] Repository created on GitHub
- [ ] .gitignore file present
- [ ] All sensitive data removed from config

### Before Deployment:
- [ ] Free hosting account created
- [ ] FTP credentials noted
- [ ] Files uploaded via FTP
- [ ] Database created on server
- [ ] SQL imported to server database
- [ ] Config file updated with server credentials
- [ ] Website accessible online

---

## 🎓 IMPORTANT NOTES

1. **Security:**
   - Change default admin password after first login
   - Never commit `config/database.php` with real credentials to GitHub
   - Use strong passwords for production

2. **Backups:**
   - Regularly backup database: phpMyAdmin → Export
   - Keep backup of files

3. **Updates:**
   - To update on GitHub:
   ```bash
   git add .
   git commit -m "Update description"
   git push
   ```

4. **Multiple Computers:**
   - Clone repository on new computer
   - Follow local setup steps
   - Import database

---

## 📚 ADDITIONAL RESOURCES

- XAMPP: https://www.apachefriends.org/
- Git: https://git-scm.com/
- GitHub: https://github.com/
- InfinityFree: https://infinityfree.net/
- FileZilla: https://filezilla-project.org/
- PHP Manual: https://www.php.net/manual/
- MySQL Tutorial: https://dev.mysql.com/doc/

---

**Good Luck with Your Project! 🎉**
