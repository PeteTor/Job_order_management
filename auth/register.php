<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/auth.css">
    <title>Register</title>
</head>

<body>
    <div class="center-wrapper">
        <div class="logo" style="padding-right: 20px">
            <img src="styles/log.png" alt="Logo" style="height: 100%px; height: 100%px">
        </div>
        <!--Register form-->
        <div class="form-box" id="register-form">
            <form action="validate_register.php" method="post">
                <h2>Register</h2>
                <p class="form-description">Please enter your details to create an account.</p>
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
                <!--Input-Fields-->
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <!--Select role admin or user-->
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="index.php">Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>