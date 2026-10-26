# Task Management System

A multi-role Task Management System built with **Laravel 12** and styled with **Bootstrap 5.3**. The system provides dedicated features for **Admin** and **Employee** roles, strict authorization controls, real-time AJAX task status updates, and a token-authenticated REST API via **Laravel Sanctum**.

---

## 🚀 Features

### 👑 Admin Features
- **Dashboard**: High-level statistical overview of total, pending, in-progress, and completed tasks, alongside project and employee counts.
- **Project Management (CRUD)**: Create, view, edit, and delete projects.
- **Task Management (CRUD)**: Create tasks, assign them to specific employees, set priority (`low`, `medium`, `high`), status (`pending`, `in_progress`, `completed`), and optional due dates.
- **Search & Filtering**: Search tasks by title and filter across status, priority, and project in real-time.
- **Role-based Protection**: Secure route-level and controller-level access via `RoleMiddleware`.

### 👷 Employee Features
- **Personalized Dashboard**: Live statistics showing only tasks assigned to the authenticated employee.
- **My Tasks View**: Clear view of assigned tasks with priority and due dates.
- **Real-Time AJAX Status Update**: Update task status instantly via an AJAX dropdown without page reload, featuring visual inline feedback (`✓ Updated`).
- **Task Details**: View detailed information (title, project, description, priority, due date, timestamps) for assigned tasks.
- **Strict Authorization**: Cannot access admin modules (e.g. `/tasks`, `/projects`) or view/modify tasks belonging to other employees (returns HTTP 403 Forbidden).

### ⚡ REST API (Laravel Sanctum)
- Token-based API authentication (`/api/v1/login`, `/api/v1/logout`).
- Endpoint to list tasks (`GET /api/v1/tasks`) with optional status filtering.
- Endpoint to view a specific task (`GET /api/v1/tasks/{id}`).
- Endpoint to update task status (`PATCH /api/v1/tasks/{id}/status`).
- Automatic role scoping: Employees only retrieve and modify their own tasks.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL (compatible with SQLite/PostgreSQL)
- **Frontend / Styling**: Bootstrap 5.3 (CDN) & Bootstrap Icons
- **JavaScript**: Vanilla ES6 Fetch API (AJAX)
- **API Authentication**: Laravel Sanctum (Bearer Token)

---

## 📋 Database Schema & Relationships

```
+----------------+       +-------------------+       +-----------------+
|     users      |       |     projects      |       |      tasks      |
+----------------+       +-------------------+       +-----------------+
| id             |1     *| id                |1     *| id              |
| name           |<------| name              |<------| project_id      |
| email          |       | description       |       | assigned_to     |--> users.id (employee)
| password       |       | created_at        |       | created_by      |--> users.id (admin)
| role (admin/   |       | updated_at        |       | title           |
|   employee)    |       +-------------------+       | description     |
| created_at     |                                   | priority        |
| updated_at     |                                   | status          |
+----------------+                                   | due_date        |
                                                     | created_at      |
                                                     | updated_at      |
                                                     +-----------------+
```

- **User**: Has many assigned `Task` records (`hasMany(Task::class, 'assigned_to')`).
- **Project**: Has many `Task` records (`hasMany(Task::class)`).
- **Task**: Belongs to `Project`, belongs to `employee` (`User`), and belongs to `creator` (`User`).

---

## ⚙️ Installation & Setup

### 1. Clone the repository
```bash
git clone <repository-url>
cd task-manager
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Environment configuration
Copy `.env.example` to `.env` and generate an application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your MySQL database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seed Demo Data
Ensure the database `task_manager` exists in MySQL, then execute:
```bash
php artisan migrate --seed
```
> This will seed the database with an Admin, 3 Employees, 3 Projects, and 10 realistic tasks across various priorities and statuses.

### 5. Start the local server
```bash
php artisan serve
```
Open [http://localhost:8000](http://localhost:8000) in your web browser.

---

## 🔑 Demo Credentials

| Role | Name | Email | Password |
|---|---|---|---|
| **Admin** | Admin User | `admin@example.com` | `Admin@12345` |
| **Employee 1** | Demo Employee | `employee@example.com` | `Employee@12345` |
| **Employee 2** | John Smith | `john@example.com` | `Employee@12345` |
| **Employee 3** | Priya Sharma | `priya@example.com` | `Employee@12345` |

---

## 📡 REST API Documentation

### 📮 Postman Collection Included
A ready-to-use Postman Collection is included in the root of this repository:
- **File**: [`Task_Manager_API.postman_collection.json`](./Task_Manager_API.postman_collection.json)
- **Features**: Automatically saves the Sanctum Bearer token into a collection variable on Login, so you don't need to copy-paste tokens manually.
- **Import**: In Postman, click **Import** > Select `Task_Manager_API.postman_collection.json`.

### Base URL
`http://localhost:8000/api/v1`

### Authentication Endpoints

#### 1. Login (Generate Token)
- **Method**: `POST`
- **URL**: `/api/v1/login`
- **Headers**: `Content-Type: application/json`, `Accept: application/json`
- **Request Body**:
```json
{
  "email": "admin@example.com",
  "password": "Admin@12345"
}
```
- **Response (200 OK)**:
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin"
  },
  "token": "1|v9SccsV0nXKSBZWTk26qioRgDMXPXSglV98GR6L1dd551bc9"
}
```

#### 2. Logout
- **Method**: `POST`
- **URL**: `/api/v1/logout`
- **Headers**:
  - `Authorization: Bearer <TOKEN>`
  - `Accept: application/json`
- **Response (200 OK)**:
```json
{
  "message": "Logged out successfully"
}
```

---

### Task Endpoints (Requires Bearer Token)

#### 3. List Tasks
- **Method**: `GET`
- **URL**: `/api/v1/tasks`
- **Optional Query Params**: `?status=pending` (`pending`, `in_progress`, or `completed`)
- **Headers**:
  - `Authorization: Bearer <TOKEN>`
  - `Accept: application/json`
- **Behavior**:
  - **Admin**: Returns all tasks across all employees (paginated).
  - **Employee**: Scoped strictly to tasks assigned to that employee.

#### 4. View Task Details
- **Method**: `GET`
- **URL**: `/api/v1/tasks/{id}`
- **Headers**:
  - `Authorization: Bearer <TOKEN>`
  - `Accept: application/json`
- **Authorization**: Returns `403 Forbidden` if an employee attempts to view a task not assigned to them.

#### 5. Update Task Status
- **Method**: `PATCH`
- **URL**: `/api/v1/tasks/{id}/status`
- **Headers**:
  - `Authorization: Bearer <TOKEN>`
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Request Body**:
```json
{
  "status": "completed"
}
```
- **Allowed Statuses**: `pending`, `in_progress`, `completed`
- **Response (200 OK)**:
```json
{
  "message": "Task status updated",
  "data": {
    "id": 1,
    "status": "completed"
  }
}
```

---

## 🔒 Security & Authorization Highlights

1. **Role Middleware (`app/Http/Middleware/RoleMiddleware.php`)**: Protects routes so employees cannot access administrative endpoints (`/projects/*`, `/tasks/*`).
2. **Horizontal Privilege Escalation Protection**: Both web controllers and API controllers enforce ownership validation (`$task->assigned_to === $request->user()->id`). Employees attempting to tamper with IDs in the URL or payload receive an instant `403 Forbidden`.
3. **CSRF Protection**: All web AJAX requests include the `X-CSRF-TOKEN` header extracted from the meta tag.
4. **Input Validation**: Strict validation rules for task statuses, project IDs, employee IDs, and date formats.
