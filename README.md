# 🚗 TamHang Tourist - Car Booking Website

TamHang Tourist is an online car booking system for tourism, supporting 4-seat, 7-seat, and 16-seat vehicles. It provides a full suite of features for route management, car booking, payment, and service ratings.

## 📑 Table of Contents
- [Project Description](#project-description)
- [Technologies Used](#technologies-used)
- [Requirements](#requirements)
- [Installation Instructions](#installation-instructions)
- [Usage Instructions](#usage-instructions)
- [Documentation](#documentation)
- [Support Information](#support-information)
- [Contribution Guidelines](#contribution-guidelines)
- [Acknowledgments](#acknowledgments)
- [Conclusion](#conclusion)

## 📝 Project Description
This system allows users to:
- Register/Login with email verification.
- Book cars based on routes, car type, and schedule.
- Manage bookings (view history, cancel bookings).
- Make payments online via **VietQR** or cash.
- Rate services (1–5 stars with comments).
- Update personal profile and change password.

**Admin** can manage vehicles, routes, bookings, and customer ratings, with a dashboard for statistics and analytics.

## 🛠 Technologies Used
- **Backend**: PHP (core)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Email Service**: PHPMailer
- **Authentication**: Session-based & Token-based Email Verification
- **Security**:
  - Password hashing using `password_hash()` and `password_verify()`.
  - Prepared Statements to prevent SQL Injection.

## ⚙ Requirements
- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Apache** or **Nginx**
- **Composer** (if needed for libraries like PHPMailer)

## 📥 Installation Instructions
1. **Clone Project**
   ```bash
   git clone https://github.com/<your-repo>/CarBooking_Website.git
   cd CarBooking_Website
   ```

2. **Database Setup**
   - Create a database named `car_booking`.
   - Import the SQL file:
     ```sql
     CREATE DATABASE car_booking;
     USE car_booking;
     ```
     (See `Code_SQL.docx` for the full database schema.)
   - Update database credentials in `config/config.php` and `config/db.php`:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'car_booking');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

3. **Email Configuration**
   - Edit `config.ini`:
     ```ini
     [mail]
     host = smtp.gmail.com
     username = your-email@gmail.com
     app_password = your-app-password
     port = 587
     from_name = "TamHang Tourist"
     ```

4. **Run the Server**
   - Place the project folder in `htdocs` (XAMPP) or `/var/www/html` (Linux).
   - Visit: `http://localhost/CarBooking_Website`

## ▶ Usage Instructions
**For Users:**
- Register and verify email (verification link expires after 1 minute).
- Book a car: Select route, vehicle type, and schedule.
- Make payments: VietQR or cash.
- Rate services: 1–5 stars with detailed comments.

**For Admin:**
- Manage vehicles: Add/edit/delete (with image upload).
- Manage routes: Add/edit/delete.
- Manage bookings: Review, confirm, cancel, or complete.
- Monitor customer ratings and view analytics in the Dashboard.

## 📚 Documentation
- **Class Diagram**: See `Class.docx`.
- **ERD & Database Design**: See `ERD.docx`.
- **Project Structure**: See `Path.docx`.
- **Functional & Non-Functional Requirements**: See `Chức năng và phi chức năng.docx`.

## 🆘 Support Information
- **Hotline**: +84 36.727.8495 – +84 36.642.6365
- **Email**: tamhangtourist83@gmail.com
- **Address**: 368C, Khu vực I, Ba Láng, Cái Răng, Cần Thơ, Vietnam.

## 🤝 Contribution Guidelines
- Fork the repository and create a new branch: `feature/<feature-name>`.
- Commit messages follow Conventional Commits:
  - `feat`: for new features.
  - `fix`: for bug fixes.
  - `docs`: for documentation updates.
  - `refactor`: for code refactoring.
- Submit a Pull Request with a detailed description.

## 🙏 Acknowledgments
- Bootstrap 5 & Font Awesome for UI components.
- PHPMailer for email sending.
- The Vietnamese PHP/MySQL community for resources and inspiration.

## ✅ Conclusion
TamHang Tourist delivers a convenient, secure, and modern online car booking platform, designed for tourists and professional transportation services.