<?php
session_start();

require_once '../config/config.php';

// requires users to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'user'");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin.css">
    <title>A Users</title>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img class="logo" src="../assets/logo/logo.png" alt="MMEI FABRICATION" />
        </div>
        <ul>
            <li>
                <a href="admin_page.php">
                    <img src="../assets/sidebar/home.png" alt="dashboard" />
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="in_progress.php">
                    <img src="../assets/sidebar/progress.png" alt="dashboard" />
                    <span>In Progress</span>
                </a>
            </li>
            <li>
                <a href="on_hold.php">
                    <img src="../assets/sidebar/hold.png" alt="dashboard" />
                    <span>On Hold</span>
                </a>
            </li>
            <li>
                <a href="cancel.php">
                    <img src="../assets/sidebar/cancel.png" alt="dashboard" />
                    <span>Cancelled</span>
                </a>
            </li>
            <li>
                <a href="admin_reports.php">
                    <img src="../assets/sidebar/reports.png" alt="dashboard" />
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="admin_users.php" class="active">
                    <img src="../assets/sidebar/user.png" alt="dashboard" />
                    <span>Users</span>
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
    <!--Navbar-->
    <div class="navbar"> 
        <div class="user-welcome-left">
            <h3>Welcome,<span><?= $_SESSION['name'] ?></span></h3>
        </div>
        <div class="user-profile-right">
            <img src="../assets/navbar/user.jpg" alt="#">
        </div>
    </div>
    <!--Content-->
    <div class="content">
        <!--Admin Users-->
        <div class="admin-users">
            <table>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                    </th>
                </tr>
                <tr>
                    <?php foreach ($users as $ur): ?>
                        <td><?= $ur['id'] ?></td>
                        <td><?= $ur['name'] ?></td>
                        <td><?= $ur['email'] ?></td>
                        <td><?= $ur['role'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $ur['id'] ?>" class="btn-edit">Edit</a>
                            <a href="delete_user.php?id=<?= $ur['id'] ?>" class="btn-delete">Delete</a>
                        </td>
                </tr>
            <?php endforeach ?>
            </tr>
            </table>
        </div>
    </div>
    <!--Logout Overlay-->
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
    <!--Scripts-->
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