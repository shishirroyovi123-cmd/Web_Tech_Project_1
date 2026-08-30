<?php

/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE
========================================================= */

require_once __DIR__ . "/config/db.php";

require_once __DIR__ . "/models/ExchangeProgram.php";


/* =========================================================
   CHECK LOGIN
========================================================= */

if (!isset($_SESSION["user_id"])) {

    header(
        "Location: login.php?error=" .
        urlencode("Please login first.")
    );

    exit();

}


/* =========================================================
   CHECK STUDENT ROLE
========================================================= */

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "student"
) {

    header(
        "Location: login.php?error=" .
        urlencode("Only students can apply for exchange programs.")
    );

    exit();

}


/* =========================================================
   GET LOGGED-IN USER ID
========================================================= */

$userID = (int) $_SESSION["user_id"];


/* =========================================================
   GET PROGRAM ID
========================================================= */

$programID = (int) ($_GET["program_id"] ?? 0);


/* =========================================================
   CHECK PROGRAM ID
========================================================= */

if ($programID <= 0) {

    header(
        "Location: search_apply.php?error=" .
        urlencode("Please select an exchange program first.")
    );

    exit();

}


/* =========================================================
   LOAD STUDENT INFORMATION
========================================================= */

$studentSQL = "
    SELECT
        user_id,
        name,
        email,
        username,
        role
    FROM users
    WHERE user_id = ?
    AND role = 'student'
    LIMIT 1
";


$studentStmt = mysqli_prepare(
    $conn,
    $studentSQL
);


if (!$studentStmt) {

    die(
        "Database error: " .
        mysqli_error($conn)
    );

}


mysqli_stmt_bind_param(
    $studentStmt,
    "i",
    $userID
);


mysqli_stmt_execute(
    $studentStmt
);


$studentResult = mysqli_stmt_get_result(
    $studentStmt
);


if (
    !$studentResult ||
    mysqli_num_rows($studentResult) === 0
) {

    mysqli_stmt_close(
        $studentStmt
    );

    header(
        "Location: login.php?error=" .
        urlencode("Student account was not found.")
    );

    exit();

}


$student = mysqli_fetch_assoc(
    $studentResult
);


mysqli_stmt_close(
    $studentStmt
);


/* =========================================================
   LOAD EXCHANGE PROGRAM

   Uses your existing ExchangeProgram model.
========================================================= */

$programModel =
    new ExchangeProgram($conn);


$allPrograms =
    $programModel->getAll();


$program = null;


/* =========================================================
   FIND SELECTED PROGRAM
========================================================= */

if ($allPrograms) {

    while (
        $row =
        mysqli_fetch_assoc($allPrograms)
    ) {

        if (
            (int) $row["program_id"] === $programID
        ) {

            $program = $row;

            break;

        }

    }

}


/* =========================================================
   CHECK PROGRAM EXISTS
========================================================= */

if ($program === null) {

    header(
        "Location: search_apply.php?error=" .
        urlencode("Selected exchange program was not found.")
    );

    exit();

}


/* =========================================================
   ERROR MESSAGE
========================================================= */

$error = "";


if (isset($_GET["error"])) {

    $error =
        $_GET["error"];

}


/* =========================================================
   SUCCESS MESSAGE
========================================================= */

$success = "";


if (isset($_GET["success"])) {

    $success =
        $_GET["success"];

}

?>


