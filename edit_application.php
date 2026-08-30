<?php

/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE + MODEL
========================================================= */

require_once __DIR__ . "/config/db.php";

require_once __DIR__ . "/models/ApplicationModel.php";


/* =========================================================
   LOGIN CHECK
========================================================= */

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    strtolower($_SESSION["role"]) !== "student"
) {

    header(
        "Location: login.php?error=" .
        urlencode("Please login first.")
    );

    exit();

}


/* =========================================================
   GET STUDENT ID
========================================================= */

$studentID = (int) $_SESSION["user_id"];


/* =========================================================
   CREATE MODEL
========================================================= */

$applicationModel =
    new ApplicationModel($conn);


/* =========================================================
   GET APPLICATION ID
========================================================= */

$applicationID =
    (int) ($_GET["application_id"] ?? $_POST["application_id"] ?? 0);


/* =========================================================
   VALIDATE APPLICATION ID
========================================================= */

if ($applicationID <= 0) {

    header(
        "Location: my_applications.php?error=" .
        urlencode("Invalid application ID.")
    );

    exit();

}


/* =========================================================
   GET APPLICATION
========================================================= */

$application =
    $applicationModel->getApplicationById(
        $applicationID,
        $studentID
    );


/* =========================================================
   CHECK APPLICATION
========================================================= */

if (!$application) {

    header(
        "Location: my_applications.php?error=" .
        urlencode(
            "Application not found or you do not have permission."
        )
    );

    exit();

}


/* =========================================================
   ONLY PENDING APPLICATION CAN BE EDITED
========================================================= */

if (
    strtolower($application["status"]) !== "pending"
) {

    header(
        "Location: view_application.php?application_id=" .
        urlencode($applicationID) .
        "&error=" .
        urlencode("Only pending applications can be edited.")
    );

    exit();

}


/* =========================================================
   MESSAGES
========================================================= */

$errorMessage = "";


