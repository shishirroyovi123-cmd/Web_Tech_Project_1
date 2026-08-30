<?php

session_start();

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/controllers/ApplicationStatusController.php";


// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


// Only student can access this page
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}


// Logged-in student ID
$studentId = $_SESSION["user_id"];


// Create controller
$controller = new ApplicationStatusController($conn, $studentId);



// =================================================
// AJAX REQUEST
// =================================================

if (
    isset($_GET["ajax"]) &&
    $_GET["ajax"] == "get_application_status"
) {

    header("Content-Type: application/json");

    $applicationId = isset($_GET["application_id"])
        ? (int)$_GET["application_id"]
        : 0;

    $data = $controller->getApplicationStatus($applicationId);

    echo json_encode($data);

    exit();
}



// Get applications for dropdown
$applications = $controller->getApplications();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SEPMS - Application & Exchange Status</title>

    <link
        rel="stylesheet"
        href="application_status.css"
    >

</head>


<body>


<!-- ================= HEADER ================= -->

<?php include "header.php"; ?>



<!-- ================= PAGE LAYOUT ================= -->

<div class="page_layout">


    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="sidebar_heading">
            STUDENT PANEL
        </div>


        <nav class="sidebar_menu">

            <a
                href="student_dashboard.php"
                class="sidebar_item"
            >
                Dashboard
            </a>


            <a
                href="search_apply.php"
                class="sidebar_item"
            >
                Search & Apply
            </a>


            <a
                href="application_form.php"
                class="sidebar_item"
            >
                Apply Program
            </a>


            <a
                href="my_applications.php"
                class="sidebar_item"
            >
                My Applications
            </a>


            <a
                href="application_status.php"
                class="sidebar_item active"
            >
                Application Status
            </a>

        </nav>



        <div class="sidebar_bottom">

            <a
                href="update_profile.php"
                class="sidebar_item"
            >
                Update Profile
            </a>


            <a
                href="change_password.php"
                class="sidebar_item"
            >
                Change Password
            </a>


            <a
                href="login.php"
                class="sidebar_item logout"
            >
                Logout
            </a>

        </div>

    </aside>



    <!-- ================= MAIN CONTENT ================= -->

    <main class="main_content">


        <!-- ================= PAGE HEADER ================= -->

        <section class="page_header">

            <h1>
                Track Application & Exchange Status
            </h1>

            <p>
                View your application progress, remarks,
                nomination status and exchange record.
            </p>

        </section>



        <!-- ================= ERROR ================= -->

        <p
            id="js_error"
            style="
                color:red;
                text-align:center;
                display:none;
            "
        >
        </p>



        <!-- =================================================
             APPLICATION SELECTION
        ================================================== -->

        <section class="content_box">

            <h2>
                Select Application
            </h2>


            <p class="section_description">

                Select an application to view its current status
                and exchange information.

            </p>


            <div class="application_selector">


                <div class="form_group">

                    <label for="application_id">
                        Application
                    </label>


                    <select
                        id="application_id"
                        name="application_id"
                    >

                        <option value="">
                            Select Application
                        </option>


                        <?php foreach ($applications as $app): ?>

                            <option
                                value="<?php echo (int)$app["application_id"]; ?>"
                            >

                                #<?php echo (int)$app["application_id"]; ?>

                                -

                                <?php
                                echo htmlspecialchars(
                                    $app["program_name"]
                                );
                                ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>



                <button
                    type="button"
                    class="view_button"
                    onclick="loadApplicationStatus()"
                >

                    View Status

                </button>


            </div>

        </section>



        <!-- =================================================
             APPLICATION INFORMATION
        ================================================== -->

        <section class="content_box">


            <h2>
                Application Information
            </h2>


            <div class="information_grid">


                <div class="information_item">

                    <span class="information_label">
                        Application ID
                    </span>

                    <span
                        class="information_value"
                        id="application_id_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Program ID
                    </span>

                    <span
                        class="information_value"
                        id="program_id_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Program Name
                    </span>

                    <span
                        class="information_value"
                        id="program_name_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        University
                    </span>

                    <span
                        class="information_value"
                        id="university_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Country
                    </span>

                    <span
                        class="information_value"
                        id="country_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Application Date
                    </span>

                    <span
                        class="information_value"
                        id="application_date_value"
                    >
                        —
                    </span>

                </div>


            </div>

        </section>



        <!-- =================================================
             APPLICATION STATUS
        ================================================== -->

        <section class="content_box">


            <h2>
                Application Status
            </h2>


            <div class="status_container">


                <!-- STEP 1 -->

                <div class="status_item">

                    <div class="status_number">
                        1
                    </div>


                    <div class="status_content">

                        <h3>
                            Application Submitted
                        </h3>

                        <p>
                            Your application has been submitted.
                        </p>

                        <span
                            class="status_badge pending"
                            id="submitted_status"
                        >
                            —
                        </span>

                    </div>

                </div>



                <div class="status_line"></div>



                <!-- STEP 2 -->

                <div class="status_item">

                    <div class="status_number">
                        2
                    </div>


                    <div class="status_content">

                        <h3>
                            Application Review
                        </h3>

                        <p>
                            Coordinator review status.
                        </p>

                        <span
                            class="status_badge pending"
                            id="review_status"
                        >
                            —
                        </span>

                    </div>

                </div>



                <div class="status_line"></div>



                <!-- STEP 3 -->

                <div class="status_item">

                    <div class="status_number">
                        3
                    </div>


                    <div class="status_content">

                        <h3>
                            Application Decision
                        </h3>

                        <p>
                            Final application decision.
                        </p>

                        <span
                            class="status_badge pending"
                            id="decision_status"
                        >
                            —
                        </span>

                    </div>

                </div>


            </div>

        </section>



        <!-- =================================================
             COORDINATOR REMARKS
        ================================================== -->

        <section class="content_box">


            <h2>
                Coordinator Remarks
            </h2>


            <div class="remarks_box">


                <div class="remarks_header">

                    <span>
                        Latest Remark
                    </span>

                    <span id="remark_date">
                        —
                    </span>

                </div>


                <p
                    class="remarks_text"
                    id="remarks_text"
                >

                    No remarks available.

                </p>


            </div>

        </section>



        <!-- =================================================
             NOMINATION STATUS
        ================================================== -->

        <section class="content_box">


            <h2>
                Nomination Status
            </h2>


            <div class="nomination_grid">


                <div class="information_item">

                    <span class="information_label">
                        Nomination Status
                    </span>

                    <span
                        class="status_badge pending"
                        id="nomination_status_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Nomination Date
                    </span>

                    <span
                        class="information_value"
                        id="nomination_date_value"
                    >
                        —
                    </span>

                </div>



                <div class="information_item">

                    <span class="information_label">
                        Nomination Remarks
                    </span>

                    <span
                        class="information_value"
                        id="nomination_remarks_value"
                    >
                        —
                    </span>

                </div>


            </div>

        </section>



        <!-- =================================================
             EXCHANGE RECORD
        ================================================== -->

        <section class="content_box">


            <h2>
                Exchange Record
            </h2>


            <p class="section_description">

                Your exchange record will appear here after
                your exchange has been confirmed.

            </p>


            <div class="record_table_container">


                <table class="record_table">


                    <thead>

                        <tr>

                            <th>Program</th>
                            <th>University</th>
                            <th>Country</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody id="exchange_record_body">

                        <tr>

                            <td
                                colspan="6"
                                class="empty_data"
                            >

                                No exchange record available.

                            </td>

                        </tr>

                    </tbody>


                </table>


            </div>

        </section>



        <!-- ================= FOOTER ================= -->

        <?php include "footer.php"; ?>


    </main>


