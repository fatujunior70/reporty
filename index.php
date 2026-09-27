<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Emergency Reporting System</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>BUPOLY Emergency Reporting System</h1>
            <p>Report emergencies quickly and help keep our community safe.</p>
        </div>
    </header>

    <main class="container">

        <section class="hero">
            <h2>Welcome</h2>

            <p>
                Use this system to report emergencies or dangerous situations
                within the community. Your report will be received and reviewed
                by the administrator.
            </p>

            <div class="home-buttons">
                <a href="send_report.php" class="btn">
                    Send Emergency Report
                </a>

                <a href="admin/login.php" class="btn admin-btn">
                    Admin Login
                </a>
            </div>
        </section>

        <section class="info-section">

            <div class="info-card">
                <h3>Report</h3>
                <p>
                    Submit details about an emergency or incident in your area.
                </p>
            </div>

            <div class="info-card">
                <h3>Review</h3>
                <p>
                    Submitted reports are reviewed by the administrator.
                </p>
            </div>

            <div class="info-card">
                <h3>Action</h3>
                <p>
                    Confirmed emergencies can receive appropriate action.
                </p>
            </div>

        </section>

    </main>

    <footer class="footer">
        <p>
            &copy; <?php echo date("Y"); ?>
            Community Emergency Reporting System
        </p>
    </footer>

    <script src="assets/js/script.js"></script>

</body>
</html>