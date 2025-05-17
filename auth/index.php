<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$siteKey = $_ENV['SITE_KEY'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="styles/auth.css">
    <title>Login</title>
</head>

<body>
    <div class="center-wrapper">
        <div class="logo" style="padding-right: 20px">
            <img src="styles/log.png" alt="Logo" style="height: 100%; height: 100%">
        </div>
        <!--Login form-->
        <div class="form-box" id="login-form">
            <form action="validate_login.php" method="post">
                <h2>Welcome Back!</h2>
                <p class="form-description">Enter your email and password to log in.</p>
                <!--Error message-->
                <?php if (isset($_SESSION['error'])): ?>
                    <div style="background-color: #ffe0e0;
                        border: 1px solid #ffb3b3;
                        padding: 15px; border-radius:5px;
                        box-shadow: 2px 2px 5px #ccc;
                        margin-bottom: 10px; text-align: center;">
                        <img src="styles/error.png" alt="Error Icon"
                            style="width: 20px;
                                height: 20px;
                                vertical-align: middle;
                                margin-right: 5px;">
                        <p style="color: #8b0000; margin: 0; display: inline;"><?= $_SESSION['error'];
                                                                                unset($_SESSION['error']); ?></p>
                    </div>
                <?php endif; ?>
                <!--Success message-->
                <?php if (isset($_SESSION['success'])): ?>
                    <div style="background-color: #e0ffe0;
                        border: 1px solid #b3ffb3; 
                        padding: 15px; 
                        border-radius: 5px; 
                        box-shadow: 2px 2px 5px #ccc; 
                        margin-bottom: 10px; 
                        text-align: center;">
                        <img src="styles/success.png" alt="Success Icon"
                            style="width: 20px; 
                            height: 20px; 
                            vertical-align: middle; 
                            margin-right: 5px;">
                        <p style="color: #008000; margin: 0; display: inline;"><?= $_SESSION['success'];
                                                                                unset($_SESSION['success']); ?></p>
                    </div>
                <?php endif; ?>
                <!--Input-Fields-->
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <!--reCaptcha-->
                <div class="mb-2 text-center ">
                    <div class="g-recaptcha d-inline-block" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>
                </div>
                <button type="submit" name="login">Login</button>
                <!--Google Login-->
                <div class="text-center mb-3">
                    <a href="../googleAuth/google-login.php" class="btn btn-outline-primary w-100">
                        <i class="fab fa-google"></i> Sign up with Google
                    </a>
                </div>
                <!--Links-->
                <p>Don't have an account? <a href="register.php">Register</a></p>
                <p>Forgot Password? <a href="forgot_password.php">Click Here!</a></p>
            </form>
            <!--Return to home-->
        </div>
    </div>
    <!--reCaptcha-->
    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>

</html>