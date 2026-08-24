<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form_type = $_POST["form_type"];


    /* =================================================
       CREATE NOMINATION
    ================================================= */

    if ($form_type == "create") {

        $application_id = trim($_POST["application_id"]);
        $student_id = trim($_POST["student_id"]);
        $student_name = trim($_POST["student_name"]);
        $program = $_POST["program"];
        $university = $_POST["university"];
        $country = $_POST["country"];
        $nomination_date = $_POST["nomination_date"];
        $nomination_status = $_POST["nomination_status"];
        $nomination_remark = trim($_POST["nomination_remark"]);


        if (
            empty($application_id) ||
            empty($student_id) ||
            empty($student_name) ||
            empty($program) ||
            empty($university) ||
            empty($country) ||
            empty($nomination_date) ||
            empty($nomination_status) ||
            empty($nomination_remark)
        ) {

            header(
                "Location: manage_nominations.php?error=All nomination fields are required."
            );

            exit();

        }


        elseif (!is_numeric($application_id)) {

            header(
                "Location: manage_nominations.php?error=Application ID must contain numbers only."
            );

            exit();

        }


        elseif (!is_numeric($student_id)) {

            header(
                "Location: manage_nominations.php?error=Student ID must contain numbers only."
            );

            exit();

        }


        elseif (!preg_match("/^[A-Za-z ]+$/", $student_name)) {

            header(
                "Location: manage_nominations.php?error=Student name must contain letters only."
            );

            exit();

        }


        else {

            header(
                "Location: manage_nominations.php?success=Nomination information is valid."
            );

            exit();

        }

    }



    /* =================================================
       UPDATE NOMINATION
    ================================================= */

    elseif ($form_type == "update") {

        $nomination_id = trim($_POST["update_nomination_id"]);
        $application_id = trim($_POST["update_application_id"]);
        $student_id = trim($_POST["update_student_id"]);
        $program = $_POST["update_program"];
        $university = $_POST["update_university"];
        $country = $_POST["update_country"];
        $update_date = $_POST["update_date"];
        $update_status = $_POST["update_status"];
        $update_remark = trim($_POST["update_remark"]);


        if (
            empty($nomination_id) ||
            empty($application_id) ||
            empty($student_id) ||
            empty($program) ||
            empty($university) ||
            empty($country) ||
            empty($update_date) ||
            empty($update_status) ||
            empty($update_remark)
        ) {

            header(
                "Location: manage_nominations.php?error=All update fields are required."
            );

            exit();

        }


        elseif (
            !is_numeric($nomination_id) ||
            !is_numeric($application_id) ||
            !is_numeric($student_id)
        ) {

            header(
                "Location: manage_nominations.php?error=IDs must contain numbers only."
            );

            exit();

        }


        else {

            header(
                "Location: manage_nominations.php?success=Nomination update information is valid."
            );

            exit();

        }

    }



    /* =================================================
       UPDATE STATUS
    ================================================= */

    elseif ($form_type == "status") {

        $status_nomination_id =
            trim($_POST["status_nomination_id"]);

        $current_status =
            $_POST["current_status"];

        $new_status =
            $_POST["new_status"];

        $status_remark =
            trim($_POST["status_remark"]);


        if (
            empty($status_nomination_id) ||
            empty($current_status) ||
            empty($new_status) ||
            empty($status_remark)
        ) {

            header(
                "Location: manage_nominations.php?error=All status fields are required."
            );

            exit();

        }


        elseif (!is_numeric($status_nomination_id)) {

            header(
                "Location: manage_nominations.php?error=Nomination ID must contain numbers only."
            );

            exit();

        }


        elseif ($current_status == $new_status) {

            header(
                "Location: manage_nominations.php?error=New status must be different from current status."
            );

            exit();

        }


        else {

            header(
                "Location: manage_nominations.php?success=Nomination status information is valid."
            );

            exit();

        }

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

    <title>SEPMS - Manage Nominations</title>

    <link rel="stylesheet"
          href="manage_nominations.css">

    <script src="manage_nominations.js"></script>

</head>


<body>


    <!-- ================= HEADER ================= -->

    <?php include "header.php"; ?>


    <!-- ================= PAGE LAYOUT ================= -->

    <div class="page_layout">


        <!-- ================= SIDEBAR ================= -->

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
                    class="sidebar_item"
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
                    class="sidebar_item active"
                >

                    Manage Nominations

                </a>


            </nav>



            <!-- ================= ACCOUNT ================= -->

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

                    Manage Nominations

                </h1>


                <p>

                    Create, view, update and manage student
                    nomination status for exchange programs.

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



            <!-- ================= ACTION CARDS ================= -->

            <section class="action_cards">


                <div class="action_card">


                    <div class="action_icon">

                        +

                    </div>


                    <div class="action_content">


                        <h3>

                            Create Nomination

                        </h3>


                        <p>

                            Create a new nomination for an eligible
                            student.

                        </p>


                        <button
                            type="button"
                            class="primary_button"
                            onclick="showSection('create')"
                        >

                            Create Nomination

                        </button>


                    </div>


                </div>



                <div class="action_card">


                    <div class="action_icon">

                        ≡

                    </div>


                    <div class="action_content">


                        <h3>

                            View Nominations

                        </h3>


                        <p>

                            View all nominations created for
                            exchange students.

                        </p>


                        <button
                            type="button"
                            class="secondary_button"
                            onclick="showSection('view')"
                        >

                            View Nominations

                        </button>


                    </div>


                </div>


            </section>



            <!-- =================================================
                 CREATE NOMINATION
            ================================================== -->

            <section
                id="create"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Create Nomination

                        </h2>


                        <p>

                            Enter the required information to
                            nominate an eligible student.

                        </p>


                    </div>


                </div>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateCreateNomination();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="create"
                    >


                    <div class="form_grid">


                        <!-- Application ID -->

                        <div class="form_group">


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



                        <!-- Student ID -->

                        <div class="form_group">


                            <label for="student_id">

                                Student ID

                            </label>


                            <input
                                type="text"
                                id="student_id"
                                name="student_id"
                                placeholder="Enter Student ID"
                            >


                        </div>



                        <!-- Student Name -->

                        <div class="form_group">


                            <label for="student_name">

                                Student Name

                            </label>


                            <input
                                type="text"
                                id="student_name"
                                name="student_name"
                                placeholder="Student Name"
                            >


                        </div>



                        <!-- Program -->

                        <div class="form_group">


                            <label for="program">

                                Exchange Program

                            </label>


                            <select
                                id="program"
                                name="program"
                            >


                                <option value="">

                                    Select Program

                                </option>


                            </select>


                        </div>



                        <!-- University -->

                        <div class="form_group">


                            <label for="university">

                                Host University

                            </label>


                            <select
                                id="university"
                                name="university"
                            >


                                <option value="">

                                    Select University

                                </option>


                            </select>


                        </div>



                        <!-- Country -->

                        <div class="form_group">


                            <label for="country">

                                Host Country

                            </label>


                            <select
                                id="country"
                                name="country"
                            >


                                <option value="">

                                    Select Country

                                </option>


                            </select>


                        </div>



                        <!-- Nomination Date -->

                        <div class="form_group">


                            <label for="nomination_date">

                                Nomination Date

                            </label>


                            <input
                                type="date"
                                id="nomination_date"
                                name="nomination_date"
                            >


                        </div>



                        <!-- Status -->

                        <div class="form_group">


                            <label for="nomination_status">

                                Nomination Status

                            </label>


                            <select
                                id="nomination_status"
                                name="nomination_status"
                            >


                                <option value="">

                                    Select Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>



                    <!-- Remark -->

                    <div class="form_group full_width">


                        <label for="nomination_remark">

                            Nomination Remark

                        </label>


                        <textarea
                            id="nomination_remark"
                            name="nomination_remark"
                            rows="5"
                            placeholder="Enter nomination remarks..."
                        ></textarea>


                    </div>



                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                        >

                            Create Nomination

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
                 VIEW NOMINATIONS
            ================================================== -->

            <section
                id="view"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            View Nominations

                        </h2>


                        <p>

                            Nominations retrieved from the database
                            will appear here.

                        </p>


                    </div>


                    <div class="nomination_count">

                        Total:

                        <strong>

                            0

                        </strong>

                    </div>


                </div>



                <!-- Search -->

                <div class="search_area">


                    <div class="search_group">


                        <label for="search_nomination">

                            Search Nomination

                        </label>


                        <input
                            type="text"
                            id="search_nomination"
                            placeholder="Nomination ID, Application ID, Student ID or Name"
                        >


                    </div>



                    <div class="search_group">


                        <label for="filter_status">

                            Status

                        </label>


                        <select id="filter_status">


                            <option value="">

                                All Status

                            </option>


                            <option value="pending">

                                Pending

                            </option>


                            <option value="nominated">

                                Nominated

                            </option>


                            <option value="accepted">

                                Accepted

                            </option>


                            <option value="rejected">

                                Rejected

                            </option>


                            <option value="withdrawn">

                                Withdrawn

                            </option>


                        </select>


                    </div>



                    <button
                        type="button"
                        class="search_button"
                        onclick="searchNomination()"
                    >

                        Search

                    </button>


                </div>



                <!-- Table -->

                <div class="table_container">


                    <table class="nomination_table">


                        <thead>


                            <tr>


                                <th>

                                    Nomination ID

                                </th>


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

                                    Nomination Date

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

                                    No nominations available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- =================================================
                 UPDATE NOMINATION
            ================================================== -->

            <section
                id="update"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Update Nomination

                        </h2>


                        <p>

                            Update information for an existing
                            nomination.

                        </p>


                    </div>


                </div>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateUpdateNomination();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="update"
                    >


                    <div class="form_grid">


                        <!-- Nomination ID -->

                        <div class="form_group">


                            <label for="update_nomination_id">

                                Nomination ID

                            </label>


                            <input
                                type="text"
                                id="update_nomination_id"
                                name="update_nomination_id"
                                placeholder="Enter Nomination ID"
                            >


                        </div>



                        <!-- Application ID -->

                        <div class="form_group">


                            <label for="update_application_id">

                                Application ID

                            </label>


                            <input
                                type="text"
                                id="update_application_id"
                                name="update_application_id"
                                placeholder="Application ID"
                            >


                        </div>



                        <!-- Student ID -->

                        <div class="form_group">


                            <label for="update_student_id">

                                Student ID

                            </label>


                            <input
                                type="text"
                                id="update_student_id"
                                name="update_student_id"
                                placeholder="Student ID"
                            >


                        </div>



                        <!-- Program -->

                        <div class="form_group">


                            <label for="update_program">

                                Exchange Program

                            </label>


                            <select
                                id="update_program"
                                name="update_program"
                            >


                                <option value="">

                                    Select Program

                                </option>


                            </select>


                        </div>



                        <!-- University -->

                        <div class="form_group">


                            <label for="update_university">

                                Host University

                            </label>


                            <select
                                id="update_university"
                                name="update_university"
                            >


                                <option value="">

                                    Select University

                                </option>


                            </select>


                        </div>



                        <!-- Country -->

                        <div class="form_group">


                            <label for="update_country">

                                Host Country

                            </label>


                            <select
                                id="update_country"
                                name="update_country"
                            >


                                <option value="">

                                    Select Country

                                </option>


                            </select>


                        </div>



                        <!-- Date -->

                        <div class="form_group">


                            <label for="update_date">

                                Nomination Date

                            </label>


                            <input
                                type="date"
                                id="update_date"
                                name="update_date"
                            >


                        </div>



                        <!-- Status -->

                        <div class="form_group">


                            <label for="update_status">

                                Nomination Status

                            </label>


                            <select
                                id="update_status"
                                name="update_status"
                            >


                                <option value="">

                                    Select Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>



                    <!-- Remark -->

                    <div class="form_group full_width">


                        <label for="update_remark">

                            Nomination Remark

                        </label>


                        <textarea
                            id="update_remark"
                            name="update_remark"
                            rows="5"
                            placeholder="Update nomination remarks..."
                        ></textarea>


                    </div>



                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                        >

                            Update Nomination

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
                 UPDATE NOMINATION STATUS
            ================================================== -->

            <section
                id="status"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Update Nomination Status

                        </h2>


                        <p>

                            Change the current status of a
                            nomination.

                        </p>


                    </div>


                </div>



                <form
                    class="status_form"
                    method="POST"
                    action=""
                    onsubmit="return validateStatus();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="status"
                    >


                    <div class="status_form_grid">


                        <!-- Nomination ID -->

                        <div class="form_group">


                            <label for="status_nomination_id">

                                Nomination ID

                            </label>


                            <input
                                type="text"
                                id="status_nomination_id"
                                name="status_nomination_id"
                                placeholder="Enter Nomination ID"
                            >


                        </div>



                        <!-- Current Status -->

                        <div class="form_group">


                            <label for="current_status">

                                Current Status

                            </label>


                            <select
                                id="current_status"
                                name="current_status"
                            >


                                <option value="">

                                    Current Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>



                        <!-- New Status -->

                        <div class="form_group">


                            <label for="new_status">

                                New Status

                            </label>


                            <select
                                id="new_status"
                                name="new_status"
                            >


                                <option value="">

                                    Select New Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>



                    <!-- Remark -->

                    <div class="form_group full_width">


                        <label for="status_remark">

                            Status Remark

                        </label>


                        <textarea
                            id="status_remark"
                            name="status_remark"
                            rows="4"
                            placeholder="Enter reason for status change..."
                        ></textarea>


                    </div>



                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                        >

                            Update Status

                        </button>


                    </div>


                </form>


            </section>



            <!-- ================= FOOTER ================= -->

           <?php include "footer.php"; ?>


        </main>


    </div>



    <!-- ================= BASIC NAVIGATION ================= -->

    <script>

        function showSection(section) {


            let createSection =
                document.getElementById("create");


            let viewSection =
                document.getElementById("view");


            if (section == "create") {

                createSection.scrollIntoView();

            }


            if (section == "view") {

                viewSection.scrollIntoView();

            }

        }


        /* Remove error/success from URL */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }

    </script>


</body>

</html>