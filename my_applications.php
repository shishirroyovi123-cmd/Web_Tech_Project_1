<?php

session_start();


/* =========================================================
   DATABASE
========================================================= */

require_once __DIR__ . "/config/db.php";


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
        urlencode("Only students can access this page.")
    );

    exit();

}


/* =========================================================
   GET LOGGED-IN STUDENT ID
========================================================= */

$studentID = $_SESSION["user_id"];


/* =========================================================
   VARIABLES
========================================================= */

$error = "";
$success = "";

$search = "";


/* =========================================================
   GET SEARCH VALUE
========================================================= */

if (isset($_GET["search"])) {

    $search =
        trim($_GET["search"]);

}


/* =========================================================
   GET ERROR MESSAGE
========================================================= */

if (isset($_GET["error"])) {

    $error =
        $_GET["error"];

}


/* =========================================================
   GET SUCCESS MESSAGE
========================================================= */

if (isset($_GET["success"])) {

    $success =
        $_GET["success"];

}


/* =========================================================
   LOAD STUDENT APPLICATIONS
========================================================= */

$sql = "
    SELECT
        a.application_id,
        a.student_id,
        a.program_id,
        a.department,
        a.cgpa,
        a.semester,
        a.study_term,
        a.statement_of_purpose,
        a.application_date,
        a.status,
        a.declaration,

        ep.program_name,
        ep.start_date,
        ep.end_date,
        ep.deadline,
        ep.available_seats,

        c.country_name,

        u.university_name

    FROM applications a

    INNER JOIN exchange_programs ep
        ON a.program_id = ep.program_id

    INNER JOIN countries c
        ON ep.country_id = c.country_id

    INNER JOIN universities u
        ON ep.university_id = u.university_id

    WHERE a.student_id = ?
";


/* =========================================================
   SEARCH FILTER
========================================================= */

if ($search !== "") {

    $sql .= "
        AND
        (
            ep.program_name LIKE ?
            OR c.country_name LIKE ?
            OR u.university_name LIKE ?
            OR a.status LIKE ?
            OR CAST(a.application_id AS CHAR) LIKE ?
        )
    ";

}


/* =========================================================
   ORDER BY
========================================================= */

$sql .= "
    ORDER BY
        a.application_id DESC
";


/* =========================================================
   PREPARE QUERY
========================================================= */

$stmt = mysqli_prepare(
    $conn,
    $sql
);


if (!$stmt) {

    die(
        "Database error: " .
        mysqli_error($conn)
    );

}


/* =========================================================
   BIND PARAMETERS
========================================================= */

if ($search !== "") {

    $searchValue =
        "%" . $search . "%";


    mysqli_stmt_bind_param(
        $stmt,
        "isssss",
        $studentID,
        $searchValue,
        $searchValue,
        $searchValue,
        $searchValue,
        $searchValue
    );

} else {

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $studentID
    );

}


/* =========================================================
   EXECUTE QUERY
========================================================= */

mysqli_stmt_execute(
    $stmt
);


