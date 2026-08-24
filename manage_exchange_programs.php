<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $program_id = trim($_POST["program_id"]);
    $program_name = trim($_POST["program_name"]);
    $country = $_POST["country"];
    $university = $_POST["university"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $deadline = $_POST["deadline"];
    $available_seats = trim($_POST["available_seats"]);
    $description = trim($_POST["description"]);


    /* Check empty fields */

    if (
        empty($program_id) ||
        empty($program_name) ||
        empty($country) ||
        empty($university) ||
        empty($start_date) ||
        empty($end_date) ||
        empty($deadline) ||
        empty($available_seats) ||
        empty($description)
    ) {

        header("Location: manage_exchange_programs.php?error=All fields are required.");
        exit();

    }


    /* Check Program ID */

    elseif (!is_numeric($program_id)) {

        header("Location: manage_exchange_programs.php?error=Program ID must contain numbers only.");
        exit();

    }


    /* Check Program Name */

    elseif (!preg_match("/^[A-Za-z0-9 ]+$/", $program_name)) {

        header("Location: manage_exchange_programs.php?error=Program name contains invalid characters.");
        exit();

    }


    /* Check Available Seats */

    elseif (!is_numeric($available_seats) || $available_seats < 1) {

        header("Location: manage_exchange_programs.php?error=Available seats must be at least 1.");
        exit();

    }


    /* Check Start and End Date */

    elseif ($end_date < $start_date) {

        header("Location: manage_exchange_programs.php?error=End date cannot be before start date.");
        exit();

    }


    /* Check Deadline */

    elseif ($deadline > $start_date) {

        header("Location: manage_exchange_programs.php?error=Application deadline should be before the program start date.");
        exit();

    }


    /* Successful validation */

    else {

        header("Location: manage_exchange_programs.php?success=Exchange program information is valid.");
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

    <title>SEPMS - Manage Exchange Programs</title>

    <link rel="stylesheet"
          href="manage_exchange_programs.css">

    <script src="manage_exchange_programs.js"></script>

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


            <!-- Page Header -->

            <div class="page_header">


                <h1>

                    Manage Exchange Programs

                </h1>


                <p>

                    Add, view, edit, delete and search exchange programs.

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



            <!-- ================= ADD PROGRAM ================= -->

            <section class="form_section">


                <h2>

                    Add Exchange Program

                </h2>


                <form
                    method="POST"
                    action=""
                    onsubmit="return validateProgram();"
                    autocomplete="off"
                >


                    <table>


                        <!-- Program ID -->

                        <tr>


                            <td>

                                <label for="program_id">

                                    Program ID

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="program_id"
                                    name="program_id"
                                    placeholder="Enter program ID"
                                >

                            </td>


                        </tr>



                        <!-- Program Name -->

                        <tr>


                            <td>

                                <label for="program_name">

                                    Program Name

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="program_name"
                                    name="program_name"
                                    placeholder="Enter program name"
                                >

                            </td>


                        </tr>



                        <!-- Country -->

                        <tr>


                            <td>

                                <label for="country">

                                    Country

                                </label>

                            </td>


                            <td>

                                <select
                                    id="country"
                                    name="country"
                                >


                                    <option value="">

                                        Select country

                                    </option>


                                    <!-- Countries will come from database later -->


                                </select>

                            </td>


                        </tr>



                        <!-- University -->

                        <tr>


                            <td>

                                <label for="university">

                                    University

                                </label>

                            </td>


                            <td>

                                <select
                                    id="university"
                                    name="university"
                                >


                                    <option value="">

                                        Select university

                                    </option>


                                    <!-- Universities will come from database later -->


                                </select>

                            </td>


                        </tr>



                        <!-- Start Date -->

                        <tr>


                            <td>

                                <label for="start_date">

                                    Start Date

                                </label>

                            </td>


                            <td>

                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                >

                            </td>


                        </tr>



                        <!-- End Date -->

                        <tr>


                            <td>

                                <label for="end_date">

                                    End Date

                                </label>

                            </td>


                            <td>

                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                >

                            </td>


                        </tr>



                        <!-- Application Deadline -->

                        <tr>


                            <td>

                                <label for="deadline">

                                    Application Deadline

                                </label>

                            </td>


                            <td>

                                <input
                                    type="date"
                                    id="deadline"
                                    name="deadline"
                                >

                            </td>


                        </tr>



                        <!-- Available Seats -->

                        <tr>


                            <td>

                                <label for="available_seats">

                                    Available Seats

                                </label>

                            </td>


                            <td>

                                <input
                                    type="number"
                                    id="available_seats"
                                    name="available_seats"
                                    placeholder="Enter available seats"
                                    min="1"
                                >

                            </td>


                        </tr>



                        <!-- Description -->

                        <tr>


                            <td>

                                <label for="description">

                                    Description

                                </label>

                            </td>


                            <td>

                                <textarea
                                    id="description"
                                    name="description"
                                    placeholder="Enter program description"
                                ></textarea>

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



            <!-- ================= PROGRAM LIST ================= -->

            <section class="list_section">


                <div class="list_header">


                    <div>


                        <h2>

                            Exchange Program List

                        </h2>


                        <p>

                            View and manage available exchange programs.

                        </p>


                    </div>



                    <!-- Search -->

                    <div class="search_area">


                        <input
                            type="text"
                            id="search_program"
                            placeholder="Search program"
                        >


                        <button
                            type="button"
                            onclick="searchProgram()"
                        >

                            Search

                        </button>


                    </div>


                </div>



                <!-- Program Table -->

                <div class="table_container">


                    <table class="program_table">


                        <thead>


                            <tr>


                                <th>

                                    Program ID

                                </th>


                                <th>

                                    Program Name

                                </th>


                                <th>

                                    Country

                                </th>


                                <th>

                                    University

                                </th>


                                <th>

                                    Start Date

                                </th>


                                <th>

                                    End Date

                                </th>


                                <th>

                                    Deadline

                                </th>


                                <th>

                                    Seats

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

                                    No exchange programs available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


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