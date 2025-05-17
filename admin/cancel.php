<?php
session_start();
require_once '../config/config.php';

// Requires user to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Load data for table view
$stmt = $pdo->prepare("SELECT * FROM job_orders WHERE status = 'canceled'");
$stmt->execute();
$job_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin.css">
    <title>Progress</title>
</head>

<body>
    <!--Sidebar-->
    <div class="sidebar">
        <div class="sidebar-header">
            <img class="logo" src="../assets/logo/logo.png" alt="logo" />
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
                <a href="cancel.php" class="active">
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
                <a href="admin_users.php">
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
            <h3>Welcome, <span><?= $_SESSION['name'] ?></span></h3>
        </div>
        <div class="user-profile-right">
            <img src="../assets/navbar/user.jpg" alt="#">
        </div>
    </div>
    <!--Content-->
    <div class="content">
        <!--Reports-->
        <div class="admin-reports">
            <table>
                <tr>
                    <th style="border-top: 5px solid #ff0000;">No.</th>
                    <th style="border-top: 5px solid #ff0000;">Job Order Title</th>
                    <th style="border-top: 5px solid #ff0000;">Dept./Requester Name</th>
                    <th style="border-top: 5px solid #ff0000;">Description</th>
                    <th style="border-top: 5px solid #ff0000;">Task Category</th>
                    <th style="border-top: 5px solid #ff0000;">Labor Cost Estimate</th>
                    <th style="border-top: 5px solid #ff0000;">Material Cost Estimate</th>
                    <th style="border-top: 5px solid #ff0000;">Status</th>
                </tr>
                <?php foreach ($job_orders as $job): ?>
                    <tr>
                        <td><?= $job['id'] ?></td>
                        <td><?= $job['title'] ?></td>
                        <td><?= $job['department'] ?></td>
                        <td><?= $job['description'] ?></td>
                        <td><?= $job['service_type'] ?></td>
                        <td><?= $job['labor_cost'] ?></td>
                        <td><?= $job['material_cost'] ?></td>
                        <td>
                            <div style="background-color: #ff0000;
                            color: white;
                            font-weight: bold;
                            padding: 10px 12px;
                            display: inline-block;
                            border-radius: 4px;
                            margin: 4px;">
                                <?= $job['status'] ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
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