<?php

/* =========================================================
   DATABASE + MODEL
   ========================================================= */

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/models/University.php";


$universityModel = new University($conn);


/* =========================================================
   GET ALL UNIVERSITIES
   ========================================================= */

$universities = $universityModel->getAll();


/* =========================================================
   GET ALL COUNTRIES
   ========================================================= */

$countries = mysqli_query(
    $conn,
    "SELECT country_id, country_name
     FROM countries
     ORDER BY country_name ASC"
);


/* =========================================================
   SUCCESS / ERROR MESSAGE
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
        SEPMS - Manage Universities
    </title>

    <link
        rel="stylesheet"
        href="manage_universities.css"
    >

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


        <div class="sidebar_bottom">


            <a href="update_profile.php">
                Profile
            </a>


            <a href="change_password.php">
                Change Password
            </a>


            <a href="logout.php">
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

        <div class="page_header">

            <h1>
                Manage Universities
            </h1>

            <p>
                Add, view, edit, delete and search universities.
            </p>

        </div>



        <!-- =================================================
             PHP ERROR MESSAGE
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
                echo htmlspecialchars($error);
                ?>

            </p>

        <?php } ?>



        <!-- =================================================
             PHP SUCCESS MESSAGE
             ================================================= -->

        <?php if ($success != "") { ?>

            <p
                id="success_message"
                style="
                    color:green;
                    text-align:center;
                "
            >

                <?php
                echo htmlspecialchars($success);
                ?>

            </p>

        <?php } ?>



        <!-- =================================================
             ADD UNIVERSITY
             ================================================= -->

        <section class="form_section">


            <h2>
                Add University
            </h2>


            <form
                method="POST"
                action="controllers/UniversityController.php"
                onsubmit="return validateUniversity();"
                autocomplete="off"
            >


                <table>


                    <!-- UNIVERSITY ID -->

                    <tr>

                        <td>

                            <label for="university_id">
                                University ID
                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="university_id"
                                name="university_id"
                                placeholder="Enter university ID"
                            >

                        </td>

                    </tr>



                    <!-- UNIVERSITY NAME -->

                    <tr>

                        <td>

                            <label for="university_name">
                                University Name
                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="university_name"
                                name="university_name"
                                placeholder="Enter university name"
                            >

                        </td>

                    </tr>



                    <!-- COUNTRY -->

                    <tr>

                        <td>

                            <label for="country">
                                Country
                            </label>

                        </td>


                        <td>

                            <select
                                id="country"
                                name="country_id"
                            >

                                <option
                                    value=""
                                    selected
                                    disabled
                                >
                                    Select country
                                </option>


                                <?php

                                if ($countries) {

                                    while (
                                        $country =
                                        mysqli_fetch_assoc(
                                            $countries
                                        )
                                    ) {

                                ?>

                                    <option
                                        value="<?php
                                            echo htmlspecialchars(
                                                $country["country_id"]
                                            );
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $country["country_name"]
                                        );
                                        ?>

                                    </option>

                                <?php

                                    }

                                }

                                ?>

                            </select>

                        </td>

                    </tr>



                    <!-- UNIVERSITY EMAIL -->

                    <tr>

                        <td>

                            <label for="university_email">
                                University Email
                            </label>

                        </td>


                        <td>

                            <input
                                type="email"
                                id="university_email"
                                name="university_email"
                                placeholder="Enter university email"
                            >

                        </td>

                    </tr>



                    <!-- UNIVERSITY ADDRESS -->

                    <tr>

                        <td>

                            <label for="university_address">
                                University Address
                            </label>

                        </td>


                        <td>

                            <textarea
                                id="university_address"
                                name="university_address"
                                placeholder="Enter university address"
                            ></textarea>

                        </td>

                    </tr>



                    <!-- JAVASCRIPT ERROR -->

                    <tr>

                        <td colspan="2">

                            <p
                                id="js_error"
                                style="
                                    color:red;
                                    text-align:center;
                                    display:none;
                                "
                            ></p>

                        </td>

                    </tr>



                    <!-- BUTTONS -->

                    <tr>

                        <td colspan="2">

                            <div class="button_area">


                                <button
                                    type="submit"
                                    name="add_university"
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



        <!-- =================================================
             UNIVERSITY LIST
             ================================================= -->

        <section class="list_section">


            <div class="list_header">


                <div>

                    <h2>
                        University List
                    </h2>

                    <p>
                        View and manage registered universities.
                    </p>

                </div>



                <!-- SEARCH -->

                <div class="search_area">


                    <input
                        type="text"
                        id="search_university"
                        placeholder="Search university"
                    >


                    <button
                        type="button"
                        onclick="searchUniversity()"
                    >
                        Search
                    </button>


                </div>

            </div>



            <!-- =================================================
                 UNIVERSITY TABLE
                 ================================================= -->

            <div class="table_container">


                <table class="university_table">


                    <thead>

                        <tr>

                            <th>
                                University ID
                            </th>

                            <th>
                                University Name
                            </th>

                            <th>
                                Country
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Address
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>



                    <tbody
                        id="university_table_body"
                    >


                    <?php

                    if (
                        $universities &&
                        mysqli_num_rows(
                            $universities
                        ) > 0
                    ) {


                        while (
                            $university =
                            mysqli_fetch_assoc(
                                $universities
                            )
                        ) {

                    ?>

                        <tr>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $university[
                                        "university_id"
                                    ]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $university[
                                        "university_name"
                                    ]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $university[
                                        "country_name"
                                    ] ?? ""
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $university[
                                        "university_email"
                                    ]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $university[
                                        "university_address"
                                    ]
                                );

                                ?>

                            </td>


                            <td>

                                <a
                                    href="controllers/UniversityController.php?delete=<?php

                                        echo urlencode(
                                            $university[
                                                "university_id"
                                            ]
                                        );

                                    ?>"
                                    onclick="
                                        return confirm(
                                            'Are you sure you want to delete this university?'
                                        );
                                    "
                                >
                                    Delete
                                </a>

                            </td>


                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="6"
                                class="empty_data"
                            >
                                No universities available.
                            </td>

                        </tr>

                    <?php

                    }

                    ?>


                    </tbody>

                </table>

            </div>

        </section>



        <!-- =================================================
             FOOTER
             ================================================= -->

        <?php include "footer.php"; ?>


    </main>

</div>



<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="manage_universities.js"></script>


<script>

/* =========================================================
   REMOVE SUCCESS / ERROR FROM URL
   ========================================================= */

if (window.location.search !== "") {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );

}


/* =========================================================
   HIDE MESSAGE AFTER 4 SECONDS
   ========================================================= */

setTimeout(function () {

    let successMessage =
        document.getElementById(
            "success_message"
        );

    let errorMessage =
        document.getElementById(
            "php_error"
        );


    if (successMessage) {

        successMessage.style.display =
            "none";

    }


    if (errorMessage) {

        errorMessage.style.display =
            "none";

    }

}, 4000);

</script>


</body>

</html>