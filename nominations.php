<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nomination_id = trim($_POST["nomination_id"]);
    $nomination_date = $_POST["nomination_date"];
    $application_id = $_POST["application_id"];
    $university_id = $_POST["university_id"];
    $status = $_POST["status"];


    /* Check empty fields */

    if (
        empty($nomination_id) ||
        empty($nomination_date) ||
        empty($application_id) ||
        empty($university_id) ||
        empty($status)
    ) {

        header(
            "Location: nominations.php?error=All fields are required."
        );

        exit();

    }


    /* Check Nomination ID */

    elseif (!is_numeric($nomination_id)) {

        header(
            "Location: nominations.php?error=Nomination ID must contain numbers only."
        );

        exit();

    }


    /* Successful validation */

    else {

        header(
            "Location: nominations.php?success=Nomination information is valid."
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

    <title>SEPMS - Nominations</title>

    <link rel="stylesheet"
          href="nominations.css">

    <script src="nominations.js"></script>

</head>


<body>


    <!-- ================= HEADER ================= -->

    <?php include "header.php"; ?>



    <!-- ================= MAIN LAYOUT ================= -->

    <div class="dashboard_container">



        <!-- ================= SIDEBAR ================= -->

        <aside class="sidebar">


            <div class="sidebar_title">

                ADMIN PANEL

            </div>



            <a href="admin_dashboard.php">

                Dashboard

            </a>


            <a href="manage_students.php">

                Students

            </a>


            <a href="manage_coordinators.php">

                Coordinators

            </a>


            <a href="manage_countries.php">

                Countries

            </a>


            <a href="manage_universities.php">

                Universities

            </a>


            <a href="manage_exchange_programs.php">

                Exchange Programs

            </a>


            <a href="applications.php">

                Applications

            </a>


            <a href="documents.php">

                Documents

            </a>


            <a href="scholarships.php">

                Scholarships

            </a>


            <a href="nominations.php">

                Nominations

            </a>


            <a href="exchange_records.php">

                Exchange Records

            </a>



            <!-- ================= BOTTOM ================= -->

            <div class="sidebar_bottom">


                <a href="update_profile.php">

                    Profile

                </a>


                <a href="change_password.php">

                    Change Password

                </a>


                <a href="login.php">

                    Logout

                </a>


            </div>


        </aside>



        <!-- ================= MAIN CONTENT ================= -->

        <main class="main_content">



            <!-- ================= PAGE HEADER ================= -->

            <div class="page_header">


                <h1>

                    Nominations

                </h1>


                <p>

                    Manage student nominations for exchange programs.

                </p>


            </div>



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



            <!-- ================= STATISTICS ================= -->

            <section class="statistics">


                <div class="stat_card">


                    <div class="stat_title">

                        Total Nominations

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Pending

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Approved

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Rejected

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>


            </section>



            <!-- ================= CREATE NOMINATION ================= -->

            <section class="form_section">


                <h2>

                    Create Nomination

                </h2>


                <p class="section_description">

                    Create a nomination for an approved exchange application.

                </p>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateNomination();"
                    autocomplete="off"
                >


                    <table>


                        <!-- Nomination ID -->

                        <tr>


                            <td>

                                <label for="nomination_id">

                                    Nomination ID

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="nomination_id"
                                    name="nomination_id"
                                    placeholder="Enter nomination ID"
                                >

                            </td>


                        </tr>



                        <!-- Nomination Date -->

                        <tr>


                            <td>

                                <label for="nomination_date">

                                    Nomination Date

                                </label>

                            </td>


                            <td>

                                <input
                                    type="date"
                                    id="nomination_date"
                                    name="nomination_date"
                                >

                            </td>


                        </tr>



                        <!-- Application -->

                        <tr>


                            <td>

                                <label for="application_id">

                                    Application ID

                                </label>

                            </td>


                            <td>

                                <select
                                    id="application_id"
                                    name="application_id"
                                >


                                    <option
                                        value=""
                                        selected
                                        disabled
                                    >

                                        Select approved application

                                    </option>


                                    <!-- Applications will come from database later -->


                                </select>

                            </td>


                        </tr>



                        <!-- Destination University -->

                        <tr>


                            <td>

                                <label for="university_id">

                                    Destination University

                                </label>

                            </td>


                            <td>

                                <select
                                    id="university_id"
                                    name="university_id"
                                >


                                    <option
                                        value=""
                                        selected
                                        disabled
                                    >

                                        Select destination university

                                    </option>


                                    <!-- Universities will come from database later -->


                                </select>

                            </td>


                        </tr>



                        <!-- Status -->

                        <tr>


                            <td>

                                <label for="status">

                                    Status

                                </label>

                            </td>


                            <td>

                                <select
                                    id="status"
                                    name="status"
                                >


                                    <option
                                        value=""
                                        selected
                                        disabled
                                    >

                                        Select status

                                    </option>


                                    <option value="Pending">

                                        Pending

                                    </option>


                                    <option value="Approved">

                                        Approved

                                    </option>


                                    <option value="Rejected">

                                        Rejected

                                    </option>


                                </select>

                            </td>


                        </tr>



                        <!-- Buttons -->

                        <tr>


                            <td colspan="2">


                                <div class="button_area">


                                    <button
                                        type="submit"
                                        class="save_btn"
                                    >

                                        Save

                                    </button>


                                    <button
                                        type="reset"
                                        class="reset_btn"
                                    >

                                        Reset

                                    </button>


                                </div>


                            </td>


                        </tr>


                    </table>


                </form>


            </section>



            <!-- ================= NOMINATION LIST ================= -->

            <section class="list_section">


                <div class="list_header">


                    <div>


                        <h2>

                            Nomination List

                        </h2>


                        <p>

                            View and manage nomination records
                            from the database.

                        </p>


                    </div>



                    <!-- Search -->

                    <div class="search_area">


                        <input
                            type="text"
                            id="search_nomination"
                            placeholder="Search nomination"
                        >


                        <button
                            type="button"
                            onclick="searchNomination()"
                        >

                            Search

                        </button>


                    </div>


                </div>



                <!-- ================= FILTER ================= -->

                <div class="filter_area">


                    <label for="status_filter">

                        Filter by Status:

                    </label>


                    <select id="status_filter">


                        <option value="">

                            All

                        </option>


                        <option value="Pending">

                            Pending

                        </option>


                        <option value="Approved">

                            Approved

                        </option>


                        <option value="Rejected">

                            Rejected

                        </option>


                    </select>


                </div>



                <!-- ================= TABLE ================= -->

                <div class="table_container">


                    <table class="nomination_table">


                        <thead>


                            <tr>


                                <th>

                                    Nomination ID

                                </th>


                                <th>

                                    Nomination Date

                                </th>


                                <th>

                                    Application ID

                                </th>


                                <th>

                                    Student

                                </th>


                                <th>

                                    Destination University

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
                                    colspan="7"
                                    class="empty_data"
                                >

                                    No nominations available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- ================= NOMINATION DETAILS ================= -->

            <section class="details_section">


                <h2>

                    Nomination Details

                </h2>


                <p class="details_instruction">

                    Select a nomination from the list to view
                    complete information.

                </p>



                <table class="details_table">


                    <tr>


                        <td>

                            Nomination ID

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Nomination Date

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Application ID

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Student

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Destination University

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Status

                        </td>


                        <td>

                            -

                        </td>


                    </tr>


                </table>


            </section>


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


        /* Clear messages when Reset is clicked */

        let resetButton =
            document.querySelector(".reset_btn");


        resetButton.addEventListener("click", function () {


            let jsError =
                document.getElementById("js_error");


            let phpError =
                document.getElementById("php_error");


            let successMessage =
                document.getElementById("success_message");


            if (jsError) {

                jsError.innerHTML = "";

                jsError.style.display = "none";

            }


            if (phpError) {

                phpError.remove();

            }


            if (successMessage) {

                successMessage.remove();

            }


        });


    </script>
   
    <?php include "footer.php"; ?>


</body>

</html>