<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        SEPMS - Application Form
    </title>


    <link
        rel="stylesheet"
        href="application_form.css"
    >


    <script
        src="application_form.js"
        defer
    ></script>


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
        class="sidebar_item active" 
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
                    class="sidebar_item"
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
                    href="logout.php"
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


            <div class="application_container">


                <div class="application_box">


                    <!-- =====================================
                         PAGE HEADER
                    ====================================== -->

                    <div class="page_header">


                        <h1>

                            Exchange Program Application

                        </h1>


                        <p>

                            Complete the application form below.

                        </p>


                    </div>


                    <!-- =====================================
                         ERROR MESSAGE
                    ====================================== -->

                    <?php if ($error !== "") { ?>


                        <p
                            class="error_message"
                        >

                            <?php
                            echo htmlspecialchars($error);
                            ?>

                        </p>


                    <?php } ?>


                    <!-- =====================================
                         SUCCESS MESSAGE
                    ====================================== -->

                    <?php if ($success !== "") { ?>


                        <p
                            class="success_message"
                        >

                            <?php
                            echo htmlspecialchars($success);
                            ?>

                        </p>


                    <?php } ?>


                    <!-- =====================================
                         FORM
                    ====================================== -->

                    <form
                        method="POST"
                        action="controllers/ApplicationController.php"
                        enctype="multipart/form-data"
                        id="applicationForm"
                    >


                        <!-- =================================
                             HIDDEN PROGRAM ID
                        ================================== -->

                        <input
                            type="hidden"
                            name="program_id"
                            value="<?php
                                echo (int)
                                    $program["program_id"];
                            ?>"
                        >


                        <!-- =================================
                             PROGRAM INFORMATION
                        ================================== -->

                        <section class="form_section">


                            <h2>

                                Selected Exchange Program

                            </h2>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Program ID

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["program_id"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        Program Name

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["program_name"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Country

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["country_name"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        University

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["university_name"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Start Date

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["start_date"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        End Date

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["end_date"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Application Deadline

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["deadline"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        Available Seats

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $program["available_seats"] ?? ""
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                        </section>


                        <!-- =================================
                             STUDENT INFORMATION
                        ================================== -->

                        <section class="form_section">


                            <h2>

                                Student Information

                            </h2>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Student Name

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $student["name"]
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        Student ID

                                    </label>


                                    <input
                                        type="text"
                                        name="student_id"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $student["user_id"]
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                            <div class="form_row">


                                <div class="form_group">


                                    <label>

                                        Email

                                    </label>


                                    <input
                                        type="email"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $student["email"]
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                                <div class="form_group">


                                    <label>

                                        Username

                                    </label>


                                    <input
                                        type="text"
                                        value="<?php
                                            echo htmlspecialchars(
                                                $student["username"]
                                            );
                                        ?>"
                                        readonly
                                    >


                                </div>


                            </div>


                        </section>


                        <!-- =================================
                             ACADEMIC INFORMATION
                        ================================== -->

                        <section class="form_section">


                            <h2>

                                Academic Information

                            </h2>


                            <div class="form_row">


                                <div class="form_group">


                                    <label for="department">

                                        Department

                                    </label>


                                    <input
                                        type="text"
                                        id="department"
                                        name="department"
                                        placeholder="Enter your department"
                                        required
                                    >


                                </div>


                                <div class="form_group">


                                    <label for="semester">

                                        Current Semester

                                    </label>


                                    <select
                                        id="semester"
                                        name="semester"
                                        required
                                    >


                                        <option value="">

                                            Select Semester

                                        </option>


                                        <?php for ($i = 1; $i <= 12; $i++) { ?>


                                            <option
                                                value="<?php echo $i; ?>"
                                            >

                                                Semester
                                                <?php echo $i; ?>

                                            </option>


                                        <?php } ?>


                                    </select>


                                </div>


                            </div>


                            <div class="form_row">


                                <div class="form_group">


                                    <label for="cgpa">

                                        CGPA

                                    </label>


                                    <input
                                        type="number"
                                        id="cgpa"
                                        name="cgpa"
                                        min="0"
                                        max="4"
                                        step="0.01"
                                        placeholder="Example: 3.50"
                                        required
                                    >


                                </div>


                                <div class="form_group">


                                    <label for="preferred_term">

                                        Preferred Term

                                    </label>


                                    <select
                                        id="preferred_term"
                                        name="preferred_term"
                                        required
                                    >


                                        <option value="">

                                            Select Term

                                        </option>


                                        <option value="Spring">

                                            Spring

                                        </option>


                                        <option value="Summer">

                                            Summer

                                        </option>


                                        <option value="Fall">

                                            Fall

                                        </option>


                                    </select>


                                </div>


                            </div>


                        </section>


                        <!-- =================================
                             STATEMENT
                        ================================== -->

                        <section class="form_section">


                            <h2>

                                Statement of Purpose

                            </h2>


                            <div class="form_group">


                                <label for="statement">

                                    Why do you want to join this
                                    exchange program?

                                </label>


                                <textarea
                                    id="statement"
                                    name="statement"
                                    rows="8"
                                    placeholder="Write your statement here..."
                                    required
                                ></textarea>


                            </div>


                        </section>


                        <!-- =================================
                             DOCUMENTS
                        ================================== -->

                        <section class="form_section">


                            <h2>

                                Required Documents

                            </h2>


                            <div class="form_group">


                                <label for="transcript">

                                    Academic Transcript

                                </label>


                                <input
                                    type="file"
                                    id="transcript"
                                    name="transcript"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    required
                                >


                            </div>


                            <div class="form_group">


                                <label for="cv">

                                    CV / Resume

                                </label>


                                <input
                                    type="file"
                                    id="cv"
                                    name="cv"
                                    accept=".pdf,.doc,.docx"
                                    required
                                >


                            </div>


                        </section>


                        <!-- =================================
                             DECLARATION
                        ================================== -->

                        <div class="declaration">


                            <input
                                type="checkbox"
                                id="declaration"
                                name="declaration"
                                value="1"
                                required
                            >


                            <label for="declaration">

                                I confirm that all information
                                provided in this application is
                                correct.

                            </label>


                        </div>


                        <!-- =================================
                             BUTTONS
                        ================================== -->

                        <div class="button_area">


                            <button
                                type="submit"
                                class="submit_btn"
                            >

                                Submit Application

                            </button>


                            <button
                                type="reset"
                                class="reset_btn"
                            >

                                Reset

                            </button>


                            <a
                                href="search_apply.php"
                                class="back_btn"
                            >

                                Back to Search

                            </a>


                        </div>


                    </form>


                </div>


            </div>


        </main>


    </div>


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <?php include "footer.php"; ?>


</body>


</html>