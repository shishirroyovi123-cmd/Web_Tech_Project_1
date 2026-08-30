<?php

/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE
========================================================= */

require_once __DIR__ . "/config/db.php";


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


/* =========================================================
   CHECK COORDINATOR ROLE
========================================================= */

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "coordinator"
) {

    header(
        "Location: login.php?error=" .
        urlencode("Only coordinators can review applications.")
    );

    exit();

}


/* =========================================================
   INITIALIZE VARIABLES
========================================================= */

$error = "";
$success = "";
$applications = null;
$selectedApplication = null;
$applicationCount = 0;
$pendingDocuments = [];


/* =========================================================
   GET SEARCH/FILTER PARAMETERS
========================================================= */

$applicationID = isset($_GET["application_id"]) ? trim($_GET["application_id"]) : "";
$studentSearch = isset($_GET["student"]) ? trim($_GET["student"]) : "";
$programFilter = isset($_GET["program"]) ? trim($_GET["program"]) : "";
$countryFilter = isset($_GET["country"]) ? trim($_GET["country"]) : "";
$statusFilter = isset($_GET["status"]) ? trim($_GET["status"]) : "";
$selectedAppID = isset($_GET["view"]) ? (int) $_GET["view"] : 0;


/* =========================================================
   BUILD SEARCH/FILTER QUERY
   ========================================================= */

$sql = "
    SELECT 
        a.application_id,
        a.application_date,
        a.status,
        a.department,
        a.semester,
        a.cgpa,
        a.study_term,
        a.statement_of_purpose,
        a.declaration,
        u.user_id AS student_id,
        u.name AS student_name,
        u.email AS student_email,
        u.username AS student_username,
        ep.program_id,
        ep.program_name,
        ep.start_date AS program_start_date,
        ep.end_date AS program_end_date,
        ep.deadline AS program_deadline,
        ep.available_seats,
        ep.description AS program_description,
        c.country_id,
        c.country_name AS host_country,
        univ.university_id,
        univ.university_name AS host_university
    FROM applications a
    INNER JOIN users u ON a.student_id = u.user_id
    INNER JOIN exchange_programs ep ON a.program_id = ep.program_id
    INNER JOIN countries c ON ep.country_id = c.country_id
    INNER JOIN universities univ ON ep.university_id = univ.university_id
    WHERE 1=1
";


/* =========================================================
   APPLY FILTERS
   ========================================================= */

if ($applicationID !== "") {
    $sql .= " AND a.application_id = " . (int) $applicationID;
}

if ($studentSearch !== "") {
    $sql .= " AND (u.name LIKE '%" . mysqli_real_escape_string($conn, $studentSearch) . "%' ";
    $sql .= " OR u.user_id LIKE '%" . mysqli_real_escape_string($conn, $studentSearch) . "%')";
}

if ($programFilter !== "") {
    $sql .= " AND ep.program_id = " . (int) $programFilter;
}

if ($countryFilter !== "") {
    $sql .= " AND ep.country_id = " . (int) $countryFilter;
}

