<?php
session_start();
require_once '../config/config.php';

// Requires user to be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Handle PDF generation
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../config/config.php';

    if (class_exists('\Mpdf\Mpdf')) {
        echo "mPDF class exists";
    } else {
        echo "mPDF class does not exist";
        var_dump(get_declared_classes());
        die();
    }

    $mpdf = new \Mpdf\Mpdf();
    header("Content-Type: application/pdf");

    $stmt = $pdo->prepare("SELECT * FROM job_orders WHERE status = 'complete'");
    $stmt->execute();
    $job_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = 1;

    $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: "Helvetica Neue", sans-serif;
                    font-size: 12px;
                    padding: 20px;
                    color: #333;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid #000;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                }
                .signature_section {
                    margin-top: 50px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h1 style="text-align: center">Completed Job Orders</h1>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Title</th>
                        <th>Dept./Requester Name</th>
                        <th>Description</th>
                        <th>Task Category</th>
                        <th>Labor Cost</th>
                        <th>Material Cost</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    ';

    foreach ($job_orders as $job) {
        $html .= '
            <tr>
                <td>' . $count++ . '</td>
                <td>' . htmlspecialchars($job['title']) . '</td>
                <td>' . htmlspecialchars($job['department']) . '</td>
                <td>' . htmlspecialchars($job['description']) . '</td>
                <td>' . htmlspecialchars($job['service_type']) . '</td>
                <td>' . htmlspecialchars($job['labor_cost']) . '</td>
                <td>' . htmlspecialchars($job['material_cost']) . '</td>
                <td>' . htmlspecialchars($job['status']) . '</td>
            </tr>
        ';
    }

    $html .= '
                </tbody>
            </table>
            <div class="signature_section">
                <p>_________________________________________________</p>
                <p><strong>Admin</strong></p>
            </div>
        </body>
        </html>
    ';

    $mpdf->SetHTMLFooter('
        <div style="text-align: left;">
            Page {PAGENO} of {nbpg}
        </div>
    ');

    $mpdf->WriteHTML($html);
    $mpdf->Output("Completed_Job_Orders_Report.pdf", "D");
    exit;
}

// Load data for table view
$stmt = $pdo->prepare("SELECT * FROM job_orders WHERE status = 'complete'");
$stmt->execute();
$job_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin.css">
    <title>A Reports</title>
</head>

<body>
    <!-- Sidebar -->
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
                <a href="cancel.php">
                    <img src="../assets/sidebar/cancel.png" alt="dashboard" />
                    <span>Cancelled</span>
                </a>
            </li>
            <li>
                <a href="admin_reports.php" class="active">
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
                    <th style="border-top: 5px solid #4ade80;">No.</th>
                    <th style="border-top: 5px solid #4ade80;">Job Order Title</th>
                    <th style="border-top: 5px solid #4ade80;">Dept./Requester Name</th>
                    <th style="border-top: 5px solid #4ade80;">Description</th>
                    <th style="border-top: 5px solid #4ade80;">Task Category</th>
                    <th style="border-top: 5px solid #4ade80;">Labor Cost Estimate</th>
                    <th style="border-top: 5px solid #4ade80;">Material Cost Estimate</th>
                    <th style="border-top: 5px solid #4ade80;">Status</th>
                </tr>
                <?php foreach ($job_orders as $job): ?>
                    <tr>
                        <td><?= $job['id'] ?></td>
                        <td><?= $job['department'] ?>
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
        <!--Download-->
        <div class="reports-download">
            <form action="" method="POST">
                <button class="download-btn">Download</button>
            </form>
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