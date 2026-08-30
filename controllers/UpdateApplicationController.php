<?php


/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE + MODEL
========================================================= */

require_once __DIR__ . "/../config/db.php";

require_once __DIR__ . "/../models/ApplicationModel.php";


/* =========================================================
   CHECK REQUEST METHOD
========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header(
        "Location: ../my_applications.php?error=" .
        urlencode("Invalid request.")
    );

    exit();

}


/* =========================================================
   CHECK LOGIN
========================================================= */

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    strtolower($_SESSION["role"]) !== "student"
) {

    header(
        "Location: ../login.php"
    );

    exit();

}


/* =========================================================
   GET STUDENT ID
========================================================= */

$studentID =
    (int) $_SESSION["user_id"];


/* =========================================================
   CREATE MODEL
========================================================= */

$applicationModel =
    new ApplicationModel($conn);


/* =========================================================
   GET FORM DATA
========================================================= */

$applicationID =
    (int) ($_POST["application_id"] ?? 0);


$department =
    trim($_POST["department"] ?? "");


$cgpa =
    trim($_POST["cgpa"] ?? "");


$semester =
    trim($_POST["semester"] ?? "");


$studyTerm =
    trim($_POST["study_term"] ?? "");


$statementOfPurpose =
    trim(
        $_POST["statement_of_purpose"]
        ?? ""
    );


$declaration =
    isset($_POST["declaration"])
    ? 1
    : 0;


/* =========================================================
   VALIDATE APPLICATION ID
========================================================= */

if ($applicationID <= 0) {

    header(
        "Location: ../my_applications.php?error=" .
        urlencode("Invalid application ID.")
    );

    exit();

}


/* =========================================================
   VALIDATE REQUIRED FIELDS
========================================================= */

if (
    $department === "" ||
    $cgpa === "" ||
    $semester === "" ||
    $studyTerm === "" ||
    $statementOfPurpose === ""
) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode("All fields are required.")
    );

    exit();

}


/* =========================================================
   VALIDATE CGPA
========================================================= */

if (
    !is_numeric($cgpa) ||
    (float) $cgpa < 0 ||
    (float) $cgpa > 4
) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode("CGPA must be between 0 and 4.")
    );

    exit();

}


/* =========================================================
   VALIDATE SEMESTER
========================================================= */

if (
    !is_numeric($semester) ||
    (int) $semester < 1
) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode("Please enter a valid semester.")
    );

    exit();

}


/* =========================================================
   VALIDATE STATEMENT
========================================================= */

if (
    strlen($statementOfPurpose) < 20
) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode(
            "Statement of Purpose must contain at least 20 characters."
        )
    );

    exit();

}


/* =========================================================
   VALIDATE DECLARATION
========================================================= */

if ($declaration !== 1) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode(
            "Please accept the declaration."
        )
    );

    exit();

}


/* =========================================================
   GET APPLICATION

   Important:
   This confirms that the application belongs to
   the currently logged-in student.
========================================================= */

$application =
    $applicationModel->getApplicationById(
        $applicationID,
        $studentID
    );


if (!$application) {

    header(
        "Location: ../my_applications.php?error=" .
        urlencode(
            "Application not found or access denied."
        )
    );

    exit();

}


/* =========================================================
   CHECK STATUS

   Only Pending applications can be edited.
========================================================= */

$currentStatus =
    strtolower(
        trim(
            $application["status"] ?? ""
        )
    );


if ($currentStatus !== "pending") {

    header(
        "Location: ../view_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode(
            "Only pending applications can be edited."
        )
    );

    exit();

}


/* =========================================================
   UPDATE APPLICATION
========================================================= */

$updated =
    $applicationModel->updateApplication(
        $applicationID,
        $studentID,
        $department,
        (float) $cgpa,
        (int) $semester,
        $studyTerm,
        $statementOfPurpose,
        $declaration
    );


/* =========================================================
   CHECK UPDATE RESULT
========================================================= */

if (!$updated) {

    header(
        "Location: ../edit_application.php?application_id=" .
        $applicationID .
        "&error=" .
        urlencode(
            "Failed to update the application."
        )
    );

    exit();

}


/* =========================================================
   SUCCESS
========================================================= */

header(
    "Location: ../view_application.php?application_id=" .
    $applicationID .
    "&success=" .
    urlencode(
        "Application updated successfully."
    )
);

exit();


?>