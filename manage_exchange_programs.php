<?php

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/models/ExchangeProgram.php";


$programModel =
    new ExchangeProgram($conn);


/* =========================================================
   GET ALL PROGRAMS
   ========================================================= */

$programs =
    $programModel->getAll();


/* =========================================================
   GET COUNTRIES
   ========================================================= */

$countries = mysqli_query(
    $conn,
    "SELECT country_id, country_name
     FROM countries
     ORDER BY country_name ASC"
);


/* =========================================================
   MESSAGES
   ========================================================= */

$error = "";

$success = "";


if (isset($_GET["error"])) {

    $error =
        $_GET["error"];

}


if (isset($_GET["success"])) {

    $success =
        $_GET["success"];

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
        SEPMS - Manage Exchange Programs
    </title>

    <link
        rel="stylesheet"
        href="manage_exchange_programs.css"
    >

</head>


<body>


<?php include "header.php"; ?>


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


        <div class="page_header">


            <h1>
                Manage Exchange Programs
            </h1>


            <p>
                Add, view, edit, delete and search exchange programs.
            </p>


        </div>



        <!-- =================================================
             MESSAGES
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

                echo htmlspecialchars(
                    $error
                );

                ?>

            </p>


        <?php } ?>



        <?php if ($success != "") { ?>


            <p
                id="success_message"
                style="
                    color:green;
                    text-align:center;
                "
            >

                <?php

                echo htmlspecialchars(
                    $success
                );

                ?>

            </p>


        <?php } ?>



        <!-- =================================================
             ADD / EDIT FORM
             ================================================= -->

        <section class="form_section">


            <h2 id="form_title">
                Add Exchange Program
            </h2>



            <form
                id="program_form"
                method="POST"
                action="controllers/ExchangeProgramController.php"
                onsubmit="return validateProgram();"
                autocomplete="off"
            >


                <table>


                    <!-- ================= PROGRAM ID ================= -->

                    <tr>

                        <td>

                            <label for="program_id">
                                Program ID
                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="program_id"
                                name="program_id"
                                placeholder="Enter program ID"
                            >

                        </td>

                    </tr>



                    <!-- ================= PROGRAM NAME ================= -->

                    <tr>

                        <td>

                            <label for="program_name">
                                Program Name
                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="program_name"
                                name="program_name"
                                placeholder="Enter program name"
                            >

                        </td>

                    </tr>



                    <!-- ================= COUNTRY ================= -->

                    <tr>

                        <td>

                            <label for="country">
                                Country
                            </label>

                        </td>


                        <td>

                            <select
                                id="country"
                                name="country"
                            >

                                <option value="">
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
                                                $country[
                                                    "country_id"
                                                ]
                                            );

                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $country[
                                                "country_name"
                                            ]
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



                    <!-- ================= UNIVERSITY ================= -->

                    <tr>

                        <td>

                            <label for="university">
                                University
                            </label>

                        </td>


                        <td>

                            <select
                                id="university"
                                name="university"
                                disabled
                            >

                                <option value="">
                                    Select country first
                                </option>

                            </select>

                        </td>

                    </tr>



                    <!-- ================= START DATE ================= -->

                    <tr>

                        <td>

                            <label for="start_date">
                                Start Date
                            </label>

                        </td>


                        <td>

                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                            >

                        </td>

                    </tr>



                    <!-- ================= END DATE ================= -->

                    <tr>

                        <td>

                            <label for="end_date">
                                End Date
                            </label>

                        </td>


                        <td>

                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                            >

                        </td>

                    </tr>



                    <!-- ================= DEADLINE ================= -->

                    <tr>

                        <td>

                            <label for="deadline">
                                Application Deadline
                            </label>

                        </td>


                        <td>

                            <input
                                type="date"
                                id="deadline"
                                name="deadline"
                            >

                        </td>

                    </tr>



                    <!-- ================= SEATS ================= -->

                    <tr>

                        <td>

                            <label for="available_seats">
                                Available Seats
                            </label>

                        </td>


                        <td>

                            <input
                                type="number"
                                id="available_seats"
                                name="available_seats"
                                min="1"
                                placeholder="Enter available seats"
                            >

                        </td>

                    </tr>



                    <!-- ================= DESCRIPTION ================= -->

                    <tr>

                        <td>

                            <label for="description">
                                Description
                            </label>

                        </td>


                        <td>

                            <textarea
                                id="description"
                                name="description"
                                placeholder="Enter program description"
                            ></textarea>

                        </td>

                    </tr>



                    <!-- ================= JS ERROR ================= -->

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



                    <!-- ================= BUTTONS ================= -->

                    <tr>

                        <td colspan="2">


                            <div class="button_area">


                                <button
                                    type="submit"
                                    id="submit_button"
                                    name="add_program"
                                    class="save_btn"
                                >

                                    Save

                                </button>



                                <button
                                    type="button"
                                    id="cancel_edit_button"
                                    class="reset_btn"
                                    style="display:none;"
                                    onclick="cancelEdit()"
                                >

                                    Cancel Edit

                                </button>



                                <button
                                    type="reset"
                                    class="reset_btn"
                                    id="reset_button"
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
             PROGRAM LIST
             ================================================= -->

        <section class="list_section">


            <div class="list_header">


                <div>


                    <h2>
                        Exchange Program List
                    </h2>


                    <p>
                        View and manage available exchange programs.
                    </p>


                </div>



                <!-- ================= SEARCH ================= -->

                <div class="search_area">


                    <input
                        type="text"
                        id="search_program"
                        placeholder="Search program, country or university"
                    >


                    <button
                        type="button"
                        onclick="searchProgram()"
                    >

                        Search

                    </button>


                </div>


            </div>



            <!-- ================= TABLE ================= -->

            <div class="table_container">


                <table class="program_table">


                    <thead>


                        <tr>


                            <th>
                                Program ID
                            </th>


                            <th>
                                Program Name
                            </th>


                            <th>
                                Country
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
                                Deadline
                            </th>


                            <th>
                                Seats
                            </th>


                            <th>
                                Action
                            </th>


                        </tr>


                    </thead>



                    <tbody
                        id="program_table_body"
                    >


                    <?php


                    if (
                        $programs &&
                        mysqli_num_rows(
                            $programs
                        ) > 0
                    ) {


                        while (
                            $program =
                            mysqli_fetch_assoc(
                                $programs
                            )
                        ) {


                    ?>


                        <tr>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "program_id"
                                    ]
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "program_name"
                                    ]
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "country_name"
                                    ] ?? ""
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "university_name"
                                    ] ?? ""
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "start_date"
                                    ]
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "end_date"
                                    ]
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "deadline"
                                    ]
                                );

                                ?>

                            </td>



                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $program[
                                        "available_seats"
                                    ]
                                );

                                ?>

                            </td>



                            <!-- ================= ACTION ================= -->

                            <td>


                                <button
                                    type="button"
                                    class="edit_btn"
                                    onclick="editProgram(
                                        <?php

                                        echo (int)
                                            $program[
                                                "program_id"
                                            ];

                                        ?>
                                    )"
                                >

                                    Edit

                                </button>



                                <a
                                    href="controllers/ExchangeProgramController.php?delete=<?php

                                        echo urlencode(
                                            $program[
                                                "program_id"
                                            ]
                                        );

                                    ?>"
                                    class="delete_btn"
                                    onclick="
                                        return confirm(
                                            'Are you sure you want to delete this exchange program?'
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


                        <tr>


                            <td
                                colspan="9"
                                class="empty_data"
                            >

                                No exchange programs available.

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



<?php include "footer.php"; ?>



<script src="manage_exchange_programs.js"></script>



<script>


/* =========================================================
   REMOVE URL MESSAGES
   ========================================================= */

if (window.location.search !== "") {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );

}



/* =========================================================
   HIDE MESSAGES
   ========================================================= */

setTimeout(
    function () {


        let success =
            document.getElementById(
                "success_message"
            );


        let error =
            document.getElementById(
                "php_error"
            );


        if (success) {

            success.style.display =
                "none";

        }


        if (error) {

            error.style.display =
                "none";

        }


    },
    4000
);

</script>


</body>

</html>