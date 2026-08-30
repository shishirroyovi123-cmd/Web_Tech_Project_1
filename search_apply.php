<?php

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/models/ExchangeProgram.php";


/* =========================================================
   CREATE MODEL
   ========================================================= */

$programModel =
    new ExchangeProgram($conn);


/* =========================================================
   VARIABLES
   ========================================================= */

$error = "";
$success = "";

$search = "";
$selectedCountry = "";


/* =========================================================
   GET SEARCH VALUES
   ========================================================= */

if (isset($_GET["search"])) {

    $search =
        trim($_GET["search"]);

}


if (isset($_GET["country"])) {

    $selectedCountry =
        trim($_GET["country"]);

}


/* =========================================================
   GET ALL EXCHANGE PROGRAMS
   ========================================================= */

$programResult =
    $programModel->getAll();


$programs = [];


/* =========================================================
   STORE DATABASE RESULTS
   ========================================================= */

if (
    $programResult &&
    mysqli_num_rows($programResult) > 0
) {

    while (
        $program =
        mysqli_fetch_assoc(
            $programResult
        )
    ) {

        $programs[] =
            $program;

    }

}


/* =========================================================
   FILTER PROGRAMS
   ========================================================= */

if (
    $search !== "" ||
    $selectedCountry !== ""
) {

    $filteredPrograms = [];


    foreach (
        $programs as $program
    ) {


        /* =============================================
           PROGRAM INFORMATION
        ============================================= */

        $programName =
            $program["program_name"]
            ?? "";

        $countryName =
            $program["country_name"]
            ?? "";

        $universityName =
            $program["university_name"]
            ?? "";


        /* =============================================
           SEARCH MATCH
        ============================================= */

        $searchMatch = true;


        if ($search !== "") {

            $searchMatch =

                stripos(
                    $programName,
                    $search
                ) !== false

                ||

                stripos(
                    $countryName,
                    $search
                ) !== false

                ||

                stripos(
                    $universityName,
                    $search
                ) !== false;

        }


        /* =============================================
           COUNTRY MATCH
        ============================================= */

        $countryMatch = true;


        if (
            $selectedCountry !== ""
        ) {

            $countryMatch =

                (
                    (string)
                    (
                        $program["country_id"]
                        ?? ""
                    )

                    ===

                    (string)
                    $selectedCountry
                );

        }


        /* =============================================
           ADD MATCHING PROGRAM
        ============================================= */

        if (
            $searchMatch &&
            $countryMatch
        ) {

            $filteredPrograms[] =
                $program;

        }

    }


    $programs =
        $filteredPrograms;

}


/* =========================================================
   GET COUNTRIES
   ========================================================= */

$countries =

    mysqli_query(
        $conn,

        "
        SELECT
            country_id,
            country_name
        FROM countries
        ORDER BY country_name ASC
        "
    );


/* =========================================================
   GET ERROR MESSAGE
   ========================================================= */

if (
    isset($_GET["error"])
) {

    $error =
        $_GET["error"];

}


/* =========================================================
   GET SUCCESS MESSAGE
   ========================================================= */

