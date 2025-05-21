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

- Clone or download zip file save in htdocs directory in your xammp folder in local C
- Install dependencies - 
  <a href="#">Composer Install</a>

- Create .env file<br> 
<a href="#">
SITE_KEY=[your_Site_key] <br>
SECRET_KEY=[your_secret_key] <br>
GOOGLE_CLIENT_ID=[your_client_id] <br>
GOOGLE_CLIENT_SECRET=[your_google_secret_key] <br>
GOOGLE_REDIRECT=[google_redirect] <br>
</a>

### Create Sql databse
- User table
<a href="#">
CREATE TABLE `users` ( <br>
  `id` int(11) NOT NULL, <br>
  `name` varchar(255) NOT NULL, <br>
  `email` varchar(255) NOT NULL, <br>
  `password` varchar(255) NOT NULL, <br>
  `role` enum('user','admin') NOT NULL, <br>
  `reset_code` bigint(20) DEFAULT NULL <br>
) 
</a>
- Job-order-table
<a href="#">
CREATE TABLE `job_orders` ( <br>
  `id` int(11) NOT NULL, <br>
  `user_id` int(11) NOT NULL, <br>
  `title` varchar(255) NOT NULL, <br>
  `description` text DEFAULT NULL, <br>
  `service_type` enum('MACHINING','FABRICATION') NOT NULL, <br>
  `labor_cost` varchar(255) DEFAULT NULL, <br>
  `material_cost` varchar(255) DEFAULT NULL, <br>
  `urgency` enum('LOW','HIGH','URGENT') NOT NULL, <br>
  `status` enum('pending','approved','rejected','in-progress','on-hold','canceled','complete') DEFAULT 'pending', <br>
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() <br>
) 
</a>

- Open your XAMPP Control Panel and start Apache and MySQL.
- then Type localhost/[folder_location]
- Sign up then Login




