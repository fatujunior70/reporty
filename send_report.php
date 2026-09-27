<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Send Emergency Report</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

    <header class="header">

        <div class="container">

            <h1>
                BUPOLY Emergency Reporting System
            </h1>

            <p>
                Submit an emergency report
            </p>

        </div>

    </header>


    <main class="container">

        <section class="form-section">

            <h2>
                Emergency Report Form
            </h2>

            <p class="form-intro">

                Please provide accurate information about the emergency.

            </p>


            <form
                action="submit_report.php"
                method="POST"
                id="reportForm"
            >


                <!-- Incident Category -->

                <div class="form-group">

                    <label for="incident_category">

                        Incident Category

                    </label>


                    <select
                        name="incident_category"
                        id="incident_category"
                        required
                    >

                        <option value="">
                            -- Select Category --
                        </option>

                        <option value="Fire">
                            Fire
                        </option>

                        <option value="Medical">
                            Medical Emergency
                        </option>

                        <option value="Crime">
                            Crime
                        </option>

                        <option value="Accident">
                            Road Accident
                        </option>

                        <option value="Flood">
                            Flood
                        </option>

                        <option value="Security">
                            Security Threat
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>


                <!-- Incident Type -->

                <div class="form-group">

                    <label for="incident_type">

                        Incident Type

                    </label>


                    <input
                        type="text"
                        name="incident_type"
                        id="incident_type"
                        placeholder="e.g. Building fire"
                        maxlength="100"
                        required
                    >

                </div>


                <!-- Description -->

                <div class="form-group">

                    <label for="description">

                        Description

                    </label>


                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        placeholder="Describe what happened..."
                        required
                    ></textarea>

                </div>


                <!-- Location -->

                <div class="form-group">

                    <label for="location">

                        Location

                    </label>


                    <input
                        type="text"
                        name="location"
                        id="location"
                        placeholder="Enter the location of the incident"
                        maxlength="255"
                        required
                    >

                </div>


                <!-- Incident Date -->

                <div class="form-group">

                    <label for="incident_date">

                        Incident Date

                    </label>


                    <input
                        type="date"
                        name="incident_date"
                        id="incident_date"
                        required
                    >

                </div>


                <!-- Incident Time -->

                <div class="form-group">

                    <label for="incident_time">

                        Incident Time

                    </label>


                    <input
                        type="time"
                        name="incident_time"
                        id="incident_time"
                        required
                    >

                </div>


                <!-- Submit -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn"
                    >

                        Submit Emergency Report

                    </button>


                    <a
                        href="index.php"
                        class="back-link"
                    >

                        Back to Home

                    </a>

                </div>


            </form>

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