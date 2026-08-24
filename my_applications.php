<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SEPMS - My Applications</title>

    <link rel="stylesheet" href="my_applications.css">

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


        <a href="student_dashboard.php"
           class="sidebar_item">
            Dashboard
        </a>


        <a href="search_apply.php"
           class="sidebar_item">
            Search & Apply
        </a>


        <a href="my_applications.php"
           class="sidebar_item active">
            My Applications
        </a>


        <a href="student_documents.php"
           class="sidebar_item">
            Documents
        </a>


        <a href="application_status.php"
           class="sidebar_item">
            Application Status
        </a>


    </nav>



    <!-- ================= ACCOUNT ================= -->

    <div class="sidebar_bottom">


        <a href="update_profile.php"
           class="sidebar_item">
            Update Profile
        </a>


        <a href="change_password.php"
           class="sidebar_item">
            Change Password
        </a>


        <a href="logout.php"
           class="sidebar_item logout">
            Logout
        </a>


    </div>


</aside>



            <!-- ================= ACCOUNT ================= -->

            <div class="sidebar_bottom">


                <a href="update_profile.php"
                   class="sidebar_item">
                    Update Profile
                </a>


                <a href="change_password.php"
                   class="sidebar_item">
                    Change Password
                </a>


                <a href="login.php"
                   class="sidebar_item logout">
                    Logout
                </a>


            </div>


        </aside>



        <!-- ================= MAIN CONTENT ================= -->

        <main class="main_content">


            <!-- ================= PAGE HEADER ================= -->

            <section class="page_header">

                <h1>
                    My Applications
                </h1>

                <p>
                    View the exchange program applications you have submitted.
                </p>

            </section>



            <!-- ================= APPLICATION LIST ================= -->

            <section class="content_box">


                <div class="section_header">

                    <div>

                        <h2>
                            Submitted Applications
                        </h2>

                        <p>
                            Your submitted exchange program applications
                            will appear here.
                        </p>

                    </div>


                    <a href="search_apply.php"
                       class="new_application_button">

                        Apply for New Program

                    </a>

                </div>



                <!-- ================= APPLICATION TABLE ================= -->

                <div class="table_container">

                    <table class="application_table">


                        <thead>

                            <tr>

                                <th>
                                    Application ID
                                </th>

                                <th>
                                    Program
                                </th>

                                <th>
                                    University
                                </th>

                                <th>
                                    Country
                                </th>

                                <th>
                                    Applied Date
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


                            <!-- Temporary empty data -->

                            <tr>

                                <td colspan="7"
                                    class="empty_data">

                                    No applications submitted yet.

                                </td>

                            </tr>


                        </tbody>


                    </table>

                </div>


            </section>



            <!-- ================= FOOTER ================= -->

           <?php include "footer.php"; ?>


        </main>


    </div>


</body>

</html>