/* =========================================================
   UPDATE APPLICATION
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* =====================================================
       GET FORM DATA
    ===================================================== */

    $department =
        trim($_POST["department"] ?? "");

    $cgpa =
        trim($_POST["cgpa"] ?? "");

    $semester =
        trim($_POST["semester"] ?? "");

    $studyTerm =
        trim($_POST["study_term"] ?? "");

    $statementOfPurpose =
        trim($_POST["statement_of_purpose"] ?? "");


    /* =====================================================
       VALIDATION
    ===================================================== */

    if (
        $department === "" ||
        $cgpa === "" ||
        $semester === "" ||
        $studyTerm === "" ||
        $statementOfPurpose === ""
    ) {

        $errorMessage =
            "Please fill in all required fields.";

    }

    elseif (!is_numeric($cgpa)) {

        $errorMessage =
            "CGPA must be a valid number.";

    }

    elseif (
        (float) $cgpa < 0 ||
        (float) $cgpa > 4
    ) {

        $errorMessage =
            "CGPA must be between 0 and 4.";

    }

    elseif (
        !is_numeric($semester) ||
        (int) $semester <= 0
    ) {

        $errorMessage =
            "Semester must be a valid number.";

    }


    /* =====================================================
       UPDATE DATABASE
    ===================================================== */

    if ($errorMessage === "") {


        $updateSQL = "

            UPDATE applications

            SET

                department = ?,

                cgpa = ?,

                semester = ?,

                study_term = ?,

                statement_of_purpose = ?

            WHERE

                application_id = ?

            AND

                student_id = ?

            AND

                LOWER(status) = 'pending'

        ";


        $statement =
            mysqli_prepare(
                $conn,
                $updateSQL
            );


        /* =================================================
           PREPARE ERROR
        ================================================= */

        if (!$statement) {

            $errorMessage =
                "Database error: " .
                mysqli_error($conn);

        }

        else {


            /* =============================================
               CORRECT BIND PARAM

               s = department
               d = cgpa
               i = semester
               s = study term
               s = statement
               i = application ID
               i = student ID

               EXACT TYPE STRING = sdissii
            ============================================= */

            mysqli_stmt_bind_param(

                $statement,

                "sdissii",

                $department,

                $cgpa,

                $semester,

                $studyTerm,

                $statementOfPurpose,

                $applicationID,

                $studentID

            );


            /* =============================================
               EXECUTE UPDATE
            ============================================= */

            if (
                mysqli_stmt_execute($statement)
            ) {


                mysqli_stmt_close(
                    $statement
                );


                /* =========================================
                   UPLOAD DIRECTORY
                ========================================== */

                $uploadDirectory =
                    __DIR__ .
                    "/uploads/documents/";


                if (
                    !is_dir($uploadDirectory)
                ) {

                    mkdir(
                        $uploadDirectory,
                        0777,
                        true
                    );

                }


                /* =========================================
                   DOCUMENT FIELDS
                ========================================== */

                $documentFields = [

                    "cv_resume" =>
                        "CV / Resume",

                    "academic_transcript" =>
                        "Academic Transcript"

                ];


                /* =========================================
                   ALLOWED EXTENSIONS
                ========================================== */

                $allowedExtensions = [

                    "pdf",
                    "doc",
                    "docx",
                    "jpg",
                    "jpeg",
                    "png"

                ];


                /* =========================================
                   PROCESS DOCUMENTS
                ========================================== */

                foreach (
                    $documentFields as
                    $inputName => $documentType
                ) {


                    /* =====================================
                       NO NEW FILE = KEEP OLD FILE
                    ====================================== */

                    if (
                        !isset($_FILES[$inputName]) ||
                        $_FILES[$inputName]["error"] ===
                        UPLOAD_ERR_NO_FILE
                    ) {

                        continue;

                    }


                    /* =====================================
                       UPLOAD ERROR
                    ====================================== */

                    if (
                        $_FILES[$inputName]["error"] !==
                        UPLOAD_ERR_OK
                    ) {

                        $errorMessage =
                            "Error uploading " .
                            $documentType .
                            ".";

                        break;

                    }


                    /* =====================================
                       FILE INFORMATION
                    ====================================== */

                    $originalFileName =
                        basename(
                            $_FILES[$inputName]["name"]
                        );


                    $temporaryFile =
                        $_FILES[$inputName]["tmp_name"];


                    $fileExtension =
                        strtolower(
                            pathinfo(
                                $originalFileName,
                                PATHINFO_EXTENSION
                            )
                        );


                    /* =====================================
                       CHECK EXTENSION
                    ====================================== */

                    if (
                        !in_array(
                            $fileExtension,
                            $allowedExtensions
                        )
                    ) {

                        $errorMessage =
                            $documentType .
                            " must be PDF, DOC, DOCX, JPG, JPEG or PNG.";

                        break;

                    }


                    /* =====================================
                       CREATE UNIQUE FILE NAME
                    ====================================== */

                    $newFileName =
                        $inputName .
                        "_" .
                        $applicationID .
                        "_" .
                        time() .
                        "_" .
                        uniqid() .
                        "." .
                        $fileExtension;


                    $destinationPath =
                        $uploadDirectory .
                        $newFileName;


                    /* =====================================
                       MOVE NEW FILE
                    ====================================== */

                    if (
                        !move_uploaded_file(
                            $temporaryFile,
                            $destinationPath
                        )
                    ) {

                        $errorMessage =
                            "Failed to upload " .
                            $documentType .
                            ".";

                        break;

                    }


                    /* =====================================
                       DATABASE FILE PATH
                    ====================================== */

                    $databaseFilePath =
                        "uploads/documents/" .
                        $newFileName;


                    /* =====================================
                       CHECK EXISTING DOCUMENT
                    ====================================== */

                    $checkDocumentSQL = "

                        SELECT

                            document_id,
                            file_path

                        FROM documents

                        WHERE application_id = ?

                        AND document_type = ?

                        LIMIT 1

                    ";


                    $checkStatement =
                        mysqli_prepare(
                            $conn,
                            $checkDocumentSQL
                        );


                    if (!$checkStatement) {

                        $errorMessage =
                            "Database error while checking documents.";

                        break;

                    }


                    mysqli_stmt_bind_param(

                        $checkStatement,

                        "is",

                        $applicationID,

                        $documentType

                    );


                    mysqli_stmt_execute(
                        $checkStatement
                    );


                    $documentResult =
                        mysqli_stmt_get_result(
                            $checkStatement
                        );


                    /* =====================================
                       DOCUMENT EXISTS
                    ====================================== */

                    if (
                        mysqli_num_rows(
                            $documentResult
                        ) > 0
                    ) {


                        $existingDocument =
                            mysqli_fetch_assoc(
                                $documentResult
                            );


                        /* =================================
                           UPDATE DATABASE
                        ================================== */

                        $updateDocumentSQL = "

                            UPDATE documents

                            SET

                                file_name = ?,

                                file_path = ?,

                                verification_status = 'Pending'

                            WHERE document_id = ?

                        ";


                        $updateDocumentStatement =
                            mysqli_prepare(
                                $conn,
                                $updateDocumentSQL
                            );


                        if (
                            !$updateDocumentStatement
                        ) {

                            $errorMessage =
                                "Failed to update " .
                                $documentType .
                                " in database.";

                            mysqli_stmt_close(
                                $checkStatement
                            );

                            break;

                        }


                        mysqli_stmt_bind_param(

                            $updateDocumentStatement,

                            "ssi",

                            $originalFileName,

                            $databaseFilePath,

                            $existingDocument["document_id"]

                        );


                        if (
                            mysqli_stmt_execute(
                                $updateDocumentStatement
                            )
                        ) {


                            /* =============================
                               DELETE OLD FILE
                            ============================== */

                            if (
                                !empty(
                                    $existingDocument["file_path"]
                                )
                            ) {

                                $oldFilePath =
                                    __DIR__ .
                                    "/" .
                                    $existingDocument["file_path"];


                                if (
                                    file_exists(
                                        $oldFilePath
                                    )
                                ) {

                                    unlink(
                                        $oldFilePath
                                    );

                                }

                            }

                        }

                        else {

                            $errorMessage =
                                "Failed to update " .
                                $documentType .
                                ".";

                        }


                        mysqli_stmt_close(
                            $updateDocumentStatement
                        );

                    }


                    /* =====================================
                       DOCUMENT DOES NOT EXIST
                    ====================================== */

                    else {


                        $insertDocumentSQL = "

                            INSERT INTO documents

                            (

                                application_id,

                                document_type,

                                file_name,

                                file_path,

                                verification_status

                            )

                            VALUES

                            (

                                ?,

                                ?,

                                ?,

                                ?,

                                'Pending'

                            )

                        ";


                        $insertDocumentStatement =
                            mysqli_prepare(
                                $conn,
                                $insertDocumentSQL
                            );


                        if (
                            !$insertDocumentStatement
                        ) {

                            $errorMessage =
                                "Failed to add " .
                                $documentType .
                                " to database.";

                            mysqli_stmt_close(
                                $checkStatement
                            );

                            break;

                        }


                        mysqli_stmt_bind_param(

                            $insertDocumentStatement,

                            "isss",

                            $applicationID,

                            $documentType,

                            $originalFileName,

                            $databaseFilePath

                        );


                        if (
                            !mysqli_stmt_execute(
                                $insertDocumentStatement
                            )
                        ) {

                            $errorMessage =
                                "Failed to save " .
                                $documentType .
                                ".";

                        }


                        mysqli_stmt_close(
                            $insertDocumentStatement
                        );

                    }


                    mysqli_stmt_close(
                        $checkStatement
                    );


                    if ($errorMessage !== "") {

                        break;

                    }

                }


                /* =========================================
                   REDIRECT AFTER SUCCESS
                ========================================== */

                if ($errorMessage === "") {

                    header(

                        "Location: view_application.php?application_id=" .
                        urlencode($applicationID) .
                        "&success=" .
                        urlencode(
                            "Application updated successfully."
                        )

                    );

                    exit();

                }

            }

            else {

                $errorMessage =
                    "Failed to update application: " .
                    mysqli_stmt_error(
                        $statement
                    );


                mysqli_stmt_close(
                    $statement
                );

            }

        }

    }

}


