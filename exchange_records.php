<?php

/* ================= PHP ================= */

/*
   Database connection will be added later.
   For now, exchange records are empty.
*/

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Exchange Records</title>

    <link rel="stylesheet"
          href="exchange_records.css">

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

                    Exchange Records

                </h1>


                <p>

                    View and monitor student exchange participation records.

                </p>


            </div>



            <!-- ================= STATISTICS ================= -->

            <section class="statistics">


                <div class="stat_card">


                    <div class="stat_title">

                        Total Exchange Records

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Active Exchanges

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Completed Exchanges

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>



                <div class="stat_card">


                    <div class="stat_title">

                        Cancelled Exchanges

                    </div>


                    <div class="stat_number">

                        0

                    </div>


                </div>


            </section>



            <!-- ================= EXCHANGE RECORD LIST ================= -->

            <section class="record_section">


                <!-- LIST HEADER -->

                <div class="list_header">


                    <div>


                        <h2>

                            Exchange Record List

                        </h2>


                        <p>

                            Exchange records retrieved from the database.

                        </p>


                    </div>



                    <!-- SEARCH -->

                    <div class="search_area">


                        <input
                            type="text"
                            placeholder="Search exchange record"
                        >


                        <button type="button">

                            Search

                        </button>


                    </div>


                </div>



                <!-- ================= FILTER ================= -->

                <div class="filter_area">


                    <div class="filter_group">


                        <label for="status_filter">

                            Status

                        </label>


                        <select id="status_filter">


                            <option value="">

                                All Status

                            </option>


                            <option value="Active">

                                Active

                            </option>


                            <option value="Completed">

                                Completed

                            </option>


                            <option value="Cancelled">

                                Cancelled

                            </option>


                        </select>


                    </div>



                    <div class="filter_group">


                        <label for="university_filter">

                            University

                        </label>


                        <select id="university_filter">


                            <option value="">

                                All Universities

                            </option>


                        </select>


                    </div>



                    <div class="filter_group">


                        <label for="program_filter">

                            Program

                        </label>


                        <select id="program_filter">


                            <option value="">

                                All Programs

                            </option>


                        </select>


                    </div>


                </div>



                <!-- ================= TABLE ================= -->

                <div class="table_container">


                    <table class="record_table">


                        <thead>


                            <tr>


                                <th>

                                    Record ID

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

                                    Start Date

                                </th>


                                <th>

                                    End Date

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

                                    No exchange records available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- ================= RECORD DETAILS ================= -->

            <section class="details_section">


                <h2>

                    Exchange Record Details

                </h2>


                <p class="details_instruction">

                    Select an exchange record from the list to view
                    complete information.

                </p>



                <table class="details_table">


                    <tr>


                        <td>

                            Record ID

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Student ID

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Student Name

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            Exchange Program

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

                            Start Date

                        </td>


                        <td>

                            -

                        </td>


                    </tr>



                    <tr>


                        <td>

                            End Date

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

<?php include "footer.php"; ?>

        </main>


    </div>



</body>

</html>