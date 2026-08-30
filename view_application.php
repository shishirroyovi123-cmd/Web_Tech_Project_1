<?php


/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE + MODEL
========================================================= */

require_once __DIR__ . "/config/db.php";

require_once __DIR__ . "/models/ApplicationModel.php";


/* =========================================================
   CHECK LOGIN
========================================================= */

if (
    !isset($_SESSION["user_id"])
) {

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
    strtolower($_SESSION["role"]) !== "student"
) {

    header(
        "Location: login.php?error=" .
        urlencode("Only students can access this page.")
    );

    exit();

}


/* =========================================================
   GET STUDENT ID
========================================================= */

$studentID =
    (int) $_SESSION["user_id"];


/* =========================================================
   CREATE MODEL
========================================================= */

$applicationModel =
    new ApplicationModel($conn);


/* =========================================================
   GET APPLICATION ID
========================================================= */

$applicationID =
    (int) ($_GET["application_id"] ?? 0);


/* =========================================================
   VALIDATE APPLICATION ID
========================================================= */

if ($applicationID <= 0) {

    header(
        "Location: my_applications.php?error=" .
        urlencode("Invalid application ID.")
    );

    exit();

}


/* =========================================================
   GET APPLICATION
========================================================= */

$application =
    $applicationModel->getApplicationById(
        $applicationID,
        $studentID
    );


/* =========================================================
   CHECK APPLICATION
========================================================= */

if (!$application) {

    header(
        "Location: my_applications.php?error=" .
        urlencode(
            "Application not found or you do not have permission to view it."
        )
    );

    exit();

}


/* =========================================================
   GET DOCUMENTS
========================================================= */

$documents =
    $applicationModel->getDocumentsByApplicationID(
        $applicationID
    );


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

        SEPMS - View Application

    </title>


    <!-- =============================================
         SAME CSS AS SEARCH & APPLY
    ============================================== -->

    <link
        rel="stylesheet"
        href="search_apply.css"
    >


</head>