if ($statusFilter !== "") {
    $sql .= " AND a.status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}

$sql .= " ORDER BY a.application_id DESC";


/* =========================================================
   EXECUTE QUERY
   ========================================================= */

$result = mysqli_query($conn, $sql);

if ($result) {
    $applications = $result;
    $applicationCount = mysqli_num_rows($result);
} else {
    $error = "Database error: " . mysqli_error($conn);
}


/* =========================================================
   GET SELECTED APPLICATION DETAILS & PENDING DOCUMENTS
   ========================================================= */

if ($selectedAppID > 0 && $applications) {
    mysqli_data_seek($applications, 0);
    
    while ($row = mysqli_fetch_assoc($applications)) {
        if ((int) $row["application_id"] === $selectedAppID) {
            $selectedApplication = $row;
            break;
        }
    }
    
    mysqli_data_seek($applications, 0);
    
    // Get PENDING documents for this application
    if ($selectedApplication) {
        $docSQL = "
            SELECT 
                document_id,
                document_type,
                file_name,
                file_path,
                upload_date,
                verification_status
            FROM documents 
            WHERE application_id = " . (int) $selectedAppID . "
            AND verification_status = 'Pending'
            ORDER BY upload_date DESC
        ";
        $docResult = mysqli_query($conn, $docSQL);
        if ($docResult) {
            while ($doc = mysqli_fetch_assoc($docResult)) {
                $pendingDocuments[] = $doc;
            }
        }
    }
}


/* =========================================================
   GET EXCHANGE PROGRAMS FOR FILTER DROPDOWN
   ========================================================= */

$programSQL = "
    SELECT 
        ep.program_id, 
        ep.program_name, 
        c.country_name,
        univ.university_name
    FROM exchange_programs ep
    INNER JOIN countries c ON ep.country_id = c.country_id
    INNER JOIN universities univ ON ep.university_id = univ.university_id
    ORDER BY ep.program_name ASC
";

$programResult = mysqli_query($conn, $programSQL);
$programsList = [];
if ($programResult) {
    while ($row = mysqli_fetch_assoc($programResult)) {
        $programsList[] = $row;
    }
}


/* =========================================================
   GET COUNTRIES FOR FILTER DROPDOWN
   ========================================================= */

$countrySQL = "
    SELECT country_id, country_name
    FROM countries
    ORDER BY country_name ASC
";

$countryResult = mysqli_query($conn, $countrySQL);
$countriesList = [];
if ($countryResult) {
    while ($row = mysqli_fetch_assoc($countryResult)) {
        $countriesList[] = $row;
    }
}


/* =========================================================
   GET MESSAGES FROM URL
   ========================================================= */

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

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Review Applications</title>

    <link rel="stylesheet"
          href="review_applications.css">

    <script src="review_applications.js"></script>

</head>


<body>


    <!-- =====================================================
                         HEADER
    ====================================================== -->

   <?php include "header.php"; ?>



    <!-- =====================================================
                       PAGE LAYOUT
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
                    class="sidebar_item active"
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
                    class="sidebar_item"
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



            <!-- =================================================
                         PAGE HEADER
            ================================================== -->

            <section class="page_header">


                <h1>

                    Review Applications

                </h1>


                <p>

                    View, search, filter and review student
                    exchange applications.

                </p>


            </section>



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

                    " . htmlspecialchars($error) . "

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

                    " . htmlspecialchars($success) . "

                </p>

                ";

            }

            ?>



            <!-- =================================================
                      SEARCH & FILTER
            ================================================== -->

            <section class="content_box">


                <h2>

                    Search & Filter Applications

                </h2>


                <p class="section_description">

                    Search for a specific application or use the
                    filters to find applications.

                </p>



                <form
                    method="GET"
                    action="review_applications.php"
                    onsubmit="return validateSearch();"
                    id="searchForm"
                >


                    <div class="filter_row">



                        <!-- APPLICATION ID -->

                        <div class="filter_group">


                            <label for="application_id">

                                Application ID

                            </label>


                            <input
                                type="text"
                                id="application_id"
                                name="application_id"
                                placeholder="Enter Application ID"
                                value="<?php echo htmlspecialchars($applicationID); ?>"
                            >


                        </div>



                        <!-- STUDENT -->

                        <div class="filter_group">


                            <label for="student">

                                Student

                            </label>


                            <input
                                type="text"
                                id="student"
                                name="student"
                                placeholder="Student ID or Name"
                                value="<?php echo htmlspecialchars($studentSearch); ?>"
                            >


                        </div>



                        <!-- PROGRAM -->

                        <div class="filter_group">


                            <label for="program">

                                Exchange Program

                            </label>


                            <select
                                id="program"
                                name="program"
                            >


                                <option value="">

                                    All Programs

                                </option>


                                <?php foreach ($programsList as $prog) { ?>

                                    <option 
                                        value="<?php echo (int) $prog["program_id"]; ?>"
                                        <?php echo ($programFilter == $prog["program_id"]) ? "selected" : ""; ?>
                                    >

                                        <?php 
                                        echo htmlspecialchars(
                                            $prog["program_name"] . " (" . $prog["country_name"] . ")"
                                        ); 
                                        ?>

                                    </option>

                                <?php } ?>


                            </select>


                        </div>



                        <!-- COUNTRY -->

                        <div class="filter_group">


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


                                <?php foreach ($countriesList as $country) { ?>

                                    <option 
                                        value="<?php echo (int) $country["country_id"]; ?>"
                                        <?php echo ($countryFilter == $country["country_id"]) ? "selected" : ""; ?>
                                    >

                                        <?php echo htmlspecialchars($country["country_name"]); ?>

                                    </option>

                                <?php } ?>


                            </select>


                        </div>



                        <!-- STATUS -->

                        <div class="filter_group">


                            <label for="status">

                                Application Status

                            </label>


                            <select
                                id="status"
                                name="status"
                            >


                                <option value="">

                                    All Status

                                </option>


                                <option 
                                    value="Pending"
                                    <?php echo ($statusFilter == "Pending") ? "selected" : ""; ?>
                                >

                                    Pending

                                </option>


                                <option 
                                    value="Under Review"
                                    <?php echo ($statusFilter == "Under Review") ? "selected" : ""; ?>
                                >

                                    Under Review

                                </option>


                                <option 
                                    value="Approved"
                                    <?php echo ($statusFilter == "Approved") ? "selected" : ""; ?>
                                >

                                    Approved

                                </option>


                                <option 
                                    value="Rejected"
                                    <?php echo ($statusFilter == "Rejected") ? "selected" : ""; ?>
                                >

                                    Rejected

                                </option>


                            </select>


                        </div>


                    </div>



                    <div class="filter_buttons">


                        <button
                            type="submit"
                            class="search_button"
                        >

                            Search

                        </button>


                        <button
                            type="reset"
                            class="clear_button"
                            onclick="clearFilters();"
                        >

                            Clear

                        </button>


                    </div>


                </form>


            </section>



            <!-- =================================================
                       APPLICATION LIST
            ================================================== -->

            <section class="content_box">


                <div class="section_header">


                    <div>


                        <h2>

                            Applications

                        </h2>


                        <p>

                            Student applications retrieved from
                            the database will appear here.

                        </p>


                    </div>


                    <div class="application_count">

                        Total Applications:

                        <strong>

                            <?php echo $applicationCount; ?>

                        </strong>

                    </div>


                </div>



                <div class="table_container">


                    <table class="application_table">


                        <thead>


                            <tr>


                                <th>

                                    Application ID

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

                                    Country

                                </th>


                                <th>

                                    Submitted Date

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


                            <?php if ($applications && mysqli_num_rows($applications) > 0) { ?>

                                <?php 
                                mysqli_data_seek($applications, 0);
                                while ($app = mysqli_fetch_assoc($applications)) { 
                                ?>

                                    <tr>

                                        <td>
                                            <?php echo htmlspecialchars($app["application_id"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($app["student_id"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($app["student_name"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($app["program_name"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($app["host_university"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($app["host_country"]); ?>
                                        </td>

                                        <td>
                                            <?php echo date("Y-m-d", strtotime($app["application_date"])); ?>
                                        </td>

                                        <td>

                                            <?php 
                                            $statusClass = "";
                                            $statusDisplay = "";

                                            switch ($app["status"]) {
                                                case "Pending":
                                                    $statusClass = "status_pending";
                                                    $statusDisplay = "Pending";
                                                    break;
                                                case "Under Review":
                                                    $statusClass = "status_under_review";
                                                    $statusDisplay = "Under Review";
                                                    break;
                                                case "Approved":
                                                    $statusClass = "status_approved";
                                                    $statusDisplay = "Approved";
                                                    break;
                                                case "Rejected":
                                                    $statusClass = "status_rejected";
                                                    $statusDisplay = "Rejected";
                                                    break;
                                                default:
                                                    $statusClass = "status_pending";
                                                    $statusDisplay = ucfirst($app["status"]);
                                            }
                                            ?>

                                            <span class="status_badge <?php echo $statusClass; ?>">
                                                <?php echo $statusDisplay; ?>
                                            </span>

                                        </td>

                                        <td>

                                            <a 
                                                href="review_applications.php?view=<?php echo (int) $app["application_id"]; ?><?php 
                                                    $params = [];
                                                    if ($applicationID) $params[] = "application_id=" . urlencode($applicationID);
                                                    if ($studentSearch) $params[] = "student=" . urlencode($studentSearch);
                                                    if ($programFilter) $params[] = "program=" . urlencode($programFilter);
                                                    if ($countryFilter) $params[] = "country=" . urlencode($countryFilter);
                                                    if ($statusFilter) $params[] = "status=" . urlencode($statusFilter);
                                                    if (!empty($params)) echo "&" . implode("&", $params);
                                                ?>"
                                                class="view_btn"
                                            >

                                                View Details

                                            </a>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>

                                    <td colspan="9" class="empty_data">

                                        <?php 
                                        if ($applicationID || $studentSearch || $programFilter || $countryFilter || $statusFilter) {
                                            echo "No applications found matching your search criteria.";
                                        } else {
                                            echo "No applications available.";
                                        }
                                        ?>

                                    </td>

                                </tr>

                            <?php } ?>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- =================================================
                    SELECTED APPLICATION DETAILS
            ================================================== -->

            <?php if ($selectedApplication) { ?>

            <section class="content_box">


                <div class="section_header">


                    <div>


                        <h2>

                            Application Details

                        </h2>


                        <p>

                            Complete information for Application #<?php echo (int) $selectedApplication["application_id"]; ?>

                        </p>


                    </div>


                </div>



                <!-- =================================================
                         APPLICATION INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Application Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Application ID

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["application_id"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Date

                            </span>


                            <span class="information_value">

                                <?php echo date("F d, Y", strtotime($selectedApplication["application_date"])); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Status

                            </span>


                            <span class="status_badge <?php 
                                $statusClass = "";
                                switch ($selectedApplication["status"]) {
                                    case "Pending": $statusClass = "status_pending"; break;
                                    case "Under Review": $statusClass = "status_under_review"; break;
                                    case "Approved": $statusClass = "status_approved"; break;
                                    case "Rejected": $statusClass = "status_rejected"; break;
                                    default: $statusClass = "status_pending";
                                }
                                echo $statusClass; 
                            ?>">

                                <?php 
                                switch ($selectedApplication["status"]) {
                                    case "Pending": echo "Pending"; break;
                                    case "Under Review": echo "Under Review"; break;
                                    case "Approved": echo "Approved"; break;
                                    case "Rejected": echo "Rejected"; break;
                                    default: echo ucfirst($selectedApplication["status"]);
                                }
                                ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Department

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["department"] ?? "—"); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Semester

                            </span>


                            <span class="information_value">

                                <?php 
                                $sem = $selectedApplication["semester"] ?? "";
                                echo $sem ? "Semester " . htmlspecialchars($sem) : "—"; 
                                ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                CGPA

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["cgpa"] ?? "—"); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Study Term

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["study_term"] ?? "—"); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Declaration

                            </span>


                            <span class="information_value">

                                <?php echo ($selectedApplication["declaration"] == 1) ? "✅ Confirmed" : "❌ Not Confirmed"; ?>

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                         STUDENT INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Student Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Student ID

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["student_id"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Full Name

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["student_name"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Email

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["student_email"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Username

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["student_username"]); ?>

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                     EXCHANGE PROGRAM INFORMATION
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Exchange Program Information

                    </h3>



                    <div class="information_grid">


                        <div class="information_item">


                            <span class="information_label">

                                Program ID

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["program_id"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program Name

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["program_name"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Host Country

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["host_country"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Host University

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["host_university"]); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program Start Date

                            </span>


                            <span class="information_value">

                                <?php echo date("F d, Y", strtotime($selectedApplication["program_start_date"])); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Program End Date

                            </span>


                            <span class="information_value">

                                <?php echo date("F d, Y", strtotime($selectedApplication["program_end_date"])); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Application Deadline

                            </span>


                            <span class="information_value">

                                <?php echo date("F d, Y", strtotime($selectedApplication["program_deadline"])); ?>

                            </span>


                        </div>



                        <div class="information_item">


                            <span class="information_label">

                                Available Seats

                            </span>


                            <span class="information_value">

                                <?php echo htmlspecialchars($selectedApplication["available_seats"]); ?>

                            </span>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                       STATEMENT OF PURPOSE
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Statement of Purpose

                    </h3>


                    <div class="text_box">

                        <?php 
                        $sop = $selectedApplication["statement_of_purpose"] ?? "";
                        echo $sop ? nl2br(htmlspecialchars($sop)) : "No statement of purpose available.";
                        ?>

                    </div>


                </div>



                <!-- =================================================
                       PENDING DOCUMENTS - ONLY SHOW PENDING
                ================================================== -->

                <div class="details_section">


                    <h3>

                        Pending Documents

                    </h3>


                    <?php if (!empty($pendingDocuments)) { ?>

                        <div class="table_container">

                            <table class="application_table">

                                <thead>

                                    <tr>

                                        <th>Document ID</th>
                                        <th>Document Type</th>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($pendingDocuments as $doc) { ?>

                                        <tr>

                                            <td>
                                                <?php echo htmlspecialchars($doc["document_id"]); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars($doc["document_type"]); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars($doc["file_name"]); ?>
                                            </td>

                                            <td>
                                                <?php echo date("Y-m-d H:i", strtotime($doc["upload_date"])); ?>
                                            </td>

                                            <td>

                                                <span class="status_badge status_pending">
                                                    <?php echo htmlspecialchars($doc["verification_status"]); ?>
                                                </span>

                                            </td>

                                            <td>

                                                <a 
                                                    href="<?php echo htmlspecialchars($doc["file_path"]); ?>" 
                                                    target="_blank"
                                                    class="view_btn"
                                                >

                                                    View File

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    <?php } else { ?>

                        <div class="text_box">

                            No pending documents for this application.

                        </div>

                    <?php } ?>


                </div>



                <!-- =================================================
                       REVIEW INFORMATION
                ================================================== -->

                <div class="details_section review_notice">


                    <div class="notice_icon">

                        i

                    </div>


                    <div>


                        <strong>

                            Application Review

                        </strong>


                        <p>

                            This page is for reviewing application
                            information only. Document verification
                            and application approval or rejection
                            are handled separately through
                            <b>Verify Documents</b>.
                            <br><br>
                            <a 
                                href="verify_documents.php?application=<?php echo (int) $selectedApplication["application_id"]; ?>"
                                class="verify_link"
                            >
                                → Go to Verify Documents for this application
                            </a>

                        </p>


                    </div>


                </div>


            </section>

            <?php } else if ($applicationCount > 0) { ?>

            <section class="content_box">

                <div class="details_section review_notice" style="text-align:center;">

                    <div>


                        <strong>

                            Select an Application

                        </strong>


                        <p>

                            Click the <b>"View Details"</b> button on any application in the table above
                            to see complete application information.

                        </p>


                    </div>

                </div>

            </section>

            <?php } ?>


            <!-- ================= FOOTER ================= -->

           <?php include "footer.php"; ?>

        </main>


    </div>



    <!-- ================= CLEAR URL ================= -->

    <script>


        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        let clearButton =
            document.querySelector(".clear_button");


        clearButton.addEventListener("click", function () {


            let error =
                document.getElementById("js_error");


            let phpError =
                document.getElementById("php_error");


            let successMessage =
                document.getElementById("success_message");


            error.innerHTML = "";

            error.style.display = "none";


            if (phpError) {

                phpError.remove();

            }


            if (successMessage) {

                successMessage.remove();

            }

        });


        function clearFilters() {

            document.getElementById("application_id").value = "";
            document.getElementById("student").value = "";
            document.getElementById("program").value = "";
            document.getElementById("country").value = "";
            document.getElementById("status").value = "";

            window.location.href = "review_applications.php";

        }


        function validateSearch() {

            var appId = document.getElementById("application_id").value;
            
            if (appId !== "" && isNaN(appId)) {
                
                var errorEl = document.getElementById("js_error");
                errorEl.innerHTML = "Application ID must contain numbers only.";
                errorEl.style.display = "block";
                
                return false;
                
            }
            
            return true;

        }


    </script>


</body>

</html>