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

### Installation 

## Clone or download zip file save in htdocs directory in your xammp folder in local C
## Install dependencies - (Composer Install)
## Create .env file 
SITE_KEY=[your_Site_key]
SECRET_KEY=[your_secret_key]
GOOGLE_CLIENT_ID=[your_client_id]
GOOGLE_CLIENT_SECRET=[your_google_secret_key]
GOOGLE_REDIRECT=[google_redirect]
## Create Sql databse
## User table
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `reset_code` bigint(20) DEFAULT NULL
) 
## Job-order-table
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
) 
## Open your XAMPP Control Panel and start Apache and MySQL.
## then Type localhost/[folder_location]
## Sign up then Login




