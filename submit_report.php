<?php

include 'config.php';

// Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: send_report.php");
    exit();
}

// Get and clean form data
$incident_category = trim($_POST['incident_category'] ?? '');
$incident_type     = trim($_POST['incident_type'] ?? '');
$description       = trim($_POST['description'] ?? '');
$location          = trim($_POST['location'] ?? '');
$latitude          = trim($_POST['latitude'] ?? '');
$longitude         = trim($_POST['longitude'] ?? '');
$incident_date     = trim($_POST['incident_date'] ?? '');
$incident_time     = trim($_POST['incident_time'] ?? '');

// Validate required fields
if (
    empty($incident_category) ||
    empty($incident_type) ||
    empty($description) ||
    empty($location) ||
    empty($incident_date) ||
    empty($incident_time)
) {
    die("Please fill in all required fields.");
}

// Convert empty coordinates to NULL
$latitude = ($latitude === '') ? null : $latitude;
$longitude = ($longitude === '') ? null : $longitude;

// Prepare SQL statement
$sql = "INSERT INTO reports
        (incident_category, incident_type, description, location,
         latitude, longitude, incident_date, incident_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

// Bind parameters
mysqli_stmt_bind_param(
    $stmt,
    "ssssddss",
    $incident_category,
    $incident_type,
    $description,
    $location,
    $latitude,
    $longitude,
    $incident_date,
    $incident_time
);

// Execute statement
if (mysqli_stmt_execute($stmt)) {

    // Get the newly created report ID
    $report_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Report Submitted</title>

        <link rel="stylesheet" href="assets/css/style.css">
    </head>

    <body>

        <main class="container">

            <section class="success-section">

                <h1>Report Submitted Successfully</h1>

                <p>
                    Your emergency report has been successfully submitted.
                </p>

                <p>
                    <strong>Report ID:</strong>
                    #<?php echo htmlspecialchars($report_id); ?>
                </p>

                <p>
                    Your report is currently being reviewed by the administrator.
                </p>

                <a href="index.php" class="btn">
                    Return to Home
                </a>

                <a href="send_report.php" class="back-link">
                    Send Another Report
                </a>

            </section>

        </main>

    </body>

    </html>

    <?php

} else {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Unable to submit report. Please try again later.");
}

?>