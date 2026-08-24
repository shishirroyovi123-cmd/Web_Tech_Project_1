<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $country_id = trim($_POST["country_id"]);
    $country_name = trim($_POST["country_name"]);
    $region = trim($_POST["region"]);


    /* Check empty fields */

    if (
        empty($country_id) ||
        empty($country_name) ||
        empty($region)
    ) {

        header("Location: manage_countries.php?error=All fields are required.");
        exit();

    }


    /* Check Country ID */

    elseif (!is_numeric($country_id)) {

        header("Location: manage_countries.php?error=Country ID must contain numbers only.");
        exit();

    }


    /* Check Country Name */

    elseif (!preg_match("/^[A-Za-z ]+$/", $country_name)) {

        header("Location: manage_countries.php?error=Country name must contain letters only.");
        exit();

    }


    /* Check Region */

    elseif (!preg_match("/^[A-Za-z ]+$/", $region)) {

        header("Location: manage_countries.php?error=Region must contain letters only.");
        exit();

    }


    /* Successful validation */

    else {

        header("Location: manage_countries.php?success=Country information is valid.");
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

    <title>SEPMS - Manage Countries</title>

    <link rel="stylesheet"
          href="manage_countries.css">

    <script src="manage_countries.js"></script>

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



            <!-- Page Header -->

            <div class="page_header">


                <h1>

                    Manage Countries

                </h1>


                <p>

                    Add, view, edit, delete and search countries.

                </p>


            </div>



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



            <!-- ================= ADD COUNTRY ================= -->

            <section class="form_section">


                <h2>

                    Add Country

                </h2>



                <form
                    method="POST"
                    action=""
                    onsubmit="return validateCountry();"
                    autocomplete="off"
                >


                    <table>


                        <!-- Country ID -->

                        <tr>


                            <td>

                                <label for="country_id">

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



                        <!-- Country Name -->

                        <tr>


                            <td>

                                <label for="country_name">

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



                        <!-- Region -->

                        <tr>


                            <td>

                                <label for="region">

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



                        <!-- Buttons -->

                        <tr>


                            <td colspan="2">


                                <div class="button_area">


                                    <button
                                        type="submit"
                                        class="save_btn"
                                    >

                                        Save

                                    </button>


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



            <!-- ================= COUNTRY LIST ================= -->

            <section class="list_section">


                <div class="list_header">


                    <div>


                        <h2>

                            Country List

                        </h2>


                        <p>

                            View and manage registered countries.

                        </p>


                    </div>



                    <!-- Search -->

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



                <!-- Country Table -->

                <div class="table_container">


                    <table class="country_table">


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



                        <tbody>


                            <!-- Empty data for now -->


                            <tr>


                                <td
                                    colspan="4"
                                    class="empty_data"
                                >

                                    No countries available.

                                </td>


                            </tr>


                        </tbody>


                    </table>


                </div>


            </section>


        </main>


    </div>



    <!-- ================= CLEAR MESSAGES ================= -->

    <script>


        /* Remove error/success from URL */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        /* Clear message when Reset is clicked */

        let resetButton =
            document.querySelector(".reset_btn");


        resetButton.addEventListener("click", function () {


            let jsError =
                document.getElementById("js_error");


            let phpError =
                document.getElementById("php_error");


            let successMessage =
                document.getElementById("success_message");


            if (jsError) {

                jsError.innerHTML = "";

                jsError.style.display = "none";

            }


            if (phpError) {

                phpError.remove();

            }


            if (successMessage) {

                successMessage.remove();

            }


        });


    </script>
    <?php include "footer.php"; ?>


</body>

</html>