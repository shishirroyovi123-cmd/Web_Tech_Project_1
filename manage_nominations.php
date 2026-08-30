<?php

/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE + CONTROLLER
========================================================= */

require_once __DIR__ . "/config/db.php";

require_once __DIR__ . "/controllers/NominationController.php";


/* =========================================================
   CHECK LOGIN
========================================================= */

if (!isset($_SESSION["user_id"])) {

    header(
        "Location: login.php?error=" .
        urlencode("Please login first.")
    );

    exit();

}


if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "coordinator"
) {

    header(
        "Location: login.php?error=" .
        urlencode("Only coordinators can manage nominations.")
    );

    exit();

}


/* =========================================================
   CREATE CONTROLLER
========================================================= */

$controller =
    new NominationController($conn);


/* =========================================================
   BASIC AJAX - GET NOMINATION DATA FOR EDIT
========================================================= */

if (
    isset($_GET["ajax"]) &&
    $_GET["ajax"] === "get_nomination"
) {

    header("Content-Type: application/json");


    $nominationID =
        isset($_GET["nomination_id"])
        ? (int) $_GET["nomination_id"]
        : 0;


    if ($nominationID <= 0) {

        echo json_encode(
            array(
                "success" => false,
                "message" => "Invalid nomination ID."
            )
        );

        exit();

    }


    $result =
        $controller->getNominationData(
            $nominationID
        );


    echo json_encode($result);

    exit();

}


/* =========================================================
   PROCESS NORMAL FORM REQUEST
========================================================= */

$controller->processRequest();


/* =========================================================
   GET PAGE DATA FROM CONTROLLER
========================================================= */

$data =
    $controller->getViewData();


$error =
    isset($data["error"])
    ? $data["error"]
    : "";


$success =
    isset($data["success"])
    ? $data["success"]
    : "";


$nominations =
    isset($data["nominations"])
    ? $data["nominations"]
    : array();


$nominationCount =
    isset($data["nominationCount"])
    ? $data["nominationCount"]
    : 0;


$approvedApplications =
    isset($data["approvedApplications"])
    ? $data["approvedApplications"]
    : array();


$universitiesList =
    isset($data["universitiesList"])
    ? $data["universitiesList"]
    : array();

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

        SEPMS - Manage Nominations

    </title>


    <link
        rel="stylesheet"
        href="manage_nominations.css"
    >


</head>


