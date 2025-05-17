<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];
    $role = trim(htmlspecialchars($_POST['role']));

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $_SESSION['error'] = 'Email is already registered!';
        header("Location: index.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Account created! You can now log in.';
        header("Location: index.php");
        exit();
    } else {
        error_log("Registration error: " . $stmt->error);
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header("Location: index.php");
        exit();
    }
}
