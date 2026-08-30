<?php

session_start();

?>

<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">


    <title>SEPMS - Admin Dashboard</title>


    <link rel="stylesheet"
          href="admin_dashboard.css">


</head>


<body>


    <!-- ================= HEADER ================= -->


    <?php include "header.php"; ?>



    <!-- ================= PAGE LAYOUT ================= -->


    <div class="page_layout">



        <!-- ================= SIDEBAR ================= -->


        <aside class="sidebar">


            <div class="sidebar_heading">

                ADMIN PANEL

            </div>



            <nav class="sidebar_menu">



                <!-- Dashboard -->


                <a
                    href="admin_dashboard.php"
                    class="sidebar_item"
                >

                    Dashboard

                </a>



                <!-- Students -->


                <a
                    href="manage_students.php"
                    class="sidebar_item"
                >

                    Students

                </a>



                <!-- Coordinators -->


                <a
                    href="manage_coordinators.php"
                    class="sidebar_item"
                >

                    Coordinators

                </a>



                <!-- Countries -->


                <a
                    href="manage_countries.php"
                    class="sidebar_item"
                >

                    Countries

                </a>



                <!-- Universities -->


                <a
                    href="manage_universities.php"
                    class="sidebar_item"
                >

                    Universities

                </a>



                <!-- Exchange Programs -->


                <a
                    href="manage_exchange_programs.php"
                    class="sidebar_item"
                >

                    Exchange Programs

                </a>



                <!-- Applications -->


                <a
                    href="applications.php"
                    class="sidebar_item"
                >

                    Applications

                </a>



                <!-- Documents -->


                <a
                    href="documents.php"
                    class="sidebar_item"
                >

                    Documents

                </a>



                <!-- Scholarships -->


                <a
                    href="scholarships.php"
                    class="sidebar_item"
                >

                    Scholarships

                </a>



                <!-- Nominations -->


                <a
                    href="nominations.php"
                    class="sidebar_item"
                >

                    Nominations

                </a>



                <!-- Exchange Records -->


                <a
                    href="exchange_records.php"
                    class="sidebar_item"
                >

                    Exchange Records

                </a>



            </nav>



            <!-- ================= BOTTOM MENU ================= -->


            <div class="sidebar_bottom">



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



            <!-- ================= PAGE HEADER ================= -->


            <section class="welcome_box">


                <h1>

                    Admin Dashboard

                </h1>


                <p>

                    Welcome to the Student Exchange Program Management System.

                </p>


            </section>



        </main>


    </div>



    <!-- ================= FOOTER ================= -->


    <?php include "footer.php"; ?>


</body>

</html>