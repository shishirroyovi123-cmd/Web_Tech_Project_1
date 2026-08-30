<?php

/* ================= COORDINATOR SESSION ================= */

session_start();

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Coordinator Dashboard</title>

    <link rel="stylesheet"
          href="coordinator_dashboard.css">

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


                <!-- Dashboard -->

                <a
                    href="coordinator_dashboard.php"
                    class="sidebar_item active"
                >

                    Dashboard

                </a>


                <!-- Feature 1 -->

                <a
                    href="review_applications.php"
                    class="sidebar_item"
                >

                    Review Applications

                </a>


                <!-- Feature 2 -->

                <a
                    href="verify_documents.php"
                    class="sidebar_item"
                >

                    Verify Documents

                </a>


                <!-- Feature 3 -->

                <a
                    href="manage_nominations.php"
                    class="sidebar_item"
                >

                    Manage Nominations

                </a>


            </nav>



            <!-- ================= ACCOUNT MENU ================= -->

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

                    Coordinator Dashboard

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