</div>



<!-- ================= JAVASCRIPT ================= -->

<script>


function showError(message)
{
    var errorBox = document.getElementById("js_error");

    errorBox.innerHTML = message;
    errorBox.style.display = "block";

    setTimeout(function()
    {
        errorBox.style.display = "none";
    }, 4000);
}



function loadApplicationStatus()
{
    var applicationId =
        document.getElementById("application_id").value;


    if (applicationId == "")
    {
        showError("Please select an application.");
        return;
    }


    var button =
        document.querySelector(".view_button");


    button.innerHTML = "Loading...";
    button.disabled = true;


    var url =
        "application_status.php?ajax=get_application_status&application_id="
        + applicationId;


    var request = new XMLHttpRequest();


    request.open(
        "GET",
        url,
        true
    );


    request.onreadystatechange = function()
    {
        if (request.readyState == 4)
        {
            button.innerHTML = "View Status";
            button.disabled = false;


            if (request.status == 200)
            {
                try
                {
                    var data =
                        JSON.parse(request.responseText);


                    if (data.success)
                    {
                        showApplicationData(data);
                    }
                    else
                    {
                        showError(data.message);
                    }
                }
                catch (error)
                {
                    showError(
                        "Error loading application information."
                    );
                }
            }
            else
            {
                showError(
                    "Server error. Please try again."
                );
            }
        }
    };


    request.send();
}



