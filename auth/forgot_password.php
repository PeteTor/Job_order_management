<?php
session_start();
require_once '../config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $reset_code = rand(100000, 999999);

        $update = $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $update->execute([$reset_code, $email]);

        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username   = 'pvdawis@gmail.com'; 
            $mail->Password   = 'jkpz mcif aodd icwk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('pvdawis@gmail.com', 'Peter Victor D. Dawis');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = "
                <p>Hello, here is your password reset code:</p>
                <div style='font-size: 24px; background: #f0f0f0; padding: 10px;'>{$reset_code}</div>
                <p>If you didn’t request this, you can ignore this message.</p>
            ";
            $mail->AltBody = "Your password reset code is: {$reset_code}";

            $mail->send();
            $_SESSION['success'] = "Code sent to your email.";
            header("Location: new_code.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'Email could not be sent. Please try again.';
        }
    } else {
        $_SESSION['error'] = 'No user found with that email.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles/auth.css">

</head>

<body>
    <div class="center-wrapper">
        <div class="logo" style="padding-right: 20px">
            <img src="styles/log.png" alt="Logo" style="height: 100%px; height: 100%px">
        </div>
        <div class="form-box" id="forgot-password-form">
            <form method="post">
                <h2>Forgot Password</h2>

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

                <p class="form-description">Enter your email address to receive a verification code.</p>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" name="forgot_password">Send Verification Code</button>
                <p>Remember your password? <a href="index.php">Back to Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>