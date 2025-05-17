<?php

session_start();

require_once '../config/config.php';

// requires users to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM job_orders WHERE status = 'complete'");
$stmt->execute();
$job_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/user.css">
    <title>Completed</title>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img class="logo" src="../assets/logo/logo.png" alt="logo" />
        </div>
        <ul>
            <li>
                <a href="user_page.php">
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
                <a href="user_records.php">
                    <img src="../assets/sidebar/record.png" alt="request" />
                    <span>Requests</span>
                </a>
            </li>
            <li>
                <a href="user_complete.php" class="active">
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
        <div class="completed-requests">
            <table>
                <tr>
                    <th>No.</th>
                    <!-- <th>Requester Name</th> -->
                    <th>Job Order Title</th>
                    <th>Description</th>
                    <th>Task Category</th>
                    <th>Labor Cost Estimate</th>
                    <th>Material Cost Estimate</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($job_orders as $job): ?>
                    <tr>
                        <td><?= $job['id'] ?></td>
                        <!-- <td><?= $job['name'] ?></td> -->
                        <td><?= $job['title'] ?></td>
                        <td><?= $job['description'] ?></td>
                        <td><?= $job['service_type'] ?></td>
                        <td><?= $job['labor_cost'] ?></td>
                        <td><?= $job['material_cost'] ?></td>
                        <td>
                            <div style="background-color: #4ade80;
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