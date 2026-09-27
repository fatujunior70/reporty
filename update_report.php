<?php

session_start();
include '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit();
}

$admin_id = (int) $_SESSION['admin_id'];

$report_id = isset($_POST['report_id'])
    ? (int) $_POST['report_id']
    : 0;

$status = trim($_POST['status'] ?? '');

$action_taken = trim($_POST['action_taken'] ?? '');
$comment = trim($_POST['comment'] ?? '');


// Validate report ID
if ($report_id <= 0) {
    die("Invalid report ID.");
}


// Allowed statuses
$allowed_statuses = [
    'Confirmed',
    'Declined',
    'Action Taken'
];


// Validate status
if (!in_array($status, $allowed_statuses, true)) {
    die("Invalid report status.");
}


// Check that the report exists
$check_sql = "SELECT id, status
              FROM reports
              WHERE id = ?
              LIMIT 1";

$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    die("Database error.");
}

mysqli_stmt_bind_param(
    $check_stmt,
    "i",
    $report_id
);

mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);

if (!$check_result || mysqli_num_rows($check_result) !== 1) {

    mysqli_stmt_close($check_stmt);
    mysqli_close($conn);

    die("Report not found.");
}

$current_report = mysqli_fetch_assoc($check_result);

mysqli_stmt_close($check_stmt);


// Prevent changing a report that has already been processed
if (
    $current_report['status'] !== 'Pending'
    && $status !== 'Action Taken'
) {

    mysqli_close($conn);

    die("This report has already been reviewed.");
}


// =====================================================
// CONFIRM OR DECLINE REPORT
// =====================================================

if ($status === 'Confirmed' || $status === 'Declined') {

    $sql = "UPDATE reports
            SET status = ?
            WHERE id = ?
            AND status = 'Pending'";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Database error.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $status,
        $report_id
    );

    if (mysqli_stmt_execute($stmt)) {

        // Record the administrative action
        $action_text = ($status === 'Confirmed')
            ? 'Report confirmed'
            : 'Report declined';

        $action_sql = "INSERT INTO actions
                       (report_id, admin_id, action_taken, comment)
                       VALUES (?, ?, ?, ?)";

        $action_stmt = mysqli_prepare(
            $conn,
            $action_sql
        );

        if ($action_stmt) {

            mysqli_stmt_bind_param(
                $action_stmt,
                "iiss",
                $report_id,
                $admin_id,
                $action_text,
                $comment
            );

            mysqli_stmt_execute($action_stmt);

            mysqli_stmt_close($action_stmt);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header(
            "Location: report_details.php?id="
            . $report_id
            . "&success=1"
        );

        exit();

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        die("Unable to update report.");
    }
}


// =====================================================
// TAKE ACTION
// =====================================================

if ($status === 'Action Taken') {

    // Action description is required
    if (empty($action_taken)) {

        mysqli_close($conn);

        die("Please describe the action taken.");
    }


    // Update report status
    $update_sql = "UPDATE reports
                   SET status = 'Action Taken'
                   WHERE id = ?";

    $update_stmt = mysqli_prepare(
        $conn,
        $update_sql
    );

    if (!$update_stmt) {
        die("Database error.");
    }

    mysqli_stmt_bind_param(
        $update_stmt,
        "i",
        $report_id
    );


    if (!mysqli_stmt_execute($update_stmt)) {

        mysqli_stmt_close($update_stmt);
        mysqli_close($conn);

        die("Unable to update report.");
    }

    mysqli_stmt_close($update_stmt);


    // Record action in actions table
    $action_sql = "INSERT INTO actions
                   (report_id, admin_id, action_taken, comment)
                   VALUES (?, ?, ?, ?)";

    $action_stmt = mysqli_prepare(
        $conn,
        $action_sql
    );

    if (!$action_stmt) {
        mysqli_close($conn);
        die("Unable to record administrative action.");
    }


    mysqli_stmt_bind_param(
        $action_stmt,
        "iiss",
        $report_id,
        $admin_id,
        $action_taken,
        $comment
    );


    if (!mysqli_stmt_execute($action_stmt)) {

        mysqli_stmt_close($action_stmt);
        mysqli_close($conn);

        die("Unable to record action.");
    }


    mysqli_stmt_close($action_stmt);
    mysqli_close($conn);


    header(
        "Location: report_details.php?id="
        . $report_id
        . "&success=1"
    );

    exit();
}

?>