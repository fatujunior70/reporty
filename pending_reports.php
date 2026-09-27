<?php

session_start();
include '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get all pending reports
$sql = "SELECT
            id,
            incident_category,
            incident_type,
            description,
            location,
            incident_date,
            incident_time,
            created_at
        FROM reports
        WHERE status = 'Pending'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pending Reports</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <header class="admin-header">

        <div class="container">

            <div class="admin-header-content">

                <div>
                    <h1>Pending Reports</h1>

                    <p>
                        Emergency reports awaiting review
                    </p>
                </div>

                <div class="admin-navigation">

                    <a href="dashboard.php">
                        Dashboard
                    </a>

                    <a href="logout.php" class="logout-btn">
                        Logout
                    </a>

                </div>

            </div>

        </div>

    </header>


    <main class="container">

        <section class="reports-section">

            <div class="section-heading">

                <div>
                    <h2>Pending Emergency Reports</h2>

                    <p>
                        Review the reports below and select one
                        to view its full details.
                    </p>
                </div>

            </div>


            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <div class="reports-table-container">

                    <table class="reports-table">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Category</th>

                                <th>Incident Type</th>

                                <th>Location</th>

                                <th>Date</th>

                                <th>Time</th>

                                <th>Submitted</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php while ($report = mysqli_fetch_assoc($result)): ?>

                                <tr>

                                    <td>
                                        #<?php echo $report['id']; ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['incident_category']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['incident_type']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['location']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['incident_date']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['incident_time']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['created_at']
                                        );
                                        ?>
                                    </td>

                                    <td>

                                        <a
                                            href="report_details.php?id=<?php echo $report['id']; ?>"
                                            class="view-btn"
                                        >
                                            View Details
                                        </a>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="no-reports">

                    <h3>No Pending Reports</h3>

                    <p>
                        There are currently no emergency reports
                        waiting for review.
                    </p>

                    <a href="dashboard.php" class="btn">
                        Back to Dashboard
                    </a>

                </div>

            <?php endif; ?>

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

<?php

mysqli_close($conn);

?>