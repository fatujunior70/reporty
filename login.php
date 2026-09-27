<?php

session_start();

include '../config.php';

$error = "";

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Process login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {

        $error = "Please enter your email and password.";

    } else {

        $sql = "SELECT id, name, email, password
                FROM admin
                WHERE email = ?
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {

            $error = "Database error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param($stmt, "s", $email);

            mysqli_stmt_execute($stmt);

            mysqli_stmt_bind_result(
                $stmt,
                $id,
                $name,
                $db_email,
                $db_password
            );

            if (mysqli_stmt_fetch($stmt)) {

                if (password_verify($password, $db_password)) {

                    session_regenerate_id(true);

                    $_SESSION['admin_id'] = $id;
                    $_SESSION['admin_name'] = $name;
                    $_SESSION['admin_email'] = $db_email;

                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $error = "Invalid email or password.";

                }

            } else {

                $error = "No administrator account found with this email.";

            }

            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <main class="login-container">

        <section class="login-box">

            <h1>Admin Login</h1>

            <p>
                Community Emergency Reporting System
            </p>

            <?php if (!empty($error)): ?>

                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="login.php">

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter admin email"
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >

                </div>

                <button type="submit" class="btn">
                    Login
                </button>

            </form>

            <a href="../index.php" class="back-link">
                Back to Home
            </a>

        </section>

    </main>

</body>

</html>