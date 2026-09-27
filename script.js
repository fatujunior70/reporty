document.addEventListener("DOMContentLoaded", function () {

    /*
     * ==========================================
     * EMERGENCY REPORT FORM
     * ==========================================
     */

    const reportForm =
        document.getElementById("reportForm");

    const confirmModal =
        document.getElementById("confirmModal");

    const confirmReport =
        document.getElementById("confirmReport");

    const cancelReport =
        document.getElementById("cancelReport");


    /*
     * This variable prevents the confirmation
     * popup from appearing again after the user
     * has already confirmed the report.
     */

    let reportConfirmed = false;


    if (
        reportForm &&
        confirmModal &&
        confirmReport &&
        cancelReport
    ) {

        reportForm.addEventListener(
            "submit",
            function (event) {

                /*
                 * If the user has already confirmed,
                 * allow the form to submit normally.
                 */

                if (reportConfirmed) {

                    return;

                }


                /*
                 * Prevent submission temporarily.
                 */

                event.preventDefault();


                const category =
                    document.getElementById(
                        "incident_category"
                    );

                const incidentType =
                    document.getElementById(
                        "incident_type"
                    );

                const description =
                    document.getElementById(
                        "description"
                    );

                const location =
                    document.getElementById(
                        "location"
                    );

                const date =
                    document.getElementById(
                        "incident_date"
                    );

                const time =
                    document.getElementById(
                        "incident_time"
                    );


                /*
                 * Remove unnecessary spaces.
                 */

                incidentType.value =
                    incidentType.value.trim();

                description.value =
                    description.value.trim();

                location.value =
                    location.value.trim();


                /*
                 * Check required fields.
                 */

                if (
                    category.value === "" ||
                    incidentType.value === "" ||
                    description.value === "" ||
                    location.value === "" ||
                    date.value === "" ||
                    time.value === ""
                ) {

                    alert(
                        "Please complete all required fields before submitting the report."
                    );

                    return;

                }


                /*
                 * Check description length.
                 */

                if (
                    description.value.length < 10
                ) {

                    alert(
                        "Please provide a more detailed description of the emergency."
                    );

                    description.focus();

                    return;

                }


                /*
                 * Show custom confirmation popup.
                 */

                confirmModal.classList.add("show");

            }
        );


        /*
         * Cancel report submission.
         */

        cancelReport.addEventListener(
            "click",
            function () {

                confirmModal.classList.remove(
                    "show"
                );

            }
        );


        /*
         * Confirm report submission.
         */

        confirmReport.addEventListener(
            "click",
            function () {

                /*
                 * Mark report as confirmed.
                 */

                reportConfirmed = true;


                /*
                 * Hide confirmation popup.
                 */

                confirmModal.classList.remove(
                    "show"
                );


                /*
                 * Submit the form.
                 */

                reportForm.submit();

            }
        );

    }


    /*
     * ==========================================
     * AUTOMATIC DATE VALIDATION
     * ==========================================
     */

    const incidentDate =
        document.getElementById("incident_date");


    if (incidentDate) {

        const today = new Date();

        const year =
            today.getFullYear();

        const month =
            String(
                today.getMonth() + 1
            ).padStart(2, "0");

        const day =
            String(
                today.getDate()
            ).padStart(2, "0");


        const formattedDate =
            `${year}-${month}-${day}`;


        /*
         * Prevent the user from selecting
         * a future date.
         */

        incidentDate.setAttribute(
            "max",
            formattedDate
        );

    }


    /*
     * ==========================================
     * ADMIN CONFIRM BUTTON CURSOR
     * ==========================================
     */

    const adminConfirmButtons =
        document.querySelectorAll(
            ".confirm-btn"
        );


    adminConfirmButtons.forEach(
        function (button) {

            button.addEventListener(
                "mouseenter",
                function () {

                    button.style.cursor =
                        "pointer";

                }
            );

        }
    );


    /*
     * ==========================================
     * ADMIN DECLINE BUTTON CURSOR
     * ==========================================
     */

    const declineButtons =
        document.querySelectorAll(
            ".decline-btn"
        );


    declineButtons.forEach(
        function (button) {

            button.addEventListener(
                "mouseenter",
                function () {

                    button.style.cursor =
                        "pointer";

                }
            );

        }
    );


    /*
     * ==========================================
     * TAKE ACTION INPUT LIMIT
     * ==========================================
     */

    const actionInput =
        document.getElementById(
            "action_taken"
        );


    if (actionInput) {

        actionInput.addEventListener(
            "input",
            function () {

                if (
                    this.value.length > 255
                ) {

                    this.value =
                        this.value.substring(
                            0,
                            255
                        );

                }

            }
        );

    }


    /*
     * ==========================================
     * PREVENT MULTIPLE SUBMISSIONS
     * ==========================================
     *
     * This applies to admin forms only.
     * The emergency report form is excluded
     * because it uses the custom confirmation
     * popup above.
     */

    const forms =
        document.querySelectorAll(
            "form"
        );


    forms.forEach(
        function (form) {

            /*
             * Skip the emergency report form.
             */

            if (
                form.id === "reportForm"
            ) {

                return;

            }


            form.addEventListener(
                "submit",
                function () {

                    const submitButton =
                        form.querySelector(
                            'button[type="submit"]'
                        );


                    if (submitButton) {

                        setTimeout(
                            function () {

                                submitButton.disabled =
                                    true;

                                submitButton.style.opacity =
                                    "0.7";

                                submitButton.textContent =
                                    "Processing...";

                            },
                            100
                        );

                    }

                }
            );

        }
    );

});