<body>


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <?php include "header.php"; ?>


    <!-- =====================================================
         MAIN PAGE LAYOUT
    ====================================================== -->

    <div class="page_layout">


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <aside class="sidebar">


            <div class="sidebar_heading">

                COORDINATOR PANEL

            </div>


            <nav class="sidebar_menu">


                <a
                    href="coordinator_dashboard.php"
                    class="sidebar_item"
                >

                    Dashboard

                </a>


                <a
                    href="review_applications.php"
                    class="sidebar_item"
                >

                    Review Applications

                </a>


                <a
                    href="verify_documents.php"
                    class="sidebar_item"
                >

                    Verify Documents

                </a>


                <a
                    href="manage_nominations.php"
                    class="sidebar_item active"
                >

                    Manage Nominations

                </a>


            </nav>


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
        ================================================== -->

        <main class="main_content">


            <!-- =============================================
                 PAGE HEADER
            ============================================== -->

            <section class="page_header">


                <h1>

                    Manage Nominations

                </h1>


                <p>

                    Create, view, update and manage student
                    nomination status for exchange programs.

                    <br>

                    <span
                        style="color:green;font-size:14px;"
                    >

                        Only

                        <strong>

                            approved applications

                        </strong>

                        (from Verify Documents) can be nominated.

                    </span>

                </p>


            </section>


            <!-- =============================================
                 ERROR MESSAGE
            ============================================== -->

            <?php if ($error !== ""): ?>


                <p
                    style="
                        color:red;
                        text-align:center;
                        background:#ffe6e6;
                        padding:10px;
                        border-radius:5px;
                    "
                >

                    ❌

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </p>


            <?php endif; ?>


            <!-- =============================================
                 SUCCESS MESSAGE
            ============================================== -->

            <?php if ($success !== ""): ?>


                <p
                    style="
                        color:green;
                        text-align:center;
                        background:#e6ffe6;
                        padding:10px;
                        border-radius:5px;
                    "
                >

                    ✓

                    <?php
                    echo htmlspecialchars($success);
                    ?>

                </p>


            <?php endif; ?>


            <!-- =============================================
                 ACTION CARDS
            ============================================== -->

            <section class="action_cards">


                <!-- CREATE CARD -->

                <div class="action_card">


                    <div class="action_icon">

                        +

                    </div>


                    <div class="action_content">


                        <h3>

                            Create Nomination

                        </h3>


                        <p>

                            Create a new nomination for an approved student.

                        </p>


                        <button
                            type="button"
                            class="primary_button"
                            onclick="showSection('create')"
                        >

                            Create Nomination

                        </button>


                    </div>


                </div>


                <!-- VIEW CARD -->

                <div class="action_card">


                    <div class="action_icon">

                        ≡

                    </div>


                    <div class="action_content">


                        <h3>

                            View Nominations

                        </h3>


                        <p>

                            View all nominations created for exchange students.

                        </p>


                        <button
                            type="button"
                            class="secondary_button"
                            onclick="showSection('view')"
                        >

                            View Nominations

                        </button>


                    </div>


                </div>


            </section>


            <!-- =============================================
                 CREATE NOMINATION
            ============================================== -->

            <section
                id="create"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Create Nomination

                        </h2>


                        <p>

                            Select an approved application and create nomination.
                            The host university will be auto-selected.

                        </p>


                    </div>


                </div>


                <form
                    method="POST"
                    action=""
                    onsubmit="return validateCreateNomination();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="create"
                    >


                    <div class="form_grid">


                        <!-- APPROVED APPLICATION -->

                        <div class="form_group">


                            <label for="application_id">

                                Approved Application

                            </label>


                            <select
                                id="application_id"
                                name="application_id"
                                required
                            >


                                <option value="">

                                    Select Approved Application

                                </option>


                                <?php foreach ($approvedApplications as $app): ?>


                                    <option
                                        value="<?php
                                        echo (int) $app["application_id"];
                                        ?>"
                                        data-university-id="<?php
                                        echo (int) $app["program_university_id"];
                                        ?>"
                                        data-university-name="<?php
                                        echo htmlspecialchars(
                                            $app["host_university"]
                                        );
                                        ?>"
                                    >

                                        #
                                        <?php
                                        echo (int) $app["application_id"];
                                        ?>

                                        -

                                        <?php
                                        echo htmlspecialchars(
                                            $app["student_name"]
                                        );
                                        ?>

                                        -

                                        <?php
                                        echo htmlspecialchars(
                                            $app["program_name"]
                                        );
                                        ?>

                                        (

                                        <?php
                                        echo htmlspecialchars(
                                            $app["host_university"]
                                        );
                                        ?>

                                        )

                                    </option>


                                <?php endforeach; ?>


                            </select>


                            <?php if (empty($approvedApplications)): ?>


                                <p
                                    style="
                                        color:#ff5722;
                                        font-size:13px;
                                        margin-top:5px;
                                    "
                                >

                                    No approved applications available.
                                    Please verify documents first.

                                </p>


                            <?php endif; ?>


                        </div>


                        <!-- HOST UNIVERSITY -->

                        <div class="form_group">


                            <label for="university_id">

                                Host University

                            </label>


                            <select
                                id="university_id"
                                name="university_id"
                                required
                            >


                                <option value="">

                                    Auto-selected from application

                                </option>


                                <?php foreach ($universitiesList as $uni): ?>


                                    <option
                                        value="<?php
                                        echo (int) $uni["university_id"];
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $uni["university_name"]
                                        );
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                            <p
                                style="
                                    font-size:12px;
                                    color:#888;
                                    margin-top:3px;
                                "
                            >

                                University will be auto-selected when
                                you choose an application.

                            </p>


                        </div>


                        <!-- NOMINATION DATE -->

                        <div class="form_group">


                            <label for="nomination_date">

                                Nomination Date

                            </label>


                            <input
                                type="date"
                                id="nomination_date"
                                name="nomination_date"
                                value="<?php echo date("Y-m-d"); ?>"
                                required
                            >


                        </div>


                        <!-- STATUS -->

                        <div class="form_group">


                            <label for="nomination_status">

                                Status

                            </label>


                            <select
                                id="nomination_status"
                                name="nomination_status"
                                required
                            >


                                <option value="">

                                    Select Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option
                                    value="nominated"
                                    selected
                                >

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>


                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                            <?php
                            echo empty($approvedApplications)
                                ? "disabled"
                                : "";
                            ?>
                        >

                            Create Nomination

                        </button>


                        <button
                            type="reset"
                            class="clear_button"
                        >

                            Clear

                        </button>


                    </div>


                </form>


            </section>


            <!-- =============================================
                 VIEW NOMINATIONS
            ============================================== -->

            <section
                id="view"
                class="content_box"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            View Nominations

                        </h2>


                        <p>

                            Nominations retrieved from the database.

                        </p>


                    </div>


                    <div class="nomination_count">

                        Total:

                        <strong>

                            <?php echo $nominationCount; ?>

                        </strong>

                    </div>


                </div>


                <!-- SEARCH -->

                <div class="search_area">


                    <div class="search_group">


                        <label for="search_nomination">

                            Search

                        </label>


                        <input
                            type="text"
                            id="search_nomination"
                            placeholder="Search..."
                            onkeyup="searchNomination()"
                        >


                    </div>


                    <div class="search_group">


                        <label for="filter_status">

                            Status

                        </label>


                        <select
                            id="filter_status"
                            onchange="searchNomination()"
                        >


                            <option value="">

                                All Status

                            </option>


                            <option value="pending">

                                Pending

                            </option>


                            <option value="nominated">

                                Nominated

                            </option>


                            <option value="accepted">

                                Accepted

                            </option>


                            <option value="rejected">

                                Rejected

                            </option>


                            <option value="withdrawn">

                                Withdrawn

                            </option>


                        </select>


                    </div>


                    <button
                        type="button"
                        class="search_button"
                        onclick="searchNomination()"
                    >

                        Search

                    </button>


                </div>


                <!-- TABLE -->

                <div class="table_container">


                    <table class="nomination_table">


                        <thead>


                            <tr>

                                <th>Nomination ID</th>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Program</th>
                                <th>University</th>
                                <th>Country</th>
                                <th>Nomination Date</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>


                        </thead>


                        <tbody id="nomination_table_body">


                            <?php if (!empty($nominations)): ?>


                                <?php foreach ($nominations as $nom): ?>


                                    <tr>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["nomination_id"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["application_id"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["student_name"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["program_name"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["university_name"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $nom["country_name"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo date(
                                                "Y-m-d",
                                                strtotime(
                                                    $nom["nomination_date"]
                                                )
                                            );
                                            ?>

                                        </td>


                                        <td>


                                            <?php

                                            $statusClass = "";
                                            $statusDisplay = "";


                                            if (
                                                $nom["nomination_status"] === "pending"
                                            ) {

                                                $statusClass =
                                                    "status_pending";

                                                $statusDisplay =
                                                    "Pending";

                                            }

                                            elseif (
                                                $nom["nomination_status"] === "nominated"
                                            ) {

                                                $statusClass =
                                                    "status_nominated";

                                                $statusDisplay =
                                                    "Nominated";

                                            }

                                            elseif (
                                                $nom["nomination_status"] === "accepted"
                                            ) {

                                                $statusClass =
                                                    "status_accepted";

                                                $statusDisplay =
                                                    "Accepted";

                                            }

                                            elseif (
                                                $nom["nomination_status"] === "rejected"
                                            ) {

                                                $statusClass =
                                                    "status_rejected";

                                                $statusDisplay =
                                                    "Rejected";

                                            }

                                            elseif (
                                                $nom["nomination_status"] === "withdrawn"
                                            ) {

                                                $statusClass =
                                                    "status_withdrawn";

                                                $statusDisplay =
                                                    "Withdrawn";

                                            }

                                            else {

                                                $statusClass =
                                                    "status_pending";

                                                $statusDisplay =
                                                    ucfirst(
                                                        $nom["nomination_status"]
                                                    );

                                            }

                                            ?>


                                            <span
                                                class="status_badge <?php
                                                echo $statusClass;
                                                ?>"
                                            >

                                                <?php
                                                echo $statusDisplay;
                                                ?>

                                            </span>


                                        </td>


                                        <td>


                                            <div class="action_buttons">


                                                <button
                                                    type="button"
                                                    class="edit_btn"
                                                    onclick="editNomination(<?php
                                                    echo (int)
                                                    $nom["nomination_id"];
                                                    ?>)"
                                                >

                                                    Edit

                                                </button>


                                                <button
                                                    type="button"
                                                    class="status_btn"
                                                    onclick="updateStatus(
                                                        <?php
                                                        echo (int)
                                                        $nom["nomination_id"];
                                                        ?>,
                                                        '<?php
                                                        echo htmlspecialchars(
                                                            $nom["nomination_status"]
                                                        );
                                                        ?>'
                                                    )"
                                                >

                                                    Status

                                                </button>


                                                <form
                                                    method="POST"
                                                    style="display:inline;"
                                                >


                                                    <input
                                                        type="hidden"
                                                        name="form_type"
                                                        value="delete"
                                                    >


                                                    <input
                                                        type="hidden"
                                                        name="delete_nomination_id"
                                                        value="<?php
                                                        echo (int)
                                                        $nom["nomination_id"];
                                                        ?>"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="delete_btn"
                                                        onclick="return confirm('Delete this nomination?')"
                                                    >

                                                        Delete

                                                    </button>


                                                </form>


                                            </div>


                                        </td>


                                    </tr>


                                <?php endforeach; ?>


                            <?php else: ?>


                                <tr>


                                    <td
                                        colspan="9"
                                        class="empty_data"
                                    >

                                        No nominations available.

                                    </td>


                                </tr>


                            <?php endif; ?>


                        </tbody>


                    </table>


                </div>


            </section>


            <!-- =============================================
                 UPDATE NOMINATION
            ============================================== -->

            <section
                id="update"
                class="content_box"
                style="display:none;"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Update Nomination

                        </h2>


                        <p>

                            Update information for an existing nomination.

                        </p>


                    </div>


                </div>


                <form
                    method="POST"
                    action=""
                    onsubmit="return validateUpdateNomination();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="update"
                    >


                    <div class="form_grid">


                        <div class="form_group">


                            <label for="update_nomination_id">

                                Nomination ID

                            </label>


                            <input
                                type="text"
                                id="update_nomination_id"
                                name="update_nomination_id"
                                readonly
                                required
                            >


                        </div>


                        <div class="form_group">


                            <label for="update_application_id">

                                Application ID

                            </label>


                            <input
                                type="text"
                                id="update_application_id"
                                name="update_application_id"
                                placeholder="Application ID"
                                required
                            >


                        </div>


                        <div class="form_group">


                            <label for="update_university_id">

                                Host University

                            </label>


                            <select
                                id="update_university_id"
                                name="update_university_id"
                                required
                            >


                                <option value="">

                                    Select University

                                </option>


                                <?php foreach ($universitiesList as $uni): ?>


                                    <option
                                        value="<?php
                                        echo (int)
                                        $uni["university_id"];
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $uni["university_name"]
                                        );
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>


                        <div class="form_group">


                            <label for="update_date">

                                Nomination Date

                            </label>


                            <input
                                type="date"
                                id="update_date"
                                name="update_date"
                                required
                            >


                        </div>


                        <div class="form_group">


                            <label for="update_status">

                                Nomination Status

                            </label>


                            <select
                                id="update_status"
                                name="update_status"
                                required
                            >


                                <option value="">

                                    Select Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>


                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                        >

                            Update Nomination

                        </button>


                        <button
                            type="button"
                            class="clear_button"
                            onclick="cancelEdit()"
                        >

                            Cancel

                        </button>


                    </div>


                </form>


            </section>


            <!-- =============================================
                 UPDATE STATUS
            ============================================== -->

            <section
                id="status"
                class="content_box"
                style="display:none;"
            >


                <div class="section_header">


                    <div>


                        <h2>

                            Update Nomination Status

                        </h2>


                        <p>

                            Change the current status of a nomination.

                        </p>


                    </div>


                </div>


                <form
                    method="POST"
                    action=""
                    onsubmit="return validateStatus();"
                >


                    <input
                        type="hidden"
                        name="form_type"
                        value="status"
                    >


                    <div class="status_form_grid">


                        <div class="form_group">


                            <label for="status_nomination_id">

                                Nomination ID

                            </label>


                            <input
                                type="text"
                                id="status_nomination_id"
                                name="status_nomination_id"
                                readonly
                                required
                            >


                        </div>


                        <div class="form_group">


                            <label for="current_status">

                                Current Status

                            </label>


                            <input
                                type="text"
                                id="current_status"
                                name="current_status"
                                readonly
                                required
                            >


                        </div>


                        <div class="form_group">


                            <label for="new_status">

                                New Status

                            </label>


                            <select
                                id="new_status"
                                name="new_status"
                                required
                            >


                                <option value="">

                                    Select New Status

                                </option>


                                <option value="pending">

                                    Pending

                                </option>


                                <option value="nominated">

                                    Nominated

                                </option>


                                <option value="accepted">

                                    Accepted

                                </option>


                                <option value="rejected">

                                    Rejected

                                </option>


                                <option value="withdrawn">

                                    Withdrawn

                                </option>


                            </select>


                        </div>


                    </div>


                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="primary_button"
                        >

                            Update Status

                        </button>


                        <button
                            type="button"
                            class="clear_button"
                            onclick="cancelStatus()"
                        >

                            Cancel

                        </button>


                    </div>


                </form>


            </section>


        </main>


    </div>


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <?php include "footer.php"; ?>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script
        src="assets/js/manage_nominations.js"
    ></script>


    <!-- =====================================================
         BASIC AUTO SELECT UNIVERSITY
    ====================================================== -->

    <script>


        document.addEventListener(
            "DOMContentLoaded",
            function () {


                var appSelect =
                    document.getElementById(
                        "application_id"
                    );


                var uniSelect =
                    document.getElementById(
                        "university_id"
                    );


                if (
                    appSelect &&
                    uniSelect
                ) {


                    appSelect.onchange =
                        function () {


                            var selectedOption =
                                appSelect.options[
                                    appSelect.selectedIndex
                                ];


                            var universityID =
                                selectedOption.getAttribute(
                                    "data-university-id"
                                );


                            if (
                                universityID !== null &&
                                universityID !== ""
                            ) {


                                uniSelect.value =
                                    universityID;


                            }


                        };


                }


            }
        );


    </script>


</body>


</html>