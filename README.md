# Smart Hostel Room Allocation and Maintenance Management System

A comprehensive web-based hostel management system built with PHP and MySQL that provides role-based access control for Admin, Staff, and Students with features for room allocation, payment tracking, and complaint management.

## 📋 Table of Contents
- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [User Roles & Access](#user-roles--access)
- [Running the Application](#running-the-application)
- [GitHub Upload Guide](#github-upload-guide)
- [Free Deployment Options](#free-deployment-options)
- [Project Structure](#project-structure)
- [Default Credentials](#default-credentials)
- [Troubleshooting](#troubleshooting)

## ✨ Features

### Admin Features
- Complete system control and management
- Staff recruitment and credential management
- Student application approval/rejection
- Room allocation and deallocation
- Payment management and tracking
- Complaint viewing and monitoring
- Comprehensive reporting system
- Archive management for past students

### Staff Features
- Add and update student records
- Room allocation to students
- Payment recording and updates
- Student credential generation
- Application processing
- Complaint handling
- View all student information

### Student Features
- Personal profile viewing
- Room allocation details
- Payment history tracking
- Make payments (record entry)
- Raise maintenance complaints
- Track complaint status
- Apply for hostel admission (public form)

## 🖥️ System Requirements

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache/Nginx web server
- Minimum 512MB RAM
- 50MB disk space

### Development Environment
- XAMPP / WAMP / LAMP / MAMP
- Code Editor (VS Code, Sublime Text, etc.)
- Modern web browser (Chrome, Firefox, Safari)

## 📦 Installation Guide

### Step 1: Install XAMPP (Windows)

1. **Download XAMPP**
   - Visit: https://www.apachefriends.org/
   - Download the latest version for Windows
   - Run the installer

2. **Install Components**
   - Select Apache, MySQL, PHP, and phpMyAdmin
   - Choose installation directory (default: C:\xampp)
   - Complete the installation

3. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

### Step 2: Download the Project

1. **Download from GitHub** (once uploaded)
   ```bash
   git clone https://github.com/yourusername/hostel-management-system.git
   ```

2. **Or manually download**
   - Download ZIP file
   - Extract to `C:\xampp\htdocs\hostel_management`

### Step 3: Database Setup

1. **Open phpMyAdmin**
   - Open browser and go to: http://localhost/phpmyadmin
   - Default username: `root`, password: (leave empty)

2. **Create Database**
   - Click "New" in the left sidebar
   - Database name: `hostel_management_system`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

3. **Import SQL File**
   - Select the `hostel_management_system` database
   - Click "Import" tab
   - Click "Choose File"
   - Navigate to `database/schema.sql`
   - Click "Go" to import
   - Wait for success message

### Step 4: Configure Database Connection

1. **Edit Configuration File**
   - Open `config/database.php`
   - Update credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');        // Your MySQL username
   define('DB_PASS', '');            // Your MySQL password
   define('DB_NAME', 'hostel_management_system');
   ```

2. **Save the file**

## 🎯 Running the Application

### Local Development

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

2. **Access the Application**
   - Open browser
   - Go to: http://localhost/hostel_management
   - or: http://localhost/hostel_management/index.php

3. **Login**
   - See [Default Credentials](#default-credentials) section

### Testing the Application

1. **Admin Login**
   - Username: `admin`
   - Password: `admin123`
   - Test all admin features

2. **Create Staff Account**
   - Login as admin
   - Go to "Add New Staff"
   - Create staff credentials

3. **Test Staff Login**
   - Logout from admin
   - Login with staff credentials

4. **Apply as Student**
   - Click "Apply for Hostel Admission"
   - Fill the application form
   - Submit

5. **Approve Application (as Admin/Staff)**
   - Login as admin or staff
   - View applications
   - Approve and create student account

6. **Test Student Login**
   - Login with student credentials

## 📁 Project Structure

```
hostel_management/
│
├── assets/
│   ├── css/
│   │   └── style.css                 # Custom styles
│   ├── js/
│   │   └── script.js                 # Custom JavaScript
│   └── images/
│       └── logo.png
│
├── config/
│   └── database.php                  # Database configuration
│
├── database/
│   └── schema.sql                    # Database schema with sample data
│
├── includes/
│   ├── auth.php                      # Authentication functions
│   ├── functions.php                 # Common utility functions
│   ├── admin_sidebar.php             # Admin navigation
│   ├── staff_sidebar.php             # Staff navigation
│   └── student_sidebar.php           # Student navigation
│
├── pages/
│   ├── admin_dashboard.php           # Admin dashboard
│   ├── staff_dashboard.php           # Staff dashboard
│   ├── student_dashboard.php         # Student dashboard
│   ├── apply.php                     # Public application form
│   ├── logout.php                    # Logout handler
│   └── [other pages...]
│
├── uploads/                          # File uploads directory
│
├── .gitignore                        # Git ignore file
├── README.md                         # This file
└── index.php                         # Login page
```

## 🔐 Default Credentials

### Admin Account
- **Username:** admin
- **Password:** admin123
- **Email:** admin@hostel.com

### Staff & Student Accounts
- Created by Admin/Staff through the system
- No default credentials

## 📤 GitHub Upload Guide

### Method 1: Using Git Command Line

1. **Install Git**
   - Download from: https://git-scm.com/downloads
   - Install with default settings

2. **Create GitHub Repository**
   - Go to: https://github.com
   - Click "New Repository"
   - Name: `hostel-management-system`
   - Click "Create Repository"

3. **Initialize Local Repository**
   ```bash
   cd C:\xampp\htdocs\hostel_management
   git init
   git add .
   git commit -m "Initial commit - Smart Hostel Management System"
   ```

4. **Connect to GitHub**
   ```bash
   git remote add origin https://github.com/yourusername/hostel-management-system.git
   git branch -M main
   git push -u origin main
   ```

### Method 2: Using GitHub Desktop

1. **Install GitHub Desktop**
   - Download from: https://desktop.github.com
   - Install and sign in

2. **Add Repository**
   - Click "File" → "Add Local Repository"
   - Choose project folder
   - Commit changes
   - Publish repository

### Create .gitignore File

Create `.gitignore` in the root directory:
```
# Configuration files with sensitive data
config/database.php

# Upload directories
uploads/*
!uploads/.gitkeep

# System files
.DS_Store
Thumbs.db
.idea/
*.log

# Temporary files
tmp/
temp/
*.tmp
```

## 🌐 Free Deployment Options

### Option 1: InfinityFree (Recommended for Beginners)

1. **Sign Up**
   - Visit: https://infinityfree.net
   - Create free account
   - Choose a subdomain or use your own

2. **Upload Files**
   - Access File Manager or use FTP
   - FTP Credentials provided in control panel
   - Upload all files to `htdocs` folder

3. **Create Database**
   - Go to MySQL Databases in control panel
   - Create new database
   - Note database name, username, password

4. **Import Database**
   - Open phpMyAdmin from control panel
   - Select your database
   - Import `database/schema.sql`

5. **Update Configuration**
   - Edit `config/database.php` with new credentials
   - Upload the updated file

6. **Access Website**
   - Visit: http://yourdomain.infinityfree.net

### Option 2: 000webhost

1. **Sign Up**
   - Visit: https://www.000webhost.com
   - Create free account

2. **Create Website**
   - Click "Create Website"
   - Choose name and password

3. **Upload Files**
   - Use File Manager or FTP
   - Upload to `public_html` folder

4. **Setup Database**
   - Create MySQL database from control panel
   - Import SQL file via phpMyAdmin

5. **Configure**
   - Update database credentials
   - Access your site

### Option 3: Heroku (For Advanced Users)

1. **Install Heroku CLI**
   - Visit: https://devcenter.heroku.com/articles/heroku-cli

2. **Prepare Application**
   - Add `Procfile` in root:
   ```
   web: vendor/bin/heroku-php-apache2
   ```

3. **Deploy**
   ```bash
   heroku login
   heroku create your-hostel-app
   git push heroku main
   ```

4. **Add MySQL Database**
   - Use ClearDB MySQL add-on
   - Configure database credentials

### Option 4: Netlify/Vercel (Static Hosting Only)
- Note: These don't support PHP/MySQL
- Would require converting to JAMstack

### Option 5: Local Network Access

1. **Find Your IP Address**
   ```bash
   ipconfig    # Windows
   ifconfig    # Linux/Mac
   ```

2. **Configure XAMPP**
   - Edit `httpd.conf`
   - Change `Listen 80` if needed
   - Restart Apache

3. **Access from Other Devices**
   - Other devices on same network
   - Use: http://YOUR_IP/hostel_management

## 🔧 Troubleshooting

### Common Issues

**1. Database Connection Error**
- Check MySQL service is running
- Verify database credentials in `config/database.php`
- Ensure database exists

**2. Page Not Found (404)**
- Check file is in correct directory
- Verify Apache is running
- Check .htaccess if using mod_rewrite

**3. Login Not Working**
- Clear browser cache and cookies
- Check session settings in php.ini
- Verify passwords are hashed correctly

**4. Cannot Upload Files**
- Check `uploads/` directory permissions
- Verify `upload_max_filesize` in php.ini
- Ensure directory exists

**5. Blank Page**
- Enable error reporting in PHP
- Check Apache error logs
- Verify all required files exist

### Enable Error Reporting

Add to top of `index.php` for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Check PHP Version
```php
<?php phpinfo(); ?>
```

## 📚 Additional Documentation

### Database Schema
- See `database/schema.sql` for complete structure
- 10 main tables with relationships
- Triggers for automatic updates
- Views for complex queries

### Security Features
- Password hashing using bcrypt
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Role-based access control (RBAC)
- Session management

### API Endpoints (Future Enhancement)
- RESTful API can be added for mobile apps
- Authentication using JWT tokens
- JSON responses

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is developed for educational purposes.

## 👥 Authors

- Your Name - Initial work

## 🙏 Acknowledgments

- Bootstrap for UI components
- Font Awesome for icons
- PHP and MySQL communities

## 📧 Support

For issues and questions:
- Create an issue on GitHub
- Email: your.email@example.com

## 🔄 Version History

- v1.0.0 (2024) - Initial Release
  - Admin, Staff, Student roles
  - Room management
  - Payment tracking
  - Complaint system
  - Application workflow
  - Archive system

## 📱 Browser Compatibility

- Chrome (Recommended)
- Firefox
- Safari
- Edge
- Opera

## 🎓 Academic Usage

This project demonstrates:
- Database normalization (3NF)
- CRUD operations
- Role-based access control
- Triggers and stored procedures
- Complex queries and joins
- Web security best practices
- Session management
- File structure organization

---

**Note:** Change default passwords before deploying to production!

For complete documentation, visit the GitHub repository.
