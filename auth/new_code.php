<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_code'])) {
    $enteredCode = $_POST['code'];
    $email = $_SESSION['email'] ?? '';

    if (!$email) {
        $_SESSION['error'] = 'Session expired. Start again.';
        header('Location: forgot_password.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $enteredCode == $user['reset_code']) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code_verified'] = true;
        header("Location: new_password.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid code. Try again.";
        header("Location: new_code.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>

<body>
    <div class="center-wrapper">
        <div class="logo" style="padding-right: 20px">
            <img src="styles/log.png" alt="Logo" style="height: 100%px; height: 100%px">
        </div>
        <div class="form-box" id="verify-code-form">
            <form method="post">
                <h2>Verify Code</h2>

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

                <p class="form-description">Enter the verification code sent to your email.</p>

                <!-- Verification code input -->
                <div class="verification-code">
                    <input type="text" name="code" placeholder="Enter verification code" required>
                </div>

                <button type="submit" name="verify_code">Verify Code</button>
            </form>
        </div>
    </div>
</body>

</html>