<?php

session_start();
include '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get admin information
$admin_name = $_SESSION['admin_name'];

// Count pending reports
$pending_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports WHERE status = 'Pending'"
);

$pending_reports = mysqli_fetch_assoc($pending_query)['total'];


// Count confirmed reports
$confirmed_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports WHERE status = 'Confirmed'"
);

$confirmed_reports = mysqli_fetch_assoc($confirmed_query)['total'];


// Count declined reports
$declined_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports WHERE status = 'Declined'"
);

$declined_reports = mysqli_fetch_assoc($declined_query)['total'];


// Count action-taken reports
$action_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports WHERE status = 'Action Taken'"
);

$action_taken = mysqli_fetch_assoc($action_query)['total'];


// Count all reports
$total_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports"
);

$total_reports = mysqli_fetch_assoc($total_query)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <header class="admin-header">

        <div class="container">

            <div class="admin-header-content">

                <div>
                    <h1>Admin Dashboard</h1>

                    <p>
                        Welcome, <?php echo htmlspecialchars($admin_name); ?>
                    </p>
                </div>

                <a href="logout.php" class="logout-btn">
                    Logout
                </a>

            </div>

        </div>

    </header>


    <main class="container">

        <section class="dashboard">

            <h2>Emergency Reports Overview</h2>


            <!-- Statistics -->

            <div class="stats-grid">

                <div class="stat-card">

                    <h3>Total Reports</h3>

                    <p>
                        <?php echo $total_reports; ?>
                    </p>

                </div>


                <div class="stat-card pending">

                    <h3>Pending</h3>

                    <p>
                        <?php echo $pending_reports; ?>
                    </p>

                </div>


                <div class="stat-card confirmed">

                    <h3>Confirmed</h3>

                    <p>
                        <?php echo $confirmed_reports; ?>
                    </p>

                </div>


                <div class="stat-card declined">

                    <h3>Declined</h3>

                    <p>
                        <?php echo $declined_reports; ?>
                    </p>

                </div>


                <div class="stat-card action">

                    <h3>Action Taken</h3>

                    <p>
                        <?php echo $action_taken; ?>
                    </p>

                </div>

            </div>


            <!-- Admin Actions -->

            <section class="admin-actions">

                <h2>Manage Reports</h2>

                <div class="action-grid">

                    <a href="pending_reports.php" class="admin-action-card">

                        <h3>Pending Reports</h3>

                        <p>
                            View and review emergency reports
                            awaiting confirmation.
                        </p>

                        <span>
                            <?php echo $pending_reports; ?> Pending
                        </span>

                    </a>


                    <a href="pending_reports.php" class="admin-action-card">

                        <h3>View Reports</h3>

                        <p>
                            Review submitted emergency reports
                            and take appropriate action.
                        </p>

                    </a>

                </div>

            </section>

        </section>

    </main>


    <footer class="footer">

        <p>
            &copy; <?php echo date("Y"); ?>
            Community Emergency Reporting System
        </p>

    </footer>

</body>

</html>