function showApplicationData(data)
{
    var app = data.application;


    // =============================================
    // APPLICATION INFORMATION
    // =============================================

    document.getElementById(
        "application_id_value"
    ).innerHTML = app.application_id;


    document.getElementById(
        "program_id_value"
    ).innerHTML = app.program_id;


    document.getElementById(
        "program_name_value"
    ).innerHTML = app.program_name;


    document.getElementById(
        "university_value"
    ).innerHTML = app.university_name;


    document.getElementById(
        "country_value"
    ).innerHTML = app.country_name;


    document.getElementById(
        "application_date_value"
    ).innerHTML = app.application_date;



    // =============================================
    // APPLICATION STATUS
    // =============================================

    document.getElementById(
        "submitted_status"
    ).innerHTML = "Submitted";


    var status =
        app.application_status.toLowerCase();



    // Step 2
    if (
        status == "pending" ||
        status == "under_review" ||
        status == "reviewing"
    )
    {
        document.getElementById(
            "review_status"
        ).innerHTML = "Pending";
    }
    else
    {
        document.getElementById(
            "review_status"
        ).innerHTML = "Completed";
    }



    // Step 3
    if (status == "approved")
    {
        document.getElementById(
            "decision_status"
        ).innerHTML = "Approved";
    }
    else if (status == "rejected")
    {
        document.getElementById(
            "decision_status"
        ).innerHTML = "Rejected";
    }
    else if (status == "nominated")
    {
        document.getElementById(
            "decision_status"
        ).innerHTML = "Approved";
    }
    else
    {
        document.getElementById(
            "decision_status"
        ).innerHTML = "Pending";
    }



    // =============================================
    // NOMINATION INFORMATION
    // =============================================

    if (data.nomination != null)
    {
        document.getElementById(
            "nomination_status_value"
        ).innerHTML =
            data.nomination.status;


        document.getElementById(
            "nomination_date_value"
        ).innerHTML =
            data.nomination.nomination_date;


        document.getElementById(
            "nomination_remarks_value"
        ).innerHTML =
            "No remarks available.";
    }
    else
    {
        document.getElementById(
            "nomination_status_value"
        ).innerHTML = "Not Nominated";


        document.getElementById(
            "nomination_date_value"
        ).innerHTML = "—";


        document.getElementById(
            "nomination_remarks_value"
        ).innerHTML =
            "No nomination remarks available.";
    }



    // =============================================
    // COORDINATOR REMARKS
    // =============================================

    document.getElementById(
        "remark_date"
    ).innerHTML = "—";


    document.getElementById(
        "remarks_text"
    ).innerHTML =
        "No remarks available.";
}


</script>


</body>

</html>