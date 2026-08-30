<?php

/* =========================================================
   MVC CONNECTION
   ========================================================= */

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/models/Country.php";


/* =========================================================
   COUNTRY MODEL
   ========================================================= */

$countryModel = new Country($conn);


/* =========================================================
   GET ALL COUNTRIES
   ========================================================= */

$countries = $countryModel->getAll();


/* =========================================================
   ERROR / SUCCESS MESSAGE
   ========================================================= */

$error = "";
$success = "";


if (isset($_GET["error"])) {

    $error = $_GET["error"];

}


if (isset($_GET["success"])) {

    $success = $_GET["success"];

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
        SEPMS - Manage Countries
    </title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="manage_countries.css"
    >


    <!-- JavaScript -->

    <script src="manage_countries.js"></script>

</head>


<body>


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <?php include "header.php"; ?>



    <!-- =====================================================
         MAIN LAYOUT
         ===================================================== -->

    <div class="dashboard_container">



        <!-- =================================================
             SIDEBAR
             ================================================= -->

        <aside class="sidebar">


            <!-- SIDEBAR TITLE -->

            <div class="sidebar_title">

                ADMIN PANEL

            </div>



            <!-- DASHBOARD -->

            <a href="admin_dashboard.php">

                Dashboard

            </a>



            <!-- STUDENTS -->

            <a href="manage_students.php">

                Students

            </a>



            <!-- COORDINATORS -->

            <a href="manage_coordinators.php">

                Coordinators

            </a>



            <!-- COUNTRIES -->

            <a
                href="manage_countries.php"
                class="active"
            >

                Countries

            </a>



            <!-- UNIVERSITIES -->

            <a href="manage_universities.php">

                Universities

            </a>



            <!-- EXCHANGE PROGRAMS -->

            <a href="manage_exchange_programs.php">

                Exchange Programs

            </a>



            <!-- APPLICATIONS -->

            <a href="applications.php">

                Applications

            </a>



            <!-- DOCUMENTS -->

            <a href="documents.php">

                Documents

            </a>



            <!-- SCHOLARSHIPS -->

            <a href="scholarships.php">

                Scholarships

            </a>



            <!-- NOMINATIONS -->

            <a href="nominations.php">

                Nominations

            </a>



            <!-- EXCHANGE RECORDS -->

            <a href="exchange_records.php">

                Exchange Records

            </a>



            <!-- =================================================
                 BOTTOM MENU
                 ================================================= -->

            <div class="sidebar_bottom">


                <!-- PROFILE -->

                <a href="update_profile.php">

                    Profile

                </a>



                <!-- CHANGE PASSWORD -->

                <a href="change_password.php">

                    Change Password

                </a>



                <!-- LOGOUT -->

                <a href="logout.php">

                    Logout

                </a>


            </div>


        </aside>



        <!-- =====================================================
             MAIN CONTENT
             ===================================================== -->

        <main class="main_content">



            <!-- =================================================
                 PAGE HEADER
                 ================================================= -->

            <div class="page_header">


                <h1>

                    Manage Countries

                </h1>


                <p>

                    Add, view, edit, delete and search countries.

                </p>


            </div>



            <!-- =================================================
                 JAVASCRIPT ERROR
                 ================================================= -->

            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            >
            </p>



            <!-- =================================================
                 PHP ERROR
                 ================================================= -->

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

                    " .
                    htmlspecialchars($error) .
                    "

                </p>

                ";

            }

            ?>



            <!-- =================================================
                 PHP SUCCESS
                 ================================================= -->

            <?php

            if ($success != "") {

                echo "

                <p
                    id='success_message'
                    style='
                        color:green;
                        text-align:center;
                    '
                >

                    " .
                    htmlspecialchars($success) .
                    "

                </p>

                ";

            }

            ?>



            <!-- =================================================
                 ADD COUNTRY
                 ================================================= -->

            <section class="form_section">


                <h2>

                    Add Country

                </h2>



                <form
                    method="POST"
                    action="controllers/CountryController.php"
                    onsubmit="return validateCountry();"
                    autocomplete="off"
                >


                    <table>


                        <!-- =================================================
                             COUNTRY ID
                             ================================================= -->

                        <tr>


                            <td>

                                <label
                                    for="country_id"
                                >

                                    Country ID

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="country_id"
                                    name="country_id"
                                    placeholder="Enter country ID"
                                >

                            </td>


                        </tr>



                        <!-- =================================================
                             COUNTRY NAME
                             ================================================= -->

                        <tr>


                            <td>

                                <label
                                    for="country_name"
                                >

                                    Country Name

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="country_name"
                                    name="country_name"
                                    placeholder="Enter country name"
                                >

                            </td>


                        </tr>



                        <!-- =================================================
                             REGION
                             ================================================= -->

                        <tr>


                            <td>

                                <label
                                    for="region"
                                >

                                    Region

                                </label>

                            </td>


                            <td>

                                <input
                                    type="text"
                                    id="region"
                                    name="region"
                                    placeholder="Enter region"
                                >

                            </td>


                        </tr>



                        <!-- =================================================
                             BUTTONS
                             ================================================= -->

                        <tr>


                            <td colspan="2">


                                <div class="button_area">


                                    <!-- SAVE -->

                                    <button
                                        type="submit"
                                        name="add_country"
                                        class="save_btn"
                                    >

                                        Save

                                    </button>



                                    <!-- RESET -->

                                    <button
                                        type="reset"
                                        class="reset_btn"
                                    >

                                        Reset

                                    </button>


                                </div>


                            </td>


                        </tr>


                    </table>


                </form>


            </section>



            <!-- =================================================
                 COUNTRY LIST
                 ================================================= -->

            <section class="list_section">



                <!-- =================================================
                     LIST HEADER
                     ================================================= -->

                <div class="list_header">


                    <div>


                        <h2>

                            Country List

                        </h2>


                        <p>

                            View and manage registered countries.

                        </p>


                    </div>



                    <!-- =================================================
                         SEARCH
                         ================================================= -->

                    <div class="search_area">


                        <input
                            type="text"
                            id="search_country"
                            placeholder="Search country"
                        >


                        <button
                            type="button"
                            onclick="searchCountry()"
                        >

                            Search

                        </button>


                    </div>


                </div>



                <!-- =================================================
                     COUNTRY TABLE
                     ================================================= -->

                <div class="table_container">


                    <table class="country_table">


                        <!-- TABLE HEADER -->

                        <thead>


                            <tr>


                                <th>

                                    Country ID

                                </th>


                                <th>

                                    Country Name

                                </th>


                                <th>

                                    Region

                                </th>


                                <th>

                                    Action

                                </th>


                            </tr>


                        </thead>



                        <!-- TABLE BODY -->

                        <tbody id="country_table_body">


                        <?php


                        if (
                            $countries &&
                            mysqli_num_rows($countries) > 0
                        ) {


                            while (
                                $row =
                                mysqli_fetch_assoc($countries)
                            ) {


                        ?>


                                <tr>


                                    <!-- COUNTRY ID -->

                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $row["country_id"]
                                        );

                                        ?>

                                    </td>



                                    <!-- COUNTRY NAME -->

                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $row["country_name"]
                                        );

                                        ?>

                                    </td>



                                    <!-- REGION -->

                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $row["region"]
                                        );

                                        ?>

                                    </td>



                                    <!-- ACTION -->

                                    <td>


                                        <a
                                            href="controllers/CountryController.php?delete=<?php echo urlencode($row["country_id"]); ?>"
                                            onclick="
                                                return confirm(
                                                    'Are you sure you want to delete this country?'
                                                );
                                            "
                                        >

                                            Delete

                                        </a>


                                    </td>


                                </tr>


                        <?php


                            }


                        }
                        else {


                        ?>


                            <!-- EMPTY DATA -->

                            <tr>


                                <td
                                    colspan="4"
                                    class="empty_data"
                                >

                                    No countries available.

                                </td>


                            </tr>


                        <?php

                        }


                        ?>


                        </tbody>


                    </table>


                </div>


            </section>



        </main>


    </div>



    <!-- =====================================================
         CLEAR URL AND MESSAGES
         ===================================================== -->

    <script>


        /*
         * Remove ?error or ?success
         * from URL.
         */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }



        /*
         * Clear messages when
         * Reset button is clicked.
         */

        let resetButton =
            document.querySelector(".reset_btn");


        if (resetButton) {

            resetButton.addEventListener(
                "click",
                function () {


                    let jsError =
                        document.getElementById(
                            "js_error"
                        );


                    let phpError =
                        document.getElementById(
                            "php_error"
                        );


                    let successMessage =
                        document.getElementById(
                            "success_message"
                        );



                    if (jsError) {

                        jsError.innerHTML = "";

                        jsError.style.display =
                            "none";

                    }



                    if (phpError) {

                        phpError.remove();

                    }



                    if (successMessage) {

                        successMessage.remove();

                    }


                }
            );

        }


    </script>



    <!-- =====================================================
         FOOTER
         ===================================================== -->

    <?php include "footer.php"; ?>


</body>

</html>