<body>


    <!-- =================================================
         HEADER
    ================================================= -->

    <?php include "header.php"; ?>


    <!-- =================================================
         PAGE LAYOUT
    ================================================= -->

    <div class="page_layout">


        <!-- =================================================
             SIDEBAR
             SAME AS MY_APPLICATIONS.PHP
        ================================================= -->

        <aside class="sidebar">


            <!-- =============================================
                 SIDEBAR HEADING
            ============================================== -->

            <div class="sidebar_heading">

                STUDENT PANEL

            </div>


            <!-- =============================================
                 SIDEBAR MENU
            ============================================== -->

            <nav class="sidebar_menu">


                <!-- DASHBOARD -->

                <a
                    href="student_dashboard.php"
                    class="sidebar_item"
                >

                    Dashboard

                </a>


                <!-- SEARCH & APPLY -->

                <a
                    href="search_apply.php"
                    class="sidebar_item"
                >

                    Search & Apply

                </a>


                <!-- APPLY PROGRAM -->

                <a
                    href="application_form.php"
                    class="sidebar_item"
                >

                    Apply Program

                </a>


                <!-- MY APPLICATIONS -->

                <a
                    href="my_applications.php"
                    class="sidebar_item active"
                >

                    My Applications

                </a>


                <!-- APPLICATION STATUS -->

                <a
                    href="application_status.php"
                    class="sidebar_item"
                >

                    Application Status

                </a>


            </nav>


            <!-- =============================================
                 SIDEBAR BOTTOM
            ============================================== -->

            <div class="sidebar_bottom">


                <!-- UPDATE PROFILE -->

                <a
                    href="update_profile.php"
                    class="sidebar_item"
                >

                    Update Profile

                </a>


                <!-- CHANGE PASSWORD -->

                <a
                    href="change_password.php"
                    class="sidebar_item"
                >

                    Change Password

                </a>


                <!-- LOGOUT -->

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
        ================================================= -->

        <main class="main_content">


            <!-- =================================================
                 PAGE HEADER
            ================================================= -->

            <section class="page_header">


                <h1>

                    View Application

                </h1>


                <p>

                    View complete details of your exchange program application.

                </p>


            </section>


            <!-- =================================================
                 APPLICATION INFORMATION
            ================================================= -->

            <section class="program_section">


                <div class="section_header">


                    <h2>

                        Application Details

                    </h2>


                    <p>

                        Complete information about your submitted application.

                    </p>


                </div>


                <!-- =============================================
                     APPLICATION CARD
                ============================================== -->

                <div class="program_card">


                    <!-- =========================================
                         APPLICATION HEADER
                    ========================================== -->

                    <div class="program_header">


                        <div>


                            <span class="program_id">

                                Application ID:

                                <?php

                                echo htmlspecialchars(
                                    $application["application_id"]
                                );

                                ?>

                            </span>


                            <h2>

                                <?php

                                echo htmlspecialchars(
                                    $application["program_name"]
                                );

                                ?>

                            </h2>


                        </div>


                        <!-- STATUS -->

                        <span class="seat_badge">

                            <?php

                            echo htmlspecialchars(
                                $application["status"]
                            );

                            ?>

                        </span>


                    </div>


                    <!-- =========================================
                         PROGRAM INFORMATION
                    ========================================== -->

                    <div class="description_box">


                        <h3>

                            Exchange Program Information

                        </h3>


                    </div>


                    <div class="program_details">


                        <!-- PROGRAM ID -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Program ID

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["program_id"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- COUNTRY -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Country

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["country_name"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- UNIVERSITY -->

                        <div class="detail_item">


                            <span class="detail_label">

                                University

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["university_name"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- START DATE -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Program Start Date

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["start_date"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- END DATE -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Program End Date

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["end_date"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- DEADLINE -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Application Deadline

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["deadline"]
                                );

                                ?>

                            </span>


                        </div>


                    </div>


                    <!-- =========================================
                         STUDENT APPLICATION INFORMATION
                    ========================================== -->

                    <div class="description_box">


                        <h3>

                            Student Application Information

                        </h3>


                    </div>


                    <div class="program_details">


                        <!-- DEPARTMENT -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Department

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["department"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- CGPA -->

                        <div class="detail_item">


                            <span class="detail_label">

                                CGPA

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["cgpa"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- SEMESTER -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Semester

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["semester"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- PREFERRED TERM -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Preferred Term

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["study_term"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- APPLICATION DATE -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Application Date

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["application_date"]
                                );

                                ?>

                            </span>


                        </div>


                        <!-- STATUS -->

                        <div class="detail_item">


                            <span class="detail_label">

                                Application Status

                            </span>


                            <span class="detail_value">

                                <?php

                                echo htmlspecialchars(
                                    $application["status"]
                                );

                                ?>

                            </span>


                        </div>


                    </div>


                    <!-- =========================================
                         STATEMENT OF PURPOSE
                    ========================================== -->

                    <div class="description_box">


                        <h3>

                            Statement of Purpose

                        </h3>


                        <p>

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $application["statement_of_purpose"]
                                )
                            );

                            ?>

                        </p>


                    </div>


                    <!-- =========================================
                         DOCUMENTS
                    ========================================== -->

                    <div class="description_box">


                        <h3>

                            Uploaded Documents

                        </h3>


                        <?php


                        if (
                            $documents &&
                            mysqli_num_rows($documents) > 0
                        ) {


                        ?>


                            <?php

                            while (
                                $document =
                                mysqli_fetch_assoc($documents)
                            ) {

                            ?>


                                <div
                                    style="
                                        display:flex;
                                        justify-content:space-between;
                                        align-items:center;
                                        padding:12px;
                                        margin-top:10px;
                                        border:1px solid #dddddd;
                                        border-radius:6px;
                                    "
                                >


                                    <div>


                                        <strong>

                                            <?php

                                            echo htmlspecialchars(
                                                $document["document_type"]
                                            );

                                            ?>

                                        </strong>


                                        <br>


                                        <span>

                                            <?php

                                            echo htmlspecialchars(
                                                $document["file_name"]
                                            );

                                            ?>

                                        </span>


                                        <br>


                                        <small>

                                            Verification:

                                            <?php

                                            echo htmlspecialchars(
                                                $document["verification_status"]
                                            );

                                            ?>

                                        </small>


                                    </div>


                                    <a
                                        href="<?php

                                        echo htmlspecialchars(
                                            $document["file_path"]
                                        );

                                        ?>"
                                        target="_blank"
                                        class="apply_button"
                                    >

                                        View File

                                    </a>


                                </div>


                            <?php

                            }

                            ?>


                        <?php

                        }

                        else {

                        ?>


                            <p>

                                No documents found.

                            </p>


                        <?php

                        }

                        ?>


                    </div>


                    <!-- =========================================
                         ACTION BUTTONS
                    ========================================== -->

                    <div
                        class="program_action"
                        style="
                            gap:15px;
                            display:flex;
                            flex-wrap:wrap;
                        "
                    >


                        <!-- BACK -->

                        <a
                            href="my_applications.php"
                            class="apply_button"
                        >

                            Back to My Applications

                        </a>


                        <!-- EDIT ONLY IF PENDING -->

                        <?php

                        if (
                            strtolower(
                                $application["status"]
                            ) === "pending"
                        ) {

                        ?>


                            <a
                                href="edit_application.php?application_id=<?php

                                echo urlencode(
                                    $application["application_id"]
                                );

                                ?>"
                                class="apply_button"
                            >

                                Edit Application

                            </a>


                        <?php

                        }

                        ?>


                    </div>


                </div>


            </section>


        </main>


    </div>


    <!-- =================================================
         FOOTER
    ================================================= -->

    <?php include "footer.php"; ?>


</body>


</html>