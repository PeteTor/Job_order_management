<?php

session_start();

require_once '../config/config.php';

// requires users to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM job_orders");
$stmt->execute();
$job_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize status counts
$status_counts = [
    'PENDING' => 0,
    'IN-PROGRESS' => 0,
    'ON-HOLD' => 0,
    'CANCELED' => 0,
    'COMPLETE' => 0
];

// Get status counts with error checking
$count_sql = "SELECT status, COUNT(*) as count FROM job_orders GROUP BY status";
$count_result = mysqli_query($conn, $count_sql);

// Debug
error_log("Fetching status counts");

if ($count_result) {
    while ($count_row = $count_result->fetch_assoc()) {
        $status = $count_row['status'];
        error_log("Found status: " . $status . " with count: " . $count_row['count']);

        // Make sure the status exists in our array before assigning
        if (isset($status_counts[$status])) {
            $status_counts[$status] = $count_row['count'];
        }
    }
} else {
    error_log("Error in count query: " . mysqli_error($conn));
}

// Debug - print counts to error log
foreach ($status_counts as $status => $count) {
    error_log("Status: $status, Count: $count");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = $_POST['action'];
    $id = $_POST['job_id'];

    $stmt = $pdo->prepare("UPDATE job_orders SET status = ? WHERE id = ?");
    $stmt->execute([$action, $id]);

    header("Location: admin_page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="styles/admin.css">
    <title>A Dashboard</title>
</head>

<body>
    <!--Sidebar-->
    <div class="sidebar">
        <div class="sidebar-header">
            <img class="logo" src="../assets/logo/logo.png" alt="logo" />
        </div>
        <ul>
            <li>
                <a href="admin_page.php" class="active">
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
            <h3>Welcome,<span><?= $_SESSION['name'] ?></span></h3>
        </div>
        <div class="user-profile-right">
            <img src="../assets/navbar/user.jpg" alt="#">
        </div>
    </div>
    <!-- Main Content -->
    <div class="content">
        <!-- Cards Container -->
        <div class="container">
            <!--Pending Card-->
            <div class="status-card pending" style="background-color: white; color: rgba(0, 0, 0, 0.8); border-top: 10px solid #6366f1">
                <div class="status-name">PENDING <i class="fas fa-hourglass-start" style="color: #6366f1; margin-left: 15px;"></i></div>
                <div class="status-count">
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_orders WHERE status = 'PENDING'");
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo $count;
                    ?>
                </div>
            </div>
            <!--In-Progress Card-->
            <div class="status-card in-progress" style="background-color: white; color: rgba(0, 0, 0, 0.8); border-top: 10px solid #06b6d4">
                <div class="status-name">IN-PROGRESS<i class="fas fa-spinner" style="color: #06b6d4; margin-left: 15px;"></i></div>
                <div class="status-count">
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_orders WHERE status = 'IN-PROGRESS'");
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo $count;
                    ?>
                </div>
            </div>
            <!--On-Hold Card-->
            <div class="status-card on-hold" style="background-color: white; color: rgba(0, 0, 0, 0.8); border-top: 10px solid #fbbf24">
                <div class="status-name">ON-HOLD <i class="fas fa-pause-circle" style="color:#fbbf24; margin-left: 15px;"></i></div>
                <div class="status-count">
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_orders WHERE status = 'ON-HOLD'");
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo $count;
                    ?>
                </div>
            </div>
            <!--Canceled Card-->
            <div class="status-card canceled" style="background-color: white; color: rgba(0, 0, 0, 0.8); border-top: 10px solid #ef4444">
                <div class="status-name">CANCELED <i class="fas fa-times-circle" style="color: #ef4444; margin-left: 15px;"></i></div>
                <div class="status-count">
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_orders WHERE status = 'CANCELED'");
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo $count;
                    ?>
                </div>
            <!--Complete Card-->
            </div>
            <div class="status-card complete" style="background-color: white; color: rgba(0, 0, 0, 0.8); border-top: 10px solid #4ade80">
                <div class="status-name">COMPLETE <i class="fas fa-check-circle" style="color: #4ade80; margin-left: 15px;"></i></div>
                <div class="status-count">
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_orders WHERE status = 'COMPLETE'");
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo $count;
                    ?>
                </div>
            </div>
        </div>
        <!-- Table Container -->
        <div class="admin-table">
            <table>
                <tr>
                    <th>No.</th>
                    <th>Job Order Title</th>
                    <th>Dept./Requester Name</th>
                    <th>Description</th>
                    <th>Task Category</th>
                    <th>Labor Cost Estimate</th>
                    <th>Material Cost Estimate</th>
                    <th>Urgency</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($job_orders as $jb): ?>
                    <tr>
                        <td>
                            <?= $jb['id'] ?>
                        </td>
                        <td>
                            <?= $jb['title'] ?>
                        </td>
                        <td>
                            <?= $jb['department'] ?>
                        </td>
                        <td>
                            <?= $jb['description'] ?>
                        </td>
                        <td>
                            <?= $jb['service_type'] ?>
                        </td>
                        <td>
                            <?= $jb['labor_cost'] ?>
                        </td>
                        <td>
                            <?= $jb['material_cost'] ?>
                        </td>
                        <td>
                            <?= $jb['urgency'] ?>
                        </td>
                        <td>
                            <?php
                            $status = strtolower(trim($jb['status']));
                            ?>
                            <span class="status-badge <?= $status ?>">
                                <?= htmlspecialchars($jb['status']) ?>
                            </span>
                        </td>
                        <!-- Action Column -->
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="job_id" value="<?= $jb['id'] ?>">
                                <select name="action" id="action">
                                    <option value="PENDING">Select Action</option>
                                    <option value="IN-PROGRESS">In Progress</option>
                                    <option value="ON-HOLD">On Hold</option>
                                    <option value="CANCELED">Canceled</option>
                                    <option value="COMPLETE">Complete</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <!-- Logout Overlay -->
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
    <!-- Logout Overlay Script -->
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