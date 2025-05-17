<?php
session_start();
require_once '../config/config.php';

// Load environment variables (if using vlucas/phpdotenv)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get reCAPTCHA secret key from env
    $recaptchaSecret = getenv('SECRET_KEY') ?: $_ENV['SECRET_KEY'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA response
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess || !$captchaSuccess->success) {
        $_SESSION['error'] = "Captcha verification failed. Please try again.";
        header("Location: index.php");
        exit();
    }

    // Sanitize and validate input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: index.php");
        exit();
    }

    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Prevent session fixation
                session_regenerate_id(true);

                // Store user info in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/admin_page.php");
                } else {
                    header("Location: ../user/user_page.php");
                }
                exit();
            }
        }
    }

    // Login failed
    $_SESSION['error'] = "Incorrect email or password.";
    header("Location: index.php");
    exit();
}
