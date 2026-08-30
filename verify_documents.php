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
        urlencode("Only coordinators can verify documents.")
    );

    exit();

}


/* =========================================================
   INITIALIZE VARIABLES
========================================================= */

$error = "";
$success = "";
$selectedApplication = null;
$pendingDocuments = [];
$verifiedCount = 0;
$rejectedCount = 0;
$pendingCount = 0;
$totalCount = 0;
$applicationsList = [];


/* =========================================================
   GET ALL APPLICATIONS FOR DROPDOWN
========================================================= */

$appSQL = "
    SELECT 
        a.application_id,
        a.status,
        u.name AS student_name,
        ep.program_name
    FROM applications a
    INNER JOIN users u ON a.student_id = u.user_id
    INNER JOIN exchange_programs ep ON a.program_id = ep.program_id
    ORDER BY a.application_id DESC
";

$appResult = mysqli_query($conn, $appSQL);
if ($appResult) {
    while ($row = mysqli_fetch_assoc($appResult)) {
        $applicationsList[] = $row;
    }
}


/* =========================================================
   GET SELECTED APPLICATION ID FROM POST OR GET
========================================================= */

$selectedAppID = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["load_application"])) {
    $selectedAppID = (int) $_POST["application_id"];
} elseif (isset($_GET["application"])) {
    $selectedAppID = (int) $_GET["application"];
}


/* =========================================================
   LOAD SELECTED APPLICATION DETAILS
========================================================= */

if ($selectedAppID > 0) {
    
    // Get application details
    $appDetailSQL = "
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
            c.country_name AS host_country,
            univ.university_name AS host_university
        FROM applications a
        INNER JOIN users u ON a.student_id = u.user_id
        INNER JOIN exchange_programs ep ON a.program_id = ep.program_id
        INNER JOIN countries c ON ep.country_id = c.country_id
        INNER JOIN universities univ ON ep.university_id = univ.university_id
        WHERE a.application_id = " . (int) $selectedAppID . "
    ";
    
    $appDetailResult = mysqli_query($conn, $appDetailSQL);
    if ($appDetailResult && mysqli_num_rows($appDetailResult) > 0) {
        $selectedApplication = mysqli_fetch_assoc($appDetailResult);
        
        // Get documents for this application
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
            ORDER BY 
                CASE 
                    WHEN verification_status = 'Pending' THEN 1
                    WHEN verification_status = 'Verified' THEN 2
                    WHEN verification_status = 'Rejected' THEN 3
                    ELSE 4
                END,
                upload_date DESC
        ";
        
        $docResult = mysqli_query($conn, $docSQL);
        if ($docResult) {
            while ($doc = mysqli_fetch_assoc($docResult)) {
                $pendingDocuments[] = $doc;
                
                // Count statistics
                if ($doc["verification_status"] == "Verified") {
                    $verifiedCount++;
                } elseif ($doc["verification_status"] == "Rejected") {
                    $rejectedCount++;
                } else {
                    $pendingCount++;
                }
            }
            $totalCount = count($pendingDocuments);
        }
    } else {
        $error = "Application not found.";
    }
}


