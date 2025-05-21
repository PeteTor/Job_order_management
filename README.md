# 📋 Job Order Management

Welcome to the **Job Order Management System**, a streamlined solution designed to help businesses efficiently track, manage, and process job orders from inception to completion.

## 🛠️ Technology Stack

### 🐘 PHP
A popular server-side scripting language used to build dynamic web applications. It runs on the server and generates HTML to send to the browser.

### 🐬 MySQL
An open-source relational database management system used to store and manage application data efficiently.

### 🌐 Front-End Technologies

- **HTML**: The standard language for creating the structure of web pages.  
- **CSS**: Used to style and design HTML elements with colors, layouts, fonts, etc.  
- **JavaScript**: A programming language that runs in the browser to make web pages interactive.  
- **Bootstrap**: A front-end CSS framework that makes it easy to design responsive and mobile-first websites quickly.

### 🌍 Apache
A widely-used open-source web server software. It serves your PHP applications to users by processing HTTP requests and delivering web content.

## 🚀 Features

### 🔐 Authentication
- Login and Signup functionality  
- Google reCAPTCHA protection  
- Google Authentication (OAuth)  
- Forgot Password functionality (Email-based reset)

### 👤 User Panel
- Personalized Dashboard  
- Job Order Request Form  
- Track status of submitted job orders  
- View completed job orders

### 🛠️ Admin Panel
- Approve or deny job order requests  
- Track and manage job order statuses  
- Sidebar modules dynamically change based on job order status  
- Download completed job orders as PDF

## Installation 

# 📦 Job Order Management System

A web-based application for managing job orders with user authentication, admin controls, and Google OAuth.

---

## 🚀 Getting Started

### 📁 1. Clone or Download

- Download the ZIP or clone the repository:
  ```bash
  git clone https://github.com/your-username/your-repo.git
  ```
- Move the project folder into your XAMPP `htdocs` directory:
  ```
  C:/xampp/htdocs/
  ```

### ⚙️ 2. Install Dependencies

Ensure [Composer](https://getcomposer.org/) is installed, then run:

```bash
composer install
```

### 🔐 3. Create `.env` File

In the root of your project, create a `.env` file and add the following:

```env
SITE_KEY=your_site_key
SECRET_KEY=your_secret_key
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_secret_key
GOOGLE_REDIRECT=http://localhost/[your-project]/google-auth/google-callback.php
```

### 🗄️ 4. Create MySQL Database

Create a database (e.g., `booking_system`) and use the SQL below to set up tables:

#### 🧍 users Table

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `reset_code` bigint(20) DEFAULT NULL
);
```

#### 📋 job_orders Table

```sql
CREATE TABLE `job_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `service_type` enum('MACHINING','FABRICATION') NOT NULL,
  `labor_cost` varchar(255) DEFAULT NULL,
  `material_cost` varchar(255) DEFAULT NULL,
  `urgency` enum('LOW','HIGH','URGENT') NOT NULL,
  `status` enum('pending','approved','rejected','in-progress','on-hold','canceled','complete') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);
```

### ▶️ 5. Run the App

- Open **XAMPP Control Panel**.
- Start **Apache** and **MySQL**.
- In your browser, go to:

```txt
http://localhost/[your-folder-name]
```

- Register a new account and log in to start using the system.