$result =
    mysqli_stmt_get_result(
        $stmt
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
        SEPMS - My Applications
    </title>


    <!-- =================================================
         SAME CSS AS SEARCH APPLY
    ================================================= -->

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
        ================================================= -->

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
                    class="sidebar_item active"
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



            <!-- =================================================
                 SIDEBAR BOTTOM
            ================================================= -->

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
        ================================================= -->

        <main class="main_content">


            <!-- =================================================
                 PAGE HEADER
            ================================================= -->

            <section class="page_header">


                <h1>

                    My Applications

                </h1>


                <p>

                    View, search and manage your exchange program applications.

                </p>


            </section>



            <!-- =================================================
                 MESSAGES
            ================================================= -->

            <?php if ($error != "") { ?>


                <p
                    id="php_error"
                    style="
                        color:red;
                        text-align:center;
                    "
                >

                    <?php

                    echo htmlspecialchars(
                        $error
                    );

                    ?>

                </p>


            <?php } ?>



            <?php if ($success != "") { ?>


                <p
                    id="success_message"
                    style="
                        color:green;
                        text-align:center;
                    "
                >

                    <?php

                    echo htmlspecialchars(
                        $success
                    );

                    ?>

                </p>


            <?php } ?>



            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            ></p>



            <!-- =================================================
                 SEARCH SECTION
            ================================================= -->

            <section class="search_box">


                <h2>

                    Search My Applications

                </h2>



                <form
                    method="GET"
                    action="my_applications.php"
                    onsubmit="return validateSearch();"
                >


                    <div class="search_row">


                        <!-- =====================================
                             SEARCH
                        ====================================== -->

                        <div class="search_group">


                            <label for="search">

                                Search

                            </label>


                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="<?php

                                    echo htmlspecialchars(
                                        $search
                                    );

                                ?>"
                                placeholder="Program, country, university, status or application ID"
                            >


                        </div>



                        <!-- =====================================
                             SEARCH BUTTON
                        ====================================== -->

                        <button
                            type="submit"
                            class="search_button"
                        >

                            Search

                        </button>



                        <!-- =====================================
                             CLEAR BUTTON
                        ====================================== -->

                        <?php

                        if ($search !== "") {

                        ?>


                            <a
                                href="my_applications.php"
                                class="search_button"
                                style="
                                    text-decoration:none;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                "
                            >

                                Clear

                            </a>


                        <?php

                        }

                        ?>


                    </div>


                </form>


            </section>



            <!-- =================================================
                 APPLICATION RESULTS
            ================================================= -->

            <section class="program_section">


                <div class="section_header">


                    <h2>

                        Your Applications

                    </h2>


                    <p>

                        Browse your submitted applications and edit applications
                        that are still pending.

                    </p>


                </div>



                <!-- =================================================
                     APPLICATION LIST
                ================================================= -->

                <?php

                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {

                ?>


                    <?php

                    while (
                        $row =
                        mysqli_fetch_assoc(
                            $result
                        )
                    ) {

                    ?>


                        <!-- =========================================
                             APPLICATION CARD
                        ========================================== -->

                        <div class="program_card">


                            <!-- =====================================
                                 APPLICATION HEADER
                            ====================================== -->

                            <div class="program_header">


                                <div>


                                    <span class="program_id">

                                        Application ID:

                                        <?php

                                        echo htmlspecialchars(
                                            $row["application_id"]
                                        );

                                        ?>

                                    </span>



                                    <h2>

                                        <?php

                                        echo htmlspecialchars(
                                            $row["program_name"]
                                        );

                                        ?>

                                    </h2>


                                </div>



                                <span class="seat_badge">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["status"]
                                    );

                                    ?>

                                </span>


                            </div>



                            <!-- =====================================
                                 APPLICATION DETAILS
                            ====================================== -->

                            <div class="program_details">


                                <!-- PROGRAM ID -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Program ID

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["program_id"]
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
                                            $row["country_name"]
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
                                            $row["university_name"]
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- DEPARTMENT -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Department

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["department"]
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
                                            $row["semester"]
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
                                            $row["study_term"]
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
                                            $row["cgpa"]
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- APPLICATION DATE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Applied Date

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["application_date"]
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- PROGRAM START DATE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Program Start Date

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["start_date"]
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- PROGRAM END DATE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Program End Date

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["end_date"]
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
                                            $row["deadline"]
                                        );

                                        ?>

                                    </span>


                                </div>



                            </div>



                            <!-- =====================================
                                 STATEMENT OF PURPOSE
                            ====================================== -->

                            <div class="description_box">


                                <h3>

                                    Statement of Purpose

                                </h3>


                                <p>

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $row["statement_of_purpose"]
                                        )
                                    );

                                    ?>

                                </p>


                            </div>



                            <!-- =====================================
                                 ACTION BUTTONS
                            ====================================== -->

                            <div class="program_action">


                                <!-- VIEW -->

                                <a
                                    href="view_application.php?application_id=<?php

                                        echo urlencode(
                                            $row["application_id"]
                                        );

                                    ?>"
                                    class="apply_button"
                                >

                                    View Application

                                </a>



                                <!-- EDIT ONLY PENDING -->

                                <?php

                                if (
                                    strtolower(
                                        $row["status"]
                                    ) === "pending"
                                ) {

                                ?>


                                    <a
                                        href="edit_application.php?application_id=<?php

                                            echo urlencode(
                                                $row["application_id"]
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


                    <?php

                    }

                    ?>


                <?php

                }

                else {

                ?>


                    <!-- =========================================
                         NO APPLICATIONS
                    ========================================== -->

                    <div class="empty_programs">


                        <div class="empty_icon">

                            +

                        </div>


                        <h3>

                            No Applications Found

                        </h3>


                        <p>


                            <?php

                            if ($search !== "") {

                                echo
                                    "No applications match your search.";

                            }

                            else {

                                echo
                                    "You have not applied for any exchange program yet.";

                            }

                            ?>


                        </p>


                

                    </div>


                <?php

                }

                ?>


            </section>


        </main>


    </div>



    <!-- =================================================
         FOOTER
    ================================================= -->

    <?php include "footer.php"; ?>



    <!-- =================================================
         JAVASCRIPT
    ================================================= -->

    <script>


        /* =====================================================
           SEARCH VALIDATION
        ===================================================== */

        function validateSearch() {


            let search =
                document.getElementById(
                    "search"
                ).value.trim();


            let error =
                document.getElementById(
                    "js_error"
                );


            /* Clear previous error */

            error.innerHTML = "";

            error.style.display =
                "none";


            /* Search cannot be empty */

            if (search === "") {


                error.innerHTML =
                    "Please enter something to search.";


                error.style.display =
                    "block";


                return false;


            }


            return true;


        }



        /* =====================================================
           CLEAR MESSAGES
        ===================================================== */

        window.addEventListener(
            "load",
            function () {


                let phpError =
                    document.getElementById(
                        "php_error"
                    );


                let successMessage =
                    document.getElementById(
                        "success_message"
                    );


                setTimeout(
                    function () {


                        if (phpError) {

                            phpError.style.display =
                                "none";

                        }


                        if (successMessage) {

                            successMessage.style.display =
                                "none";

                        }


                    },
                    5000
                );


            }
        );


    </script>


</body>


</html>


<?php


/* =========================================================
   CLOSE STATEMENT
========================================================= */

mysqli_stmt_close(
    $stmt
);

?>