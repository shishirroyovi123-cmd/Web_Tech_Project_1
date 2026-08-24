<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $search = trim($_POST["search"]);
    $country = $_POST["country"];


    /* Check search */

    if ($search == "" && $country == "") {

        header(
            "Location: search_apply.php?error=Please enter something to search."
        );

        exit();

    }


    else {

        header(
            "Location: search_apply.php?success=Search information is valid."
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

    <title>SEPMS - Search & Apply</title>

    <link rel="stylesheet"
          href="search_apply.css">

    <script src="search_apply.js"></script>

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
                    class="sidebar_item "
                >

                    Search & Apply

                </a>


                <a
                    href="application_form.php"
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

                    Search & Apply for Exchange Programs

                </h1>


                <p>

                    Search available exchange programs and apply
                    for a suitable program.

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



            <!-- ================= SEARCH ================= -->

            <section class="search_box">


                <h2>

                    Search Exchange Programs

                </h2>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateSearch();"
                >


                    <div class="search_row">



                        <!-- SEARCH -->

                        <div class="search_group">


                            <label for="search">

                                Search

                            </label>


                            <input
                                type="text"
                                id="search"
                                name="search"
                                placeholder="Program name, country or university"
                            >


                        </div>



                        <!-- COUNTRY -->

                        <div class="search_group">


                            <label for="country">

                                Country

                            </label>


                            <select
                                id="country"
                                name="country"
                            >


                                <option value="">

                                    Select Country

                                </option>


                                <!-- Countries will come from database later -->


                            </select>


                        </div>



                        <!-- SEARCH BUTTON -->

                        <button
                            type="submit"
                            class="search_button"
                        >

                            Search

                        </button>


                    </div>


                </form>


            </section>



            <!-- ================= PROGRAM RESULTS ================= -->

            <section class="program_section">


                <div class="section_header">


                    <h2>

                        Available Exchange Programs

                    </h2>


                    <p>

                        Exchange programs will appear here after
                        they are added by the administrator.

                    </p>


                </div>



                <!-- ================= EMPTY STATE ================= -->

                <div class="empty_programs">


                    <div class="empty_icon">

                        +

                    </div>


                    <h3>

                        No Exchange Programs Available

                    </h3>


                    <p>

                        No exchange programs have been added yet.

                    </p>


                </div>



                <!-- =================================================
                     PROGRAM CARD

                     Database records will appear here later.
                ================================================== -->


                <!--

                <div class="program_card">


                    <div class="program_header">


                        <div>


                            <span class="program_id">

                                Program ID

                            </span>


                            <h2>

                                Program Name

                            </h2>


                        </div>


                        <span class="seat_badge">

                            Available Seats

                        </span>


                    </div>



                    <div class="program_details">



                        <div class="detail_item">


                            <span class="detail_label">

                                Country

                            </span>


                            <span class="detail_value">

                                Country Name

                            </span>


                        </div>



                        <div class="detail_item">


                            <span class="detail_label">

                                University

                            </span>


                            <span class="detail_value">

                                University Name

                            </span>


                        </div>



                        <div class="detail_item">


                            <span class="detail_label">

                                Start Date

                            </span>


                            <span class="detail_value">

                                Start Date

                            </span>


                        </div>



                        <div class="detail_item">


                            <span class="detail_label">

                                End Date

                            </span>


                            <span class="detail_value">

                                End Date

                            </span>


                        </div>



                        <div class="detail_item">


                            <span class="detail_label">

                                Application Deadline

                            </span>


                            <span class="detail_value">

                                Application Deadline

                            </span>


                        </div>



                        <div class="detail_item">


                            <span class="detail_label">

                                Available Seats

                            </span>


                            <span class="detail_value">

                                Available Seats

                            </span>


                        </div>


                    </div>



                    <div class="description_box">


                        <h3>

                            Description

                        </h3>


                        <p>

                            Program description will appear here.

                        </p>


                    </div>



                    <div class="program_action">


                        <a
                            href="application_form.php"
                            class="apply_button"
                        >

                            Apply Now

                        </a>


                    </div>


                </div>

                -->


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


        /* Clear search messages */

        let searchForm =
            document.querySelector("form");


        searchForm.addEventListener("reset", function () {

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