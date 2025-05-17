<?php
session_start();

// Require login
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

require_once '../config/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmed'])) {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $department = $_POST['department']; // Changed from description to department
    $description = $_POST['description'];
    $service_type = $_POST['serviceType'];
    $labor_cost = $_POST['laborCost'] ?: '';
    $material_cost = $_POST['materialCost'] ?: '';
    $urgency = $_POST['urgency'];

    $sql = "INSERT INTO job_orders (user_id, title, department, description, service_type, labor_cost, material_cost, urgency)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $title, $department, $description, $service_type, $labor_cost, $material_cost, $urgency);

    if ($stmt->execute()) {
        echo "<script>alert('Job order submitted successfully'); window.location.href='user_request.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/user.css">
    <title>Request</title>
</head>

<body>
    <!-- Sidebar -->
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
                <a href="user_request.php" class="active">
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

    <!-- Navbar -->
    <div class="navbar">
        <div class="user-welcome-left">
            <h3>Welcome,<span><?= htmlspecialchars($_SESSION['name']) ?></span></h3>
        </div>
        <div class="user-profile-right">
            <img src="../assets/navbar/user.jpg" alt="User Profile">
        </div>
    </div>
    <!-- Content -->
    <div class="content-request">
        <div class="container">
            <div class="container-header">
                <h2>Request Form</h2>
            </div>

            <form id="requestForm" method="POST" action="user_request.php">
                <input type="hidden" name="confirmed" value="1">

                <div class="form-group"> <!--form group job order title-->
                    <span class="label-title">JOB ORDER TITLE</span><input type="text" name="title" placeholder=" " required>
                </div>

                <div class="form-group"> <!--form group department/requester-->
                    <span class="label-title">DEPARTMENT/REQUESTER NAME</span><input type="text" name="department" placeholder=" " required>
                </div>

                <div class="form-group"> <!--form group task description-->
                    <span class="label-title">TASK DESCRIPTION</span><textarea name="description" placeholder="" rows="3"></textarea>
                </div>

                <div class="service-options"> <!--Service options machining or fabrication-->
                    <label class="service-option"><input type="radio" name="serviceType" value="MACHINING" required><span>MACHINING</span></label>
                    <label class="service-option"><input type="radio" name="serviceType" value="FABRICATION" required><span>FABRICATION</span></label>
                </div>

                <div class="form-group"> <!--form group labor cost estimate-->
                    <span class="label-title">LABOR COST ESTIMATE</span><input type="text" name="laborCost" placeholder="e.g 10 hours">
                </div>

                <div class="form-group"> <!--form group material cost estimate-->
                    <span class="label-title">MATERIAL COST ESTIMATE</span><input type="text" name="materialCost" placeholder="e.g 2pcs rebar etc.">
                </div>

                <div class="form-group">
                    <p class="urgency-title">URGENCY</p>
                    <div class="urgency-options"> <!--urgency options low high and urgent-->
                        <label class="urgency-option urgency-low"><input type="radio" name="urgency" value="LOW" required><span>LOW</span></label>
                        <label class="urgency-option urgency-high"><input type="radio" name="urgency" value="HIGH" required><span>HIGH</span></label>
                        <label class="urgency-option urgency-urgent"><input type="radio" name="urgency" value="URGENT" required><span>URGENT</span></label>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Submit form</button>
            </form>

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

    <!-- Confirmation Overlay -->
    <div id="confirmation-overlay" class="confirmation-overlay">
        <div class="confirmation-popup">
            <h2>Confirm Job Order Request</h2>
            <div class="confirmation-details" id="confirmation-details">
                <!-- Details will be populated by JavaScript -->
            </div>
            <div class="confirmation-buttons">
                <button type="button" class="confirmation-btn confirm" id="confirmSubmit">Confirm & Submit</button>
                <button type="button" class="confirmation-btn cancel" id="cancelSubmit">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Ensure DOM is fully loaded before attaching events
        document.addEventListener('DOMContentLoaded', function() {
            // Logout functions
            function showLogoutOverlay() {
                document.getElementById('logout-overlay').style.display = 'flex';
            }

            function hideLogoutOverlay() {
                document.getElementById('logout-overlay').style.display = 'none';
            }

            // Initialize logout overlay functionality
            document.querySelectorAll('a[onclick="showLogoutOverlay()"]').forEach(function(element) {
                element.addEventListener('click', showLogoutOverlay);
            });

            document.querySelector('.logout-btn.cancel').addEventListener('click', hideLogoutOverlay);

            // Close logout overlay when clicking outside of the popup
            document.getElementById('logout-overlay').addEventListener('click', function(event) {
                if (event.target === this) {
                    hideLogoutOverlay();
                }
            });

            // Form confirmation functions
            function showConfirmation(event) {
                event.preventDefault();

                const form = document.getElementById('requestForm');
                const formData = new FormData(form);

                // Check form validity
                if (!form.checkValidity()) {
                    // If form is invalid, trigger browser's built-in validation UI
                    return true; // Allow normal form submission to show validation errors
                }

                // Create HTML for confirmation details
                let detailsHTML = '';

                // Job Order Title
                detailsHTML += createDetailRow('Job Order Title', formData.get('title'));

                // Department/Requester Name
                detailsHTML += createDetailRow('Department/Requester Name', formData.get('department'));

                // Task Description
                detailsHTML += createDetailRow('Task Description', formData.get('description') || 'Not provided');

                // Service Type
                detailsHTML += createDetailRow('Service Type', formData.get('serviceType'));

                // Labor Cost Estimate
                detailsHTML += createDetailRow('Labor Cost Estimate', formData.get('laborCost') || 'Not provided');

                // Material Cost Estimate
                detailsHTML += createDetailRow('Material Cost Estimate', formData.get('materialCost') || 'Not provided');

                // Urgency
                const urgencyValue = formData.get('urgency');
                detailsHTML += `
                    <div class="detail-row">
                        <div class="detail-label">Urgency</div>
                        <div class="detail-value">
                            <span class="urgency-tag urgency-${urgencyValue}">${urgencyValue}</span>
                        </div>
                    </div>
                `;

                // Update confirmation details
                document.getElementById('confirmation-details').innerHTML = detailsHTML;

                // Show confirmation overlay
                const overlay = document.getElementById('confirmation-overlay');
                overlay.style.display = 'flex';

                return false;
            }

            function createDetailRow(label, value) {
                return `
                    <div class="detail-row">
                        <div class="detail-label">${label}</div>
                        <div class="detail-value">${value}</div>
                    </div>
                `;
            }

            // Attach the confirmation function to the form
            const requestForm = document.getElementById('requestForm');
            if (requestForm) {
                requestForm.addEventListener('submit', showConfirmation);
            }

            // Submit form when confirmation is clicked
            document.getElementById('confirmSubmit').addEventListener('click', function() {
                document.getElementById('requestForm').submit();
            });

            // Cancel submission
            document.getElementById('cancelSubmit').addEventListener('click', function() {
                document.getElementById('confirmation-overlay').style.display = 'none';
            });

            // Close confirmation overlay when clicking outside of the popup
            document.getElementById('confirmation-overlay').addEventListener('click', function(event) {
                if (event.target === this) {
                    this.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>