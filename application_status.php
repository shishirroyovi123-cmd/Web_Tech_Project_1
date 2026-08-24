<?php

/* ================= PHP ================= */

/*
   Database will be connected later.
   For now, this page only displays the form.
*/

$error = "";


/* ================= APPLICATION SELECTION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $application_id = trim($_POST["application_id"]);


    if (empty($application_id)) {

        $error = "Please select an application.";

    }

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Application & Exchange Status</title>

    <link rel="stylesheet"
          href="application_status.css">

    <script src="application_status.js"></script>

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
                   class="sidebar_item ">

                    Dashboard

                </a>


                <a href="search_apply.php"
                   class="sidebar_item ">

                    Search & Apply

                </a>


                <a href="application_form.php"
                   class="sidebar_item ">

                    My Applications

                </a>


                <a href="application_status.php"
                   class="sidebar_item ">

                    Application Status

                </a>


            </nav>



            <!-- ================= BOTTOM ================= -->

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
                    Track Application & Exchange Status
                </h1>


                <p>
                    View your application progress, remarks,
                    nomination status and exchange record.
                </p>


            </section>



            <!-- ================= ERROR ================= -->

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
                    style='
                        color:red;
                        text-align:center;
                    '
                >
                    $error
                </p>
                ";

            }

            ?>



            <!-- =================================================
                     APPLICATION SELECTION
            ================================================== -->

            <section class="content_box">


                <h2>
                    Select Application
                </h2>


                <p class="section_description">

                    Select an application to view its current status
                    and exchange information.

                </p>


                <form
                    method="POST"
                    action=""
                    onsubmit="return validateStatus();"
                >


                    <div class="application_selector">


                        <div class="form_group">


                            <label for="application_id">

                                Application

                            </label>


                            <select
                                id="application_id"
                                name="application_id"
                            >


                                <option value="">

                                    Select Application

                                </option>


                                <!--
                                    Applications will come
                                    from database later.
                                -->


                            </select>


                        </div>



                        <button
                            type="submit"
                            class="view_button"
                        >

                            View Status

                        </button>


                    </div>


                </form>


            </section>



            <!-- =================================================
                     APPLICATION INFORMATION
            ================================================== -->

            <section class="content_box">


                <h2>
                    Application Information
                </h2>


                <div class="information_grid">


                    <div class="information_item">

                        <span class="information_label">
                            Application ID
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>



                    <div class="information_item">

                        <span class="information_label">
                            Program ID
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>



                    <div class="information_item">

                        <span class="information_label">
                            Program Name
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>



                    <div class="information_item">

                        <span class="information_label">
                            University
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>



                    <div class="information_item">

                        <span class="information_label">
                            Country
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>



                    <div class="information_item">

                        <span class="information_label">
                            Application Date
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>


                </div>


            </section>



            <!-- =================================================
                     APPLICATION STATUS
            ================================================== -->

            <section class="content_box">


                <h2>
                    Application Status
                </h2>


                <div class="status_container">


                    <!-- STEP 1 -->

                    <div class="status_item">


                        <div class="status_number">
                            1
                        </div>


                        <div class="status_content">


                            <h3>
                                Application Submitted
                            </h3>


                            <p>
                                Your application has been submitted.
                            </p>


                            <span class="status_badge pending">
                                —
                            </span>


                        </div>


                    </div>



                    <div class="status_line"></div>



                    <!-- STEP 2 -->

                    <div class="status_item">


                        <div class="status_number">
                            2
                        </div>


                        <div class="status_content">


                            <h3>
                                Application Review
                            </h3>


                            <p>
                                Coordinator review status.
                            </p>


                            <span class="status_badge pending">
                                —
                            </span>


                        </div>


                    </div>



                    <div class="status_line"></div>



                    <!-- STEP 3 -->

                    <div class="status_item">


                        <div class="status_number">
                            3
                        </div>


                        <div class="status_content">


                            <h3>
                                Application Decision
                            </h3>


                            <p>
                                Final application decision.
                            </p>


                            <span class="status_badge pending">
                                —
                            </span>


                        </div>


                    </div>


                </div>


            </section>



            <!-- =================================================
                     COORDINATOR REMARKS
            ================================================== -->

            <section class="content_box">


                <h2>
                    Coordinator Remarks
                </h2>


                <div class="remarks_box">


                    <div class="remarks_header">


                        <span>
                            Latest Remark
                        </span>


                        <span>
                            —
                        </span>


                    </div>


                    <p class="remarks_text">

                        No remarks available.

                    </p>


                </div>


            </section>



            <!-- =================================================
                     NOMINATION STATUS
            ================================================== -->

            <section class="content_box">


                <h2>
                    Nomination Status
                </h2>


                <div class="nomination_grid">


                    <div class="information_item">


                        <span class="information_label">
                            Nomination Status
                        </span>


                        <span class="status_badge pending">
                            —
                        </span>


                    </div>



                    <div class="information_item">


                        <span class="information_label">
                            Nomination Date
                        </span>


                        <span class="information_value">
                            —
                        </span>


                    </div>



                    <div class="information_item">


                        <span class="information_label">
                            Nomination Remarks
                        </span>


                        <span class="information_value">
                            —
                        </span>


                    </div>


                </div>


            </section>



            <!-- =================================================
                     EXCHANGE RECORD
            ================================================== -->

            <section class="content_box">


                <h2>
                    Exchange Record
                </h2>


                <p class="section_description">

                    Your exchange record will appear here after
                    your exchange has been confirmed.

                </p>


                <div class="record_table_container">


                    <table class="record_table">


                        <thead>


                            <tr>


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
                                    Start Date
                                </th>


                                <th>
                                    End Date
                                </th>


                                <th>
                                    Status
                                </th>


                            </tr>


                        </thead>



                        <tbody>


                            <tr>


                                <td
                                    colspan="6"
                                    class="empty_data"
                                >

                                    No exchange record available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- ================= FOOTER ================= -->

           <?php include "footer.php"; ?>



    <!-- ================= CLEAR URL ================= -->

    <script>

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