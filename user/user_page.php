<?php

session_start();

// requires users to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/user.css">
    <title>Home</title>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img class="logo" src="../assets/logo/logo.png" alt="logo" />
        </div>
        <ul>
            <li>
                <a href="user_page.php" class="active">
                    <img src="../assets/sidebar/home.png" alt="dashboard" />
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="user_request.php">
                    <img src="../assets/sidebar/add.png" alt="request" />
                    <span>Request Form</span>
                </a>
            </li>
            <li>
                <a href="User_records.php">
                    <img src="../assets/sidebar/record.png" alt="request" />
                    <span>Requests</span>
                </a>
            </li>
            <li>
                <a href="user_complete.php">
                    <img src="../assets/sidebar/reports.png" alt="dashboard" />
                    <span>Completed</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" onclick="showLogoutOverlay()">
                    <img src="../assets/sidebar/logout.png" alt="dashboard" />
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="navbar">
        <div class="user-welcome-left">
            <h3>Welcome,<span><?= $_SESSION['name'] ?></span></h3>
        </div>
        <div class="user-profile-right">
            <img src="../assets/navbar/user.jpg" alt="#">
        </div>
    </div>

    <div class="content">

        <div class="slider">
            <div class="slides">
                <img src="../assets/announcement/1.png" class="slide" />
                <img src="../assets/announcement/2.png" class="slide" />
                <img src="../assets/announcement/3.png" class="slide" />
            </div>
            <button class="prev" onclick="moveSlide(-1)">❮</button>
            <button class="next" onclick="moveSlide(1)">❯</button>
        </div>

        <div class="hero-advertise">
            <h1>Smart Job Order Management</h1>
            <p>Track tasks, monitor costs, and manage progress with ease — all in one place.</p>
            <a href="./user_request.php" class="hero-btn">Create New Request</a>
        </div>

    </div>

    <div id="logout-overlay" class="logout-overlay">
        <div class="logout-popup">
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to log out?</p>
            <div class="logout-buttons">
                <a href="<?php echo htmlspecialchars('logout.php'); ?>" class="logout-btn confirm">Yes, Log Out</a>
                <button type="button" class="logout-btn cancel" onclick="hideLogoutOverlay()">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/silder.js"></script>
    <script>
        function showLogoutOverlay() {
            document.getElementById('logout-overlay').style.display = 'flex';
        }

        function hideLogoutOverlay() {
            document.getElementById('logout-overlay').style.display = 'none';
        }

        // Close overlay when clicking outside of the popup
        document.getElementById('logout-overlay').addEventListener('click', function(event) {
            if (event.target === this) {
                hideLogoutOverlay();
            }
        });
    </script>
</body>

</html>