if (
    isset($_GET["success"])
) {

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
        SEPMS - Search & Apply
    </title>


    <link
        rel="stylesheet"
        href="search_apply.css"
    >


</head>


<body>


    <!-- =================================================
         HEADER
    ================================================= -->

    <?php include "header.php"; ?>



    <!-- =================================================
         PAGE LAYOUT
    ================================================= -->

    <div class="page_layout">


        <!-- =================================================
             SIDEBAR
        ================================================= -->

        <aside class="sidebar">


            <div class="sidebar_heading">

                STUDENT PANEL

            </div>



            <nav class="sidebar_menu">


                <a
                    href="student_dashboard.php"
                    class="sidebar_item"
                >

                    Dashboard

                </a>



                <a
                    href="search_apply.php"
                    class="sidebar_item active"
                >

                    Search & Apply

                </a>

                <a 
        href="application_form.php" 
        class="sidebar_item" 
    > 

        Apply Program 

    </a>



                <a
                    href="my_applications.php"
                    class="sidebar_item"
                >

                    My Applications

                </a>



                <a
                    href="application_status.php"
                    class="sidebar_item"
                >

                    Application Status

                </a>


            </nav>



            <!-- =================================================
                 SIDEBAR BOTTOM
            ================================================= -->

            <div class="sidebar_bottom">


                <a
                    href="update_profile.php"
                    class="sidebar_item"
                >

                    Update Profile

                </a>



                <a
                    href="change_password.php"
                    class="sidebar_item"
                >

                    Change Password

                </a>



                <a
                    href="login.php"
                    class="sidebar_item logout"
                >

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

            <section class="page_header">


                <h1>

                    Search & Apply for Exchange Programs

                </h1>


                <p>

                    Search available exchange programs
                    and apply for a suitable program.

                </p>


            </section>



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



            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            ></p>



            <!-- =================================================
                 SEARCH SECTION
            ================================================= -->

            <section class="search_box">


                <h2>

                    Search Exchange Programs

                </h2>



                <form
                    method="GET"
                    action="search_apply.php"
                    onsubmit="return validateSearch();"
                >


                    <div class="search_row">


                        <!-- =====================================
                             SEARCH
                        ====================================== -->

                        <div class="search_group">


                            <label for="search">

                                Search

                            </label>


                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="<?php

                                    echo htmlspecialchars(
                                        $search
                                    );

                                ?>"
                                placeholder="Program name, country or university"
                            >


                        </div>



                        <!-- =====================================
                             COUNTRY
                        ====================================== -->

                        <div class="search_group">


                            <label for="country">

                                Country

                            </label>


                            <select
                                id="country"
                                name="country"
                            >


                                <option value="">

                                    All Countries

                                </option>



                                <?php

                                if (
                                    $countries &&
                                    mysqli_num_rows(
                                        $countries
                                    ) > 0
                                ) {

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
                                        <?php

                                        if (
                                            (string)
                                            $selectedCountry

                                            ===

                                            (string)
                                            $country["country_id"]
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
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


                        </div>



                        <!-- =====================================
                             SEARCH BUTTON
                        ====================================== -->

                        <button
                            type="submit"
                            class="search_button"
                        >

                            Search

                        </button>



                        <!-- =====================================
                             CLEAR BUTTON
                        ====================================== -->

                        <?php

                        if (
                            $search !== ""
                            ||
                            $selectedCountry !== ""
                        ) {

                        ?>

                            <a
                                href="search_apply.php"
                                class="search_button"
                                style="
                                    text-decoration:none;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                "
                            >

                                Clear

                            </a>

                        <?php

                        }

                        ?>


                    </div>


                </form>


            </section>



            <!-- =================================================
                 PROGRAM RESULTS
            ================================================= -->

            <section class="program_section">


                <div class="section_header">


                    <h2>

                        Available Exchange Programs

                    </h2>


                    <p>

                        Browse available exchange programs
                        and apply for a suitable program.

                    </p>


                </div>



                <!-- =================================================
                     PROGRAM LIST
                ================================================= -->

                <?php

                if (
                    count($programs) > 0
                ) {

                ?>


                    <?php

                    foreach (
                        $programs as $program
                    ) {

                    ?>


                        <!-- =========================================
                             PROGRAM CARD
                        ========================================== -->

                        <div class="program_card">


                            <!-- =====================================
                                 PROGRAM HEADER
                            ====================================== -->

                            <div class="program_header">


                                <div>


                                    <span class="program_id">

                                        Program ID:

                                        <?php

                                        echo htmlspecialchars(
                                            $program["program_id"]
                                        );

                                        ?>

                                    </span>



                                    <h2>

                                        <?php

                                        echo htmlspecialchars(
                                            $program["program_name"]
                                        );

                                        ?>

                                    </h2>


                                </div>



                                <span class="seat_badge">

                                    <?php

                                    echo htmlspecialchars(
                                        $program["available_seats"]
                                    );

                                    ?>

                                    Seats Available

                                </span>


                            </div>



                            <!-- =====================================
                                 PROGRAM DETAILS
                            ====================================== -->

                            <div class="program_details">


                                <!-- COUNTRY -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Country

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["country_name"]
                                            ?? ""
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- UNIVERSITY -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        University

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["university_name"]
                                            ?? ""
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- START DATE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Start Date

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["start_date"]
                                            ?? ""
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- END DATE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        End Date

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["end_date"]
                                            ?? ""
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- DEADLINE -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Application Deadline

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["deadline"]
                                            ?? ""
                                        );

                                        ?>

                                    </span>


                                </div>



                                <!-- AVAILABLE SEATS -->

                                <div class="detail_item">


                                    <span class="detail_label">

                                        Available Seats

                                    </span>


                                    <span class="detail_value">

                                        <?php

                                        echo htmlspecialchars(
                                            $program["available_seats"]
                                        );

                                        ?>

                                    </span>


                                </div>


                            </div>



                            <!-- =====================================
                                 DESCRIPTION
                            ====================================== -->

                            <div class="description_box">


                                <h3>

                                    Description

                                </h3>


                                <p>

                                    <?php

                                    echo htmlspecialchars(
                                        $program["description"]
                                        ??
                                        "No description available."
                                    );

                                    ?>

                                </p>


                            </div>



                            <!-- =====================================
                                 APPLY BUTTON
                            ====================================== -->

                            <div class="program_action">


                                <a
                                    href="application_form.php?program_id=<?php

                                        echo urlencode(
                                            $program["program_id"]
                                        );

                                    ?>"
                                    class="apply_button"
                                >

                                    Apply Now

                                </a>


                            </div>


                        </div>


                    <?php

                    }

                    ?>


                <?php

                }

                else {

                ?>


                    <!-- =========================================
                         NO PROGRAMS
                    ========================================== -->

                    <div class="empty_programs">


                        <div class="empty_icon">

                            +

                        </div>


                        <h3>

                            No Exchange Programs Available

                        </h3>


                        <p>


                            <?php

                            if (
                                $search !== ""
                                ||
                                $selectedCountry !== ""
                            ) {

                                echo
                                    "No exchange programs match your search.";

                            }

                            else {

                                echo
                                    "No exchange programs have been added yet.";

                            }

                            ?>


                        </p>


                    </div>


                <?php

                }

                ?>


            </section>



            <!-- =================================================
                 FOOTER
            ================================================= -->

            <?php include "footer.php"; ?>


        </main>


    </div>



    <!-- =================================================
         JAVASCRIPT
    ================================================= -->

    <script>


        /* =====================================================
           SEARCH VALIDATION
        ===================================================== */

        function validateSearch() {


            let search =
                document.getElementById(
                    "search"
                ).value.trim();


            let country =
                document.getElementById(
                    "country"
                ).value;


            let error =
                document.getElementById(
                    "js_error"
                );


            /* Clear previous error */

            error.innerHTML = "";

            error.style.display =
                "none";


            /* At least one search option */

            if (
                search === "" &&
                country === ""
            ) {

                error.innerHTML =
                    "Please enter a search term or select a country.";

                error.style.display =
                    "block";

                return false;

            }


            return true;

        }



        /* =====================================================
           CLEAR MESSAGES
        ===================================================== */

        window.addEventListener(
            "load",
            function () {


                let error =
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


                setTimeout(
                    function () {


                        if (phpError) {

                            phpError.style.display =
                                "none";

                        }


                        if (successMessage) {

                            successMessage.style.display =
                                "none";

                        }


                    },
                    5000
                );


            }
        );


    </script>


</body>


</html>