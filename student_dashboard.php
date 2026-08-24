<?php

/* ================= STUDENT SESSION ================= */

session_start();

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Student Dashboard</title>

    <link rel="stylesheet"
          href="student_dashboard.css">

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


                <!-- Dashboard -->

                <a
                    href="student_dashboard.php"
                    class="sidebar_item "
                >

                    Dashboard

                </a>


                <!-- Search & Apply -->

                <a
                    href="search_apply.php"
                    class="sidebar_item "
                >

                    Search & Apply

                </a>


                <!-- My Applications -->

                <a
                    href="application_form.php"
                    class="sidebar_item "
                >

                    My Applications

                </a>


                <!-- Application Status -->

                <a
                    href="application_status.php"
                    class="sidebar_item "
                >

                    Application Status

                </a>


            </nav>



            <!-- ================= BOTTOM MENU ================= -->

            <div class="sidebar_bottom">


                <!-- Update Profile -->

                <a
                    href="update_profile.php"
                    class="sidebar_item"
                >

                    Update Profile

                </a>


                <!-- Change Password -->

                <a
                    href="change_password.php"
                    class="sidebar_item"
                >

                    Change Password

                </a>


                <!-- Logout -->

                <a
                    href="logout.php"
                    class="sidebar_item logout"
                >

                    Logout

                </a>


            </div>


        </aside>



        <!-- ================= MAIN CONTENT ================= -->

        <main class="main_content">


            <!-- ================= WELCOME ================= -->

            <section class="welcome_box">


                <h1>

                    Student Dashboard

                </h1>


                <p>

                    Welcome to the Student Exchange Program
                    Management System.

                </p>


            </section>


        </main>


    </div>



    <!-- ================= FOOTER ================= -->

    <?php include "footer.php"; ?>


</body>

</html>