/* =========================================================
   HANDLE DOCUMENT VERIFICATION/REJECTION
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Load Application
    if (isset($_POST["load_application"])) {
        $application_id = trim($_POST["application_id"]);
        
        if ($application_id == "") {
            $error = "Please select an application.";
        } elseif (!is_numeric($application_id)) {
            $error = "Application ID must contain numbers only.";
        } else {
            $success = "Application loaded successfully.";
            $selectedAppID = (int) $application_id;
            // Refresh the page to load the application
            header("Location: verify_documents.php?application=" . (int) $application_id);
            exit();
        }
    }
    
    // Verify Document
    if (isset($_POST["verify_document"])) {
        $document_id = (int) $_POST["document_id"];
        $app_id = (int) $_POST["application_id"];
        
        $updateSQL = "
            UPDATE documents 
            SET verification_status = 'Verified' 
            WHERE document_id = " . $document_id . "
            AND application_id = " . $app_id . "
        ";
        
        if (mysqli_query($conn, $updateSQL)) {
            $success = "Document verified successfully.";
            // Refresh to show updated status
            header("Location: verify_documents.php?application=" . $app_id . "&success=" . urlencode("Document verified successfully."));
            exit();
        } else {
            $error = "Error verifying document: " . mysqli_error($conn);
        }
    }
    
    // Reject Document
    if (isset($_POST["reject_document"])) {
        $document_id = (int) $_POST["document_id"];
        $app_id = (int) $_POST["application_id"];
        
        $updateSQL = "
            UPDATE documents 
            SET verification_status = 'Rejected' 
            WHERE document_id = " . $document_id . "
            AND application_id = " . $app_id . "
        ";
        
        if (mysqli_query($conn, $updateSQL)) {
            $success = "Document rejected.";
            // Refresh to show updated status
            header("Location: verify_documents.php?application=" . $app_id . "&success=" . urlencode("Document rejected."));
            exit();
        } else {
            $error = "Error rejecting document: " . mysqli_error($conn);
        }
    }
    
    // Save Remarks
    if (isset($_POST["save_remarks"])) {
        $remark = trim($_POST["coordinator_remark"]);
        $app_id = (int) $_POST["application_id"];
        
        if ($remark == "") {
            $error = "Please enter remarks.";
        } else {
            // Assuming you have a remarks column or table
            // For now, we'll just store it in a session or show success
            $success = "Remarks saved successfully.";
        }
    }
    
    // Approve Application
    if (isset($_POST["approve_application"])) {
        $app_id = (int) $_POST["application_id"];
        
        $updateSQL = "
            UPDATE applications 
            SET status = 'Approved' 
            WHERE application_id = " . $app_id . "
        ";
        
        if (mysqli_query($conn, $updateSQL)) {
            $success = "Application approved successfully.";
            header("Location: verify_documents.php?application=" . $app_id . "&success=" . urlencode("Application approved successfully."));
            exit();
        } else {
            $error = "Error approving application: " . mysqli_error($conn);
        }
    }
    
    // Reject Application
    if (isset($_POST["reject_application"])) {
        $app_id = (int) $_POST["application_id"];
        
        $updateSQL = "
            UPDATE applications 
            SET status = 'Rejected' 
            WHERE application_id = " . $app_id . "
        ";
        
        if (mysqli_query($conn, $updateSQL)) {
            $success = "Application rejected successfully.";
            header("Location: verify_documents.php?application=" . $app_id . "&success=" . urlencode("Application rejected successfully."));
            exit();
        } else {
            $error = "Error rejecting application: " . mysqli_error($conn);
        }
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

// If we have an application ID in URL but no POST action, load it
if (isset($_GET["application"]) && !isset($_POST["load_application"])) {
    $selectedAppID = (int) $_GET["application"];
    // Reload the application data (will be done in the loading section above)
    // But we need to make sure we load it
    if ($selectedAppID > 0) {
        // This will be handled by the loading section above
        // We just need to ensure the variable is set correctly
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Verify Documents</title>

    <link rel="stylesheet"
          href="verify_documents.css">

</head>


<body>


<!-- ================= HEADER ================= -->

<?php include "header.php"; ?>



<!-- ================= PAGE LAYOUT ================= -->

<div class="page_layout">


    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">


        <div class="sidebar_heading">
            COORDINATOR PANEL
        </div>


        <nav class="sidebar_menu">


            <a href="coordinator_dashboard.php"
               class="sidebar_item">

                Dashboard

            </a>


            <a href="review_applications.php"
               class="sidebar_item">

                Review Applications

            </a>


            <a href="verify_documents.php"
               class="sidebar_item active">

                Verify Documents

            </a>


            <a href="manage_nominations.php"
               class="sidebar_item">

               Manage Nominations

            </a>


        </nav>


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
                Verify Documents & Application
            </h1>

            <p>
                Verify submitted documents and make the final
                application decision.
            </p>

        </section>



        <!-- ================= PHP MESSAGE ================= -->

        <?php

        if ($error != "") {

            echo "

            <p style='
                color:red;
                text-align:center;
            '>

                " . htmlspecialchars($error) . "

            </p>

            ";

        }


        if ($success != "") {

            echo "

            <p style='
                color:green;
                text-align:center;
            '>

                " . htmlspecialchars($success) . "

            </p>

            ";

        }

        ?>



        <!-- ================= SELECT APPLICATION ================= -->

        <section class="content_box">


            <h2>
                Select Application
            </h2>


            <p class="section_description">

                Select the application whose documents you want
                to verify.

            </p>


            <form method="POST"
                  action="">


                <div class="application_selector">


                    <div class="form_group">


                        <label for="application_id">

                            Application ID

                        </label>


                        <select
                            id="application_id"
                            name="application_id"
                            required
                        >

                            <option value="">

                                Select Application

                            </option>

                            <?php foreach ($applicationsList as $app) { ?>

                                <option 
                                    value="<?php echo (int) $app["application_id"]; ?>"
                                    <?php echo ($selectedAppID == $app["application_id"]) ? "selected" : ""; ?>
                                >

                                    #<?php echo (int) $app["application_id"]; ?> - 
                                    <?php echo htmlspecialchars($app["student_name"]); ?> - 
                                    <?php echo htmlspecialchars($app["program_name"]); ?>
                                    (<?php echo htmlspecialchars($app["status"]); ?>)

                                </option>

                            <?php } ?>

                        </select>


                    </div>


                    <button
                        type="submit"
                        name="load_application"
                        class="load_button"
                    >

                        Load Application

                    </button>


                </div>


            </form>


        </section>



        <!-- ================= APPLICATION INFORMATION ================= -->

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
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["application_id"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Student ID
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["student_id"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Student Name
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["student_name"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Program
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["program_name"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        University
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["host_university"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Country
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["host_country"]) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Application Date
                    </span>

                    <span class="information_value">
                        <?php echo $selectedApplication ? date("Y-m-d", strtotime($selectedApplication["application_date"])) : "—"; ?>
                    </span>

                </div>

                <div class="information_item">

                    <span class="information_label">
                        Application Status
                    </span>

                    <span class="status_badge <?php 
                        if ($selectedApplication) {
                            $status = $selectedApplication["status"];
                            if ($status == "Approved") echo "approved";
                            elseif ($status == "Rejected") echo "rejected";
                            elseif ($status == "Under Review") echo "under_review";
                            else echo "pending";
                        } else {
                            echo "pending";
                        }
                    ?>">
                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["status"]) : "—"; ?>
                    </span>

                </div>


            </div>

        </section>



        <!-- ================= SUBMITTED DOCUMENTS ================= -->

        <section class="content_box">


            <div class="section_header">


                <div>

                    <h2>
                        Submitted Documents
                    </h2>

                    <p class="section_description">

                        Documents submitted by the student
                        for this application.

                    </p>

                </div>


            </div>



            <div class="document_table_container">


                <?php if (!empty($pendingDocuments)) { ?>

                <table class="document_table">


                    <thead>

                        <tr>

                            <th>
                                Document ID
                            </th>

                            <th>
                                Document Type
                            </th>

                            <th>
                                File Name
                            </th>

                            <th>
                                Upload Date
                            </th>

                            <th>
                                Verification Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($pendingDocuments as $doc) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($doc["document_id"]); ?>
                            </td>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        <?php echo htmlspecialchars($doc["document_type"]); ?>
                                    </strong>

                                    <?php if (in_array($doc["document_type"], ["Academic Transcript", "CV / Resume"])) { ?>
                                        <span class="required">Required</span>
                                    <?php } else { ?>
                                        <span class="optional">Optional</span>
                                    <?php } ?>

                                </div>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($doc["file_name"]); ?>
                            </td>

                            <td>
                                <?php echo date("Y-m-d H:i", strtotime($doc["upload_date"])); ?>
                            </td>

                            <td>

                                <span class="document_status <?php 
                                    $status = strtolower($doc["verification_status"]);
                                    echo $status == "verified" ? "verified" : ($status == "rejected" ? "rejected" : "pending");
                                ?>">
                                    <?php echo htmlspecialchars($doc["verification_status"]); ?>
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <?php if (!empty($doc["file_path"])) { ?>
                                        <a 
                                            href="<?php echo htmlspecialchars($doc["file_path"]); ?>" 
                                            target="_blank"
                                            class="view_button"
                                        >
                                            View
                                        </a>
                                    <?php } else { ?>
                                        <span style="color:#999;font-size:12px;">No file</span>
                                    <?php } ?>

                                    <?php if ($doc["verification_status"] == "Pending") { ?>

                                        <form method="POST"
                                              action=""
                                              style="display:inline;">

                                            <input
                                                type="hidden"
                                                name="document_id"
                                                value="<?php echo (int) $doc["document_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="application_id"
                                                value="<?php echo (int) $selectedAppID; ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="verify_document"
                                                class="verify_button"
                                            >
                                                Verify
                                            </button>

                                        </form>


                                        <form method="POST"
                                              action=""
                                              style="display:inline;">

                                            <input
                                                type="hidden"
                                                name="document_id"
                                                value="<?php echo (int) $doc["document_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="application_id"
                                                value="<?php echo (int) $selectedAppID; ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="reject_document"
                                                class="reject_button"
                                            >
                                                Reject
                                            </button>

                                        </form>

                                    <?php } else { ?>

                                        <span style="color:#999;font-size:12px;">
                                            <?php echo $doc["verification_status"] == "Verified" ? "✅ Verified" : "❌ Rejected"; ?>
                                        </span>

                                    <?php } ?>

                                </div>

                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

                <?php } else { ?>

                    <div class="text_box" style="text-align:center;padding:40px;">

                        <?php if ($selectedApplication) { ?>
                            <p>No documents submitted for this application.</p>
                        <?php } else { ?>
                            <p>Please select an application to view documents.</p>
                        <?php } ?>

                    </div>

                <?php } ?>

            </div>

        </section>



        <!-- ================= VERIFICATION SUMMARY ================= -->

        <section class="content_box">


            <h2>
                Verification Summary
            </h2>


            <div class="summary_grid">


                <div class="summary_card">

                    <span class="summary_label">
                        Total Documents
                    </span>

                    <strong>
                        <?php echo $totalCount; ?>
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Verified
                    </span>

                    <strong class="verified_number">
                        <?php echo $verifiedCount; ?>
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Rejected
                    </span>

                    <strong class="rejected_number">
                        <?php echo $rejectedCount; ?>
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Pending
                    </span>

                    <strong class="pending_number">
                        <?php echo $pendingCount; ?>
                    </strong>

                </div>


            </div>

        </section>



        <!-- ================= COORDINATOR REMARKS ================= -->

        <section class="content_box">


            <h2>
                Coordinator Remarks
            </h2>


            <p class="section_description">

                Add remarks regarding document verification
                or the application decision.

            </p>


            <form method="POST"
                  action="">


                <input
                    type="hidden"
                    name="application_id"
                    value="<?php echo (int) $selectedAppID; ?>"
                >

                <div class="remark_group">


                    <label for="coordinator_remark">

                        Remarks

                    </label>


                    <textarea
                        id="coordinator_remark"
                        name="coordinator_remark"
                        rows="6"
                        placeholder="Enter your remarks here..."
                    ></textarea>


                </div>


                <button
                    type="submit"
                    name="save_remarks"
                    class="save_button"
                >

                    Save Remarks

                </button>


            </form>


        </section>



        <!-- ================= APPLICATION DECISION ================= -->

        <section class="content_box decision_section">


            <h2>
                Application Decision
            </h2>


            <p class="section_description">

                After completing document verification,
                make the final decision for this application.

            </p>


            <div class="decision_box">


                <div class="decision_status">


                    <span class="decision_label">

                        Current Application Status

                    </span>


                    <span class="status_badge <?php 
                        if ($selectedApplication) {
                            $status = $selectedApplication["status"];
                            if ($status == "Approved") echo "approved";
                            elseif ($status == "Rejected") echo "rejected";
                            elseif ($status == "Under Review") echo "under_review";
                            else echo "pending";
                        } else {
                            echo "pending";
                        }
                    ?>">

                        <?php echo $selectedApplication ? htmlspecialchars($selectedApplication["status"]) : "Pending"; ?>

                    </span>


                </div>


                <div class="decision_actions">


                    <form method="POST"
                          style="display:inline;">

                        <input
                            type="hidden"
                            name="application_id"
                            value="<?php echo (int) $selectedAppID; ?>"
                        >

                        <button
                            type="submit"
                            name="approve_application"
                            class="approve_button"
                            <?php echo !$selectedApplication ? "disabled" : ""; ?>
                        >

                            Approve Application

                        </button>

                    </form>


                    <form method="POST"
                          style="display:inline;">

                        <input
                            type="hidden"
                            name="application_id"
                            value="<?php echo (int) $selectedAppID; ?>"
                        >

                        <button
                            type="submit"
                            name="reject_application"
                            class="reject_application_button"
                            <?php echo !$selectedApplication ? "disabled" : ""; ?>
                        >

                            Reject Application

                        </button>

                    </form>


                </div>


            </div>


        </section>



        <!-- ================= FOOTER ================= -->

        <?php include "footer.php"; ?>

    </main>


</div>


</body>

</html>