/* =========================================================
   RELOAD APPLICATION
========================================================= */

$application =
    $applicationModel->getApplicationById(
        $applicationID,
        $studentID
    );


/* =========================================================
   RELOAD DOCUMENTS
========================================================= */

$documents =
    $applicationModel->getDocumentsByApplicationID(
        $applicationID
    );


/* =========================================================
   CREATE DOCUMENT ARRAY
========================================================= */

$documentList = [];


if (
    $documents &&
    mysqli_num_rows($documents) > 0
) {

    while (
        $document =
        mysqli_fetch_assoc($documents)
    ) {

        $documentList[
            strtolower(
                trim(
                    $document["document_type"]
                )
            )
        ] = $document;

    }

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

        SEPMS - Edit Application

    </title>


    <!-- SAME DASHBOARD DESIGN -->

    <link
        rel="stylesheet"
        href="student_dashboard.css"
    >


    <!-- EDIT PAGE CONTENT -->

    <link
        rel="stylesheet"
        href="edit_application.css"
    >


</head>


<body>


    <!-- =====================================================
         SAME HEADER AS STUDENT DASHBOARD
    ====================================================== -->

    <?php include "header.php"; ?>


    <!-- =====================================================
         SAME PAGE LAYOUT AS STUDENT DASHBOARD
    ====================================================== -->

    <div class="page_layout">


        <!-- =================================================
             SAME SIDEBAR AS STUDENT DASHBOARD
        ================================================== -->

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
                    class="sidebar_item"
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
                    class="sidebar_item active"
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
                    href="logout.php"
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


            <section class="welcome_box edit_page_header">


                <h1>

                    Edit Application

                </h1>


                <p>

                    Update your application information and submitted documents.

                </p>


            </section>


            <?php if ($errorMessage !== "") { ?>

                <div class="error_message">

                    <?php
                    echo htmlspecialchars(
                        $errorMessage
                    );
                    ?>

                </div>

            <?php } ?>


            <section class="edit_section">


                <div class="edit_section_header">


                    <h2>

                        Application Details

                    </h2>


                    <p>

                        Update your information below.

                    </p>


                </div>


                <div class="edit_application_card">


                    <div class="application_top">


                        <div>


                            <span class="application_id_text">

                                Application ID:
                                <?php
                                echo htmlspecialchars(
                                    $application["application_id"]
                                );
                                ?>

                            </span>


                            <h2>

                                <?php
                                echo htmlspecialchars(
                                    $application["program_name"]
                                );
                                ?>

                            </h2>


                        </div>


                        <span class="status_badge">

                            <?php
                            echo htmlspecialchars(
                                $application["status"]
                            );
                            ?>

                        </span>


                    </div>


                    <form
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <input
                            type="hidden"
                            name="application_id"
                            value="<?php
                            echo htmlspecialchars($applicationID);
                            ?>"
                        >


                        <!-- PROGRAM INFORMATION -->

                        <div class="form_section_title">

                            Exchange Program Information

                        </div>


                        <div class="info_grid">


                            <div class="info_box">

                                <span>Program ID</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["program_id"]); ?>
                                </strong>

                            </div>


                            <div class="info_box">

                                <span>Country</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["country_name"]); ?>
                                </strong>

                            </div>


                            <div class="info_box">

                                <span>University</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["university_name"]); ?>
                                </strong>

                            </div>


                            <div class="info_box">

                                <span>Program Start Date</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["start_date"]); ?>
                                </strong>

                            </div>


                            <div class="info_box">

                                <span>Program End Date</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["end_date"]); ?>
                                </strong>

                            </div>


                            <div class="info_box">

                                <span>Application Deadline</span>

                                <strong>
                                    <?php echo htmlspecialchars($application["deadline"]); ?>
                                </strong>

                            </div>


                        </div>


                        <!-- STUDENT INFORMATION -->

                        <div class="form_section_title">

                            Student Application Information

                        </div>


                        <div class="form_grid">


                            <div class="form_group">

                                <label for="department">
                                    Department
                                </label>

                                <input
                                    type="text"
                                    id="department"
                                    name="department"
                                    value="<?php echo htmlspecialchars($application["department"]); ?>"
                                    required
                                >

                            </div>


                            <div class="form_group">

                                <label for="cgpa">
                                    CGPA
                                </label>

                                <input
                                    type="number"
                                    id="cgpa"
                                    name="cgpa"
                                    step="0.01"
                                    min="0"
                                    max="4"
                                    value="<?php echo htmlspecialchars($application["cgpa"]); ?>"
                                    required
                                >

                            </div>


                            <div class="form_group">

                                <label for="semester">
                                    Semester
                                </label>

                                <input
                                    type="number"
                                    id="semester"
                                    name="semester"
                                    min="1"
                                    value="<?php echo htmlspecialchars($application["semester"]); ?>"
                                    required
                                >

                            </div>


                            <div class="form_group">

                                <label for="study_term">
                                    Preferred Term
                                </label>

                                <select
                                    id="study_term"
                                    name="study_term"
                                    required
                                >

                                    <option value="Spring"
                                        <?php
                                        if ($application["study_term"] === "Spring") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Spring
                                    </option>

                                    <option value="Summer"
                                        <?php
                                        if ($application["study_term"] === "Summer") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Summer
                                    </option>

                                    <option value="Fall"
                                        <?php
                                        if ($application["study_term"] === "Fall") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Fall
                                    </option>

                                </select>

                            </div>


                        </div>


                        <!-- STATEMENT -->

                        <div class="form_section_title">

                            Statement of Purpose

                        </div>


                        <div class="form_group full_width">


                            <label for="statement_of_purpose">

                                Why do you want to join this exchange program?

                            </label>


                            <textarea
                                id="statement_of_purpose"
                                name="statement_of_purpose"
                                rows="8"
                                minlength="20"
                                required
                            ><?php echo htmlspecialchars($application["statement_of_purpose"]); ?></textarea>


                        </div>


                        <!-- DOCUMENTS -->

                        <div class="form_section_title">

                            Submitted Documents

                        </div>


                        <p class="document_note">

                            Upload a new file only if you want to replace the
                            submitted document. If you leave it empty, your
                            current document will remain unchanged.

                        </p>


                        <!-- CV -->

                        <div class="document_edit_box">


                            <div class="document_current_info">


                                <h3>

                                    CV / Resume

                                </h3>


                                <?php
                                $cvDocument =
                                    $documentList["cv / resume"] ?? null;
                                ?>


                                <?php if ($cvDocument) { ?>


                                    <p>

                                        Current File:
                                        <strong>
                                            <?php echo htmlspecialchars($cvDocument["file_name"]); ?>
                                        </strong>

                                    </p>


                                    <a
                                        href="<?php echo htmlspecialchars($cvDocument["file_path"]); ?>"
                                        target="_blank"
                                        class="view_document_button"
                                    >

                                        View Current File

                                    </a>


                                <?php } else { ?>


                                    <p>
                                        No document submitted.
                                    </p>


                                <?php } ?>


                            </div>


                            <div class="document_upload_area">


                                <label for="cv_resume">

                                    Replace CV / Resume

                                </label>


                                <input
                                    type="file"
                                    id="cv_resume"
                                    name="cv_resume"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png"
                                >


                                <small>

                                    PDF, DOC, DOCX, JPG, JPEG or PNG

                                </small>


                            </div>


                        </div>


                        <!-- TRANSCRIPT -->

                        <div class="document_edit_box">


                            <div class="document_current_info">


                                <h3>

                                    Academic Transcript

                                </h3>


                                <?php
                                $transcriptDocument =
                                    $documentList["academic transcript"] ?? null;
                                ?>


                                <?php if ($transcriptDocument) { ?>


                                    <p>

                                        Current File:
                                        <strong>
                                            <?php echo htmlspecialchars($transcriptDocument["file_name"]); ?>
                                        </strong>

                                    </p>


                                    <a
                                        href="<?php echo htmlspecialchars($transcriptDocument["file_path"]); ?>"
                                        target="_blank"
                                        class="view_document_button"
                                    >

                                        View Current File

                                    </a>


                                <?php } else { ?>


                                    <p>
                                        No document submitted.
                                    </p>


                                <?php } ?>


                            </div>


                            <div class="document_upload_area">


                                <label for="academic_transcript">

                                    Replace Academic Transcript

                                </label>


                                <input
                                    type="file"
                                    id="academic_transcript"
                                    name="academic_transcript"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png"
                                >


                                <small>

                                    PDF, DOC, DOCX, JPG, JPEG or PNG

                                </small>


                            </div>


                        </div>


                        <!-- CONFIRMATION -->

                        <div class="confirmation_box">


                            <label>

                                <input
                                    type="checkbox"
                                    required
                                >

                                I confirm that the information provided is correct.

                            </label>


                        </div>


                        <!-- ACTIONS -->

                        <div class="form_actions">


                            <a
                                href="view_application.php?application_id=<?php echo urlencode($applicationID); ?>"
                                class="cancel_button"
                            >

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="update_button"
                            >

                                Update Application

                            </button>


                        </div>


                    </form>


                </div>


            </section>


        </main>


    </div>


    <?php include "footer.php"; ?>


</body>


</html>