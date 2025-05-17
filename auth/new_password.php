<?php
session_start();
require_once '../config/config.php';

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $email = $_SESSION['reset_email'] ?? '';
    if (!$email || !($_SESSION['reset_code_verified'] ?? false)) {
        $_SESSION['error'] = 'Session expired or invalid.';
        header('Location: forgot_password.php');
        exit();
    }

    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: new_password.php');
        exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?");
    $stmt->execute([$hashed, $email]);

    // Clear reset-related session variables
    unset($_SESSION['reset_email'], $_SESSION['reset_code_verified'], $_SESSION['email']);

    $_SESSION['success'] = 'Password reset successful. You can now log in.';
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>

<body>
    <div class="center-wrapper">
        <div class="logo" style="padding-right: 20px">
            <img src="styles/log.png" alt="Logo" style="height: 100%px; height: 100%px">
        </div>
        <div class="form-box" id="new-password-form">
            <form method="post">
                <h2>Create New Password</h2>

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

                <p class="form-description">Please enter your new password.</p>

                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>

                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>