===========================================================
HERALD COLLEGE KATHMANDU - NAMASTE VIDYALAYA SCHOOL Record MANAGEMENT SYSTEM
Student Name: Sriyog Dhital
Student ID: NP03CS4A240201
Website URL: https://student.heraldcollege.edu.np/~NP03CS4A240201/public/login.php
===========================================================

1. LOGIN CREDENTIALS
----------------------------------------
ADMIN ACCESS:
- Email: admin@namaste.edu
- Password: admin123

USER ACCESS (Student):
- Email: [Create via Admin Panel or Signup]
- Password: [User defined]

2. SYSTEM SETUP INSTRUCTIONS
———————————————————--
DATABASE SETUP:
- Database is auto-created on first run via config/db.php
- Tables: students, courses, attendance
- Default admin account is auto-inserted

FILE PERMISSIONS:
- Ensure 'assets/', 'config/', 'includes/', 'public/' directories 
  have proper read permissions (755)
- No special upload directories required (no file uploads)

3. KEY FEATURES
----------------------------------------
- ROLE-BASED ACCESS: Admin vs Student dashboards with separate menus
- REAL-TIME ATTENDANCE: Students check in daily, admin sees live count
- COURSE MANAGEMENT: Students submit course completions, admin verifies
- DRAFT SYSTEM: Students can save records as draft before submitting
- AJAX-POWERED: Live search, instant updates without page reload
- SECURITY: CSRF tokens, SQL injection protection, XSS prevention
- RESPONSIVE DESIGN: Works on mobile and desktop

ADMIN FEATURES:
- Dashboard with live stats (Students, Total Records, Pending, Present Today)
- Student CRUD (Create, Read, Update, Delete)
- Attendance register with date search
- Course verification (Verify/Reject student submissions)

STUDENT FEATURES:
- Daily attendance check-in
- Submit course completions (saved as Draft first)
- Edit drafts before submitting to admin
- View attendance history
- Profile management

4. KNOWN ISSUES / FUTURE IMPROVEMENTS
———————————————————--
- ATTENDANCE HISTORY: Currently shows only present dates. Could add 
  absent marking and monthly reports.
- EMAIL NOTIFICATIONS: No email alerts for verifications (can be added 
  using PHPMailer in future versions).
- PROFILE PICTURES: Currently uses initials avatar. File upload for 
  profile photos can be added.
- PASSWORD RESET: No "Forgot Password" feature implemented yet.

===========================================================