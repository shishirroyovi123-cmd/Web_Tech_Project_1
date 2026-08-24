<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $application_id = trim($_POST["application_id"]);
    $student = trim($_POST["student"]);
    $program = $_POST["program"];
    $country = $_POST["country"];
    $status = $_POST["status"];


    /* Check Application ID */

    if ($application_id != "" && !is_numeric($application_id)) {

        header(
            "Location: review_applications.php?error=Application ID must contain numbers only."
        );

        exit();

    }


    /* Search / Filter information */

    else {

        header(
            "Location: review_applications.php?success=Search information is valid."
        );

        exit();

    }

}


/* ================= ERROR ================= */

if (isset($_GET["error"])) {

    $error = $_GET["error"];

}


/* ================= SUCCESS ================= */

if (isset($_GET["success"])) {

    $success = $_GET["success"];

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Review Applications</title>

    <link rel="stylesheet"
          href="review_applications.css">

    <script src="review_applications.js"></script>

</head>


<body>


    <!-- =====================================================
                         HEADER
    ====================================================== -->

   <?php include "header.php"; ?>



    <!-- =====================================================
                       PAGE LAYOUT
    ====================================================== -->

    <div class="page_layout">



        <!-- =================================================
                            SIDEBAR
        ================================================== -->

        <aside class="sidebar">


            <div class="sidebar_heading">

                COORDINATOR PANEL

            </div>



            <nav class="sidebar_menu">


                <a
                    href="coordinator_dashboard.php"
                    class="sidebar_item"
                >

                    Dashboard

                </a>


                <a
                    href="review_applications.php"
                    class="sidebar_item active"
                >

                    Review Applications

                </a>


                <a
                    href="verify_documents.php"
                    class="sidebar_item"
                >

                    Verify Documents

                </a>


                <a
                    href="manage_nominations.php"
                    class="sidebar_item"
                >

                     Manage Nominations

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



        <!-- =================================================
                         MAIN CONTENT
        ================================================== -->

        <main class="main_content">



            <!-- =================================================
                         PAGE HEADER
            ================================================== -->

            <section class="page_header">


                <h1>

                    Review Applications

                </h1>


                <p>

                    View, search, filter and review student
                    exchange applications.

                </p>


            </section>



            <!-- ================= MESSAGES ================= -->

            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            >
            </p>


            <?php

            if ($error != "") {

                echo "

                <p
                    id='php_error'
                    style='
                        color:red;
                        text-align:center;
                    '
                >

                    $error

                </p>

                ";

            }


            if ($success != "") {

                echo "

                <p
                    id='success_message'
                    style='
                        color:green;
                        text-align:center;
                    '
                >

                    $success

                </p>

                ";

            }

            ?>



            <!-- =================================================
                      SEARCH & FILTER
            ================================================== -->

            <section class="content_box">


                <h2>

                    Search & Filter Applications

                </h2>


                <p class="section_description">

                    Search for a specific application or use the
                    filters to find applications.

                </p>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateSearch();"
                >


                    <div class="filter_row">



                        <!-- APPLICATION ID -->

                        <div class="filter_group">


                            <label for="application_id">

                                Application ID

                            </label>


                            <input
                                type="text"
                                id="application_id"
                                name="application_id"
                                placeholder="Enter Application ID"
                            >


                        </div>



                        <!-- STUDENT -->

                        <div class="filter_group">


                            <label for="student">

                                Student

                            </label>


                            <input
                                type="text"
                                id="student"
                                name="student"
                                placeholder="Student ID or Name"
                            >


                        </div>



                        <!-- PROGRAM -->

                        <div class="filter_group">


                            <label for="program">

                                Exchange Program

                            </label>


                            <select
                                id="program"
                                name="program"
                            >


                                <option value="">

                                    All Programs

                                </option>


                                <!-- Programs will come from database later -->


                            </select>


                        </div>



                        <!-- COUNTRY -->

                        <div class="filter_group">


                            <label for="country">

                                Country

                            </label>


                            <select
                                id="country"
                                name="country"
                            >


                                <option value="">

                                    All Countries

                                </option>


                                <!-- Countries will come from database later -->


                            </select>


                        </div>



                        <!-- STATUS -->

                        <div class="filter_group">


                            <label for="status">

                                Application Status

                            </label>


                            <select
                                id="status"
                                name="status"
                            >


                                <option value="">

                                    All Status

                                </option>


                                <option value="submitted">

                                    Submitted

                                </option>


                                <option value="under_review">

                                    Under Review

                                </option>


                                <option value="approved">

                                    Approved

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                            </select>


                        </div>


                    </div>



                    <div class="filter_buttons">


                        <button
                            type="submit"
                            class="search_button"
                        >

                            Search

                        </button>


                        <button
                            type="reset"
                            class="clear_button"
                        >

                            Clear

                        </button>


                    </div>


                </form>


            </section>



            <!-- =================================================
                       APPLICATION LIST
            ================================================== -->

            <section class="content_box">


                <div class="section_header">


                    <div>


                        <h2>

                            Applications

                        </h2>


                        <p>

                            Student applications retrieved from
                            the database will appear here.

                        </p>


                    </div>


                    <div class="application_count">

                        Total Applications:

                        <strong>

                            0

                        </strong>

                    </div>


                </div>



                <div class="table_container">


                    <table class="application_table">


                        <thead>


                            <tr>


                                <th>

                                    Application ID

                                </th>


                                <th>

                                    Student ID

                                </th>


                                <th>

                                    Student Name

                                </th>


                                <th>

                                    Program

                                </th>


                                <th>

                                    University

                                </th>


                                <th>

                                    Country

                                </th>


                                <th>

                                    Submitted Date

                                </th>


                                <th>

                                    Status

                                </th>


                                <th>

                                    Action

                                </th>


                            </tr>


                        </thead>



                        <tbody>


                            <tr>


                                <td
                                    colspan="9"
                                    class="empty_data"
                                >

                                    No applications available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- =================================================
                    SELECTED APPLICATION DETAILS
            ================================================== -->

            <section class="content_box">


                <div class="section_header">


                    <div>


                        <h2>

                            Application Details

                        </h2>


                        <p>

                            Select an application from the table
                            to view its complete information.

                        </p>


                    </div>


                </div>



                <!-- =================================================
                         APPLICATION INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Application Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Application ID

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Date

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Status

                            </span>


                            <span class="status_badge pending">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Preferred Term

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                         STUDENT INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Student Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Student ID

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Full Name

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Email

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Phone Number

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Department

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Current CGPA

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Current Semester

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                University / Institution

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                     EXCHANGE PROGRAM INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Exchange Program Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Program ID

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program Name

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Host Country

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Host University

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program Start Date

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program End Date

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Deadline

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Available Seats

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                       STATEMENT OF PURPOSE
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Statement of Purpose

                    </h3>


                    <div class="text_box">

                        No statement available.

                    </div>


                </div>



                <!-- =================================================
                         ADDITIONAL INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Additional Application Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Previous Exchange Experience

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Preferred Study Area

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Funding Type

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Scholarship Required

                            </span>


                            <span class="information_value">

                                —

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                       REVIEW INFORMATION
                ================================================== -->

                <div class="details_section review_notice">


                    <div class="notice_icon">

                        i

                    </div>


                    <div>


                        <strong>

                            Application Review

                        </strong>


                        <p>

                            This page is for reviewing application
                            information only. Document verification
                            and application approval or rejection
                            are handled separately through
                            <b>Verify Documents</b>.

                        </p>


                    </div>


                </div>


            </section>



            <!-- ================= FOOTER ================= -->

           <?php include "footer.php"; ?>

        </main>


    </div>



    <!-- ================= CLEAR URL ================= -->

    <script>


        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        /* Clear button */

        let clearButton =
            document.querySelector(".clear_button");


        clearButton.addEventListener("click", function () {


            let error =
                document.getElementById("js_error");


            let phpError =
                document.getElementById("php_error");


            let successMessage =
                document.getElementById("success_message");


            error.innerHTML = "";

            error.style.display = "none";


            if (phpError) {

                phpError.remove();

            }


            if (successMessage) {

                successMessage.remove();

            }

        });


    </script>


</body>

</html>