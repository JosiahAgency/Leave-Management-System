# 🗓️ Laravel Leave Management System

A web-based Leave Management System built with the **Laravel framework** and **Filament admin panel**. This application allows employees to apply for leave, and authorized roles such as Managers, HR, and Directors to approve or reject leave requests based on organizational hierarchy.

---

## 🚀 Features

- User Authentication and Role-based Access Control (using Spatie Laravel-Permission)
- Leave Application Submission
- Leave Approval Workflow:
  - ✅ Manager (HOD) Approval
  - ✅ Human Resources Approval
  - ✅ Director Final Approval
- Role-Specific Views:
  - Employees view only their leave history
  - Managers see leave requests from their department
  - HR sees all pending approvals
  - Director sees final-stage approvals
- Notification-style badge for pending requests
- Custom Filament Pages for Approval Workflows
- User-friendly admin dashboard with Filament UI

---

## 🛠️ Tech Stack

- [Laravel](https://laravel.com) 11+
- [Filament](https://filamentphp.com/) (Admin Panel)
- [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission) (Roles & Permissions)
- MySQL / PostgreSQL (Database)
- Tailwind CSS (via Filament)

---

## ⚙️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-leave-system.git
cd laravel-leave-system
````

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Setup Environment

Copy `.env.example` to `.env` and set your database, mail, and app settings.

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

> Seeder will create default roles: `admin`, `humanResources`, `manager`, `director`, and `user`.

### 5. Serve the Application

```bash
composer run dev
```

Access the application at `http://localhost:8000`

---

## 👥 User Roles

| Role         | Permissions                                  |
| ------------ | -------------------------------------------- |
| **User**     | Submit leave requests, view personal history |
| **Manager**  | Approve/reject department requests           |
| **HR**       | Approve/reject requests after HOD            |
| **Director** | Final approval stage                         |
| **Admin**    | Full access, manage users, view all requests |

> Role management is handled using the **Spatie** permissions package via the Filament admin dashboard.

---

## 📄 Approval Workflow

1. **Employee** applies for leave.
2. **Manager (HOD)** reviews and approves/rejects.
3. **HR** reviews approved requests by HOD.
4. **Director** gives final approval if required.

Each step is permission-guarded and reflected in a separate workflow page.

---

## 📁 Directory Structure Highlights

* `app/Models/LeaveRequest.php` – Main leave request model
* `app/Filament/Resources/LeaveRequestResource.php` – Resource file for leave management
* `app/Filament/Resources/LeaveRequestResource/Pages/Approvals.php` – Custom page for role-based approvals
* `database/seeders/RoleSeeder.php` – Role and permission seeding

---

## 🔐 Authentication

This app uses Laravel Breeze or Jetstream (depending on setup) for authentication. User registration/login is built-in.

---

## ✅ Future Improvements

* Email notifications for approvals/rejections
* Leave balances and limits
* Public holiday exclusions
* File attachments for medical/leave evidence
* PDF export for leave records

---

## 🙏 Acknowledgements

* [Laravel](https://laravel.com)
* [Filament Admin Panel](https://filamentphp.com/)
* [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

---

## 📃 License

This project is open-source and available under the [MIT License](LICENSE).

```

---

Let me know if you'd like me to generate this as a downloadable file, or customize it with real usernames, repository URLs, or screenshots.
```
