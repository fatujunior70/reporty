<?php

session_start();
include '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if report ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: pending_reports.php");
    exit();
}

$report_id = (int) $_GET['id'];

// Get report details
$sql = "SELECT
            id,
            incident_category,
            incident_type,
            description,
            location,
            latitude,
            longitude,
            incident_date,
            incident_time,
            status,
            created_at
        FROM reports
        WHERE id = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database error.");
}

mysqli_stmt_bind_param($stmt, "i", $report_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) !== 1) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Report not found.");
}

$report = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Report #<?php echo $report['id']; ?> - Emergency Reporting System
    </title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <header class="admin-header">

        <div class="container">

            <div class="admin-header-content">

                <div>

                    <h1>Report Details</h1>

                    <p>
                        Emergency Report #<?php echo $report['id']; ?>
                    </p>

                </div>

                <div class="admin-navigation">

                    <a href="dashboard.php">
                        Dashboard
                    </a>

                    <a href="pending_reports.php">
                        Pending Reports
                    </a>

                    <a href="logout.php" class="logout-btn">
                        Logout
                    </a>

                </div>

            </div>

        </div>

    </header>


    <main class="container">

        <section class="report-details-section">

            <!-- Success Message -->

            <?php if (isset($_GET['success'])): ?>

                <div class="success-message">

                    Report updated successfully.

                </div>

            <?php endif; ?>


            <!-- Report Header -->

            <div class="details-header">

                <div>

                    <h2>
                        Emergency Report #<?php echo $report['id']; ?>
                    </h2>

                    <p>
                        Submitted on
                        <?php echo htmlspecialchars($report['created_at']); ?>
                    </p>

                </div>


                <div class="status-badge">

                    <?php echo htmlspecialchars($report['status']); ?>

                </div>

            </div>


            <!-- Report Information -->

            <div class="details-grid">

                <div class="detail-item">

                    <h3>Incident Category</h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $report['incident_category']
                        );
                        ?>
                    </p>

                </div>


                <div class="detail-item">

                    <h3>Incident Type</h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $report['incident_type']
                        );
                        ?>
                    </p>

                </div>


                <div class="detail-item">

                    <h3>Location</h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $report['location']
                        );
                        ?>
                    </p>

                </div>


                <div class="detail-item">

                    <h3>Incident Date</h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $report['incident_date']
                        );
                        ?>
                    </p>

                </div>


                <div class="detail-item">

                    <h3>Incident Time</h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $report['incident_time']
                        );
                        ?>
                    </p>

                </div>


                <div class="detail-item">

                    <h3>Latitude</h3>

                    <p>

                        <?php

                        if ($report['latitude'] !== null) {

                            echo htmlspecialchars(
                                $report['latitude']
                            );

                        } else {

                            echo "Not provided";

                        }

                        ?>

                    </p>

                </div>


                <div class="detail-item">

                    <h3>Longitude</h3>

                    <p>

                        <?php

                        if ($report['longitude'] !== null) {

                            echo htmlspecialchars(
                                $report['longitude']
                            );

                        } else {

                            echo "Not provided";

                        }

                        ?>

                    </p>

                </div>

            </div>


            <!-- Description -->

            <div class="description-box">

                <h3>Incident Description</h3>

                <p>
                    <?php
                    echo nl2br(
                        htmlspecialchars(
                            $report['description']
                        )
                    );
                    ?>
                </p>

            </div>


            <!-- Confirm / Decline -->

            <?php if ($report['status'] === 'Pending'): ?>

                <div class="report-actions">

                    <h2>Review Report</h2>

                    <p>
                        Review the report carefully before making a decision.
                    </p>


                    <div class="action-buttons">

                        <!-- Confirm -->

                        <form
                            action="update_report.php"
                            method="POST"
                            onsubmit="return confirm(
                                'Are you sure you want to confirm this report?'
                            );"
                        >

                            <input
                                type="hidden"
                                name="report_id"
                                value="<?php echo $report['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Confirmed"
                            >

                            <button
                                type="submit"
                                class="confirm-btn"
                            >
                                Confirm Report
                            </button>

                        </form>


                        <!-- Decline -->

                        <form
                            action="update_report.php"
                            method="POST"
                            onsubmit="return confirm(
                                'Are you sure you want to decline this report?'
                            );"
                        >

                            <input
                                type="hidden"
                                name="report_id"
                                value="<?php echo $report['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Declined"
                            >

                            <button
                                type="submit"
                                class="decline-btn"
                            >
                                Decline Report
                            </button>

                        </form>

                    </div>

                </div>

            <?php endif; ?>


            <!-- Take Action -->

            <?php if ($report['status'] === 'Confirmed'): ?>

                <div class="take-action-section">

                    <h2>Take Action</h2>

                    <p>
                        Record the action taken in response to this
                        confirmed emergency report.
                    </p>


                    <form
                        action="update_report.php"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="report_id"
                            value="<?php echo $report['id']; ?>"
                        >

                        <input
                            type="hidden"
                            name="status"
                            value="Action Taken"
                        >


                        <div class="form-group">

                            <label for="action_taken">
                                Action Taken
                            </label>

                            <input
                                type="text"
                                name="action_taken"
                                id="action_taken"
                                maxlength="255"
                                placeholder="e.g. Security team was contacted"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="comment">
                                Comment
                            </label>

                            <textarea
                                name="comment"
                                id="comment"
                                rows="5"
                                placeholder="Enter additional information about the action..."
                            ></textarea>

                        </div>


                        <button
                            type="submit"
                            class="confirm-btn"
                            onclick="return confirm(
                                'Are you sure you want to record this action?'
                            );"
                        >
                            Record Action
                        </button>

                    </form>

                </div>

            <?php endif; ?>


            <!-- Action Already Taken -->

            <?php if ($report['status'] === 'Action Taken'): ?>

                <div class="success-message">

                    <h3>Action Completed</h3>

                    <p>
                        Action has already been recorded for this report.
                    </p>

                </div>

            <?php endif; ?>


            <!-- Declined -->

            <?php if ($report['status'] === 'Declined'): ?>

                <div class="error-message">

                    <h3>Report Declined</h3>

                    <p>
                        This report has been declined and no further
                        action can be taken.
                    </p>

                </div>

            <?php endif; ?>


            <!-- Navigation -->

            <div class="back-actions">

                <a
                    href="pending_reports.php"
                    class="back-link"
                >
                    ← Back to Pending Reports
                </a>

                <a
                    href="dashboard.php"
                    class="back-link"
                >
                    ← Back to Dashboard
                </a>

            </div>

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