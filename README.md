# 🏥 HealthMate Kabarak — Clinic Appointment & Record System

A web-based clinic system that allows **students** to book medical appointments, **doctors** to manage private health records, and **admins** to moderate users and content. Built using **HTML**, **PHP**, **JavaScript**, **TailwindCSS**, **Google Fonts**, and **Google Icons**.

---

## 📌 Core Features

- ✅ Student Appointment Booking
- ✅ MySQL-Driven Medical Record System
- ✅ Role-Based Access Control (Student / Doctor / Admin)
- ✅ Notification & Reminder System
- ✅ Full CRUD for Medical Notes
- ✅ Admin Moderation & User Management

---

## 🧭 System Flow

Login/Register
↓
Dashboard
↓
┌────────────┬─────────────┬─────────────┐
│ Student    │ Doctor      │ Admin       │
├────────────┼─────────────┼─────────────┤
│ Book Appt  │ View Appts  │ View Users  │
│ View Hist  │ Add Notes   │ Moderate    │
│ Notify     │ View Hist   │ Manage Roles│
└────────────┴─────────────┴─────────────┘


---

## 🗂️ Folder Structure

/healthmate
│
├── index.php # Landing/Login page
├── dashboard.php # Role-based dashboard
├── /views # Role-specific pages
│ ├── student.php
│ ├── doctor.php
│ └── admin.php
│
├── /components # UI Components
│ ├── header.php
│
├── /includes # Logic & DB
│ ├── db.php
│ ├── auth.php
│ └── functions.php
│
├── /assets
│ ├── /index.css (Index Styling)
│ ├── /index.js (Extra Functionalities)


---

## 🛢️ Database Tables

### `users`
| Column     | Type     | Description                   |
|------------|----------|-------------------------------|
| id         | INT      | Primary Key                   |
| name       | VARCHAR  | User's Full Name              |
| email      | VARCHAR  | Login Email                   |
| password   | VARCHAR  | Hashed Password               |
| role       | ENUM     | student, doctor, admin        |
| reg_date   | DATETIME | Registration Date             |

---

### `appointments`
| Column     | Type     | Description                   |
|------------|----------|-------------------------------|
| id         | INT      | Primary Key                   |
| student_id | INT      | FK → users.id                 |
| doctor_id  | INT      | FK → users.id                 |
| date       | DATE     | Appointment Date              |
| time       | TIME     | Appointment Time              |
| status     | ENUM     | pending, approved, done, etc. |

---

### `medical_notes`
| Column     | Type     | Description                   |
|------------|----------|-------------------------------|
| id         | INT      | Primary Key                   |
| doctor_id  | INT      | FK → users.id                 |
| student_id | INT      | FK → users.id                 |
| note       | TEXT     | Medical Notes Content         |
| date_created | DATETIME | Note Timestamp              |

---

### `notifications`
| Column     | Type     | Description                   |
|------------|----------|-------------------------------|
| id         | INT      | Primary Key                   |
| user_id    | INT      | FK → users.id                 |
| message    | TEXT     | Notification Message          |
| read_status| BOOLEAN  | Read/Unread                   |
| created_at | DATETIME | Timestamp                     |

---

## 🛠 Tech Stack

| Area              | Tool/Language      |
|-------------------|--------------------|
| Frontend          | HTML, TailwindCSS  |
| Backend           | PHP                |
| Database          | MySQL              |
| Dynamic Behavior  | JavaScript         |
| UI Enhancements   | Google Fonts, Icons|

---

## 🧠 Developer Tips

- Use `$_SESSION` to manage logged-in roles.
- Use `include`/`require` to modularize pages.
- Use `switch-case` for location logic & cleaner role routing.
- Tailwind utility classes simplify layout & responsiveness.
- Keep all queries secure with prepared statements or mysqli real-escape.

---

## 📈 Future Improvements

- ✅ Email notifications
- ✅ Calendar-based booking UI
- ✅ Password reset & user verification
- ✅ Admin analytics dashboard

---

## 👥 Authors

- Joy Jeruto
- Sudi Kerechi
- Kiptoo Cornel Kiprono
- Mwaura Calvin Kariuki
- Jollyne Cheruto
- Chrispus Mwangi

---

