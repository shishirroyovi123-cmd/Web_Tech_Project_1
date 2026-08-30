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
        "Location: ../search_apply.php?error=" .
        urlencode("Invalid request.")
    );

    exit();

}


/* =========================================================
   CHECK LOGIN
========================================================= */

if (!isset($_SESSION["user_id"])) {

    header(
        "Location: ../login.php?error=" .
        urlencode("Please login first.")
    );

    exit();

}


/* =========================================================
   CHECK STUDENT ROLE
========================================================= */

if (
    !isset($_SESSION["role"]) ||
    strtolower($_SESSION["role"]) !== "student"
) {

    header(
        "Location: ../login.php?error=" .
        urlencode("Only students can submit an application.")
    );

    exit();

}


/* =========================================================
   GET LOGGED-IN STUDENT ID
========================================================= */

$studentID =
    (int) $_SESSION["user_id"];


/* =========================================================
   CREATE APPLICATION MODEL
========================================================= */

$applicationModel =
    new ApplicationModel($conn);


/* =========================================================
   GET FORM DATA
========================================================= */

$programID =
    (int) ($_POST["program_id"] ?? 0);


$department =
    trim($_POST["department"] ?? "");


$semester =
    trim($_POST["semester"] ?? "");


$cgpa =
    trim($_POST["cgpa"] ?? "");


/* preferred_term is the form input name */

$studyTerm =
    trim($_POST["preferred_term"] ?? "");


/* statement is the form input name */

$statement =
    trim($_POST["statement"] ?? "");


/* Declaration checkbox */

$declaration =
    isset($_POST["declaration"]) ? 1 : 0;


/* =========================================================
   VALIDATE PROGRAM ID
========================================================= */

if ($programID <= 0) {

    header(
        "Location: ../search_apply.php?error=" .
        urlencode(
            "Please select an exchange program first."
        )
    );

    exit();

}


/* =========================================================
   VALIDATE REQUIRED FIELDS
========================================================= */

if (
    $department === "" ||
    $semester === "" ||
    $cgpa === "" ||
    $studyTerm === "" ||
    $statement === ""
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "All required fields must be completed."
        )
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
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "CGPA must be between 0 and 4."
        )
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
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Please enter a valid semester."
        )
    );

    exit();

}


/* =========================================================
   VALIDATE STATEMENT OF PURPOSE
========================================================= */

if (strlen($statement) < 20) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
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
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Please accept the declaration before submitting."
        )
    );

    exit();

}


/* =========================================================
   CHECK STUDENT EXISTS
========================================================= */

$student =
    $applicationModel->getStudentById(
        $studentID
    );


if (!$student) {

    header(
        "Location: ../login.php?error=" .
        urlencode(
            "Student account was not found."
        )
    );

    exit();

}


/* =========================================================
   GET SELECTED PROGRAM
========================================================= */

$program =
    $applicationModel->getProgramById(
        $programID
    );


if (!$program) {

    header(
        "Location: ../search_apply.php?error=" .
        urlencode(
            "Selected exchange program was not found."
        )
    );

    exit();

}


/* =========================================================
   CHECK APPLICATION DEADLINE
========================================================= */

$deadlineValue =
    $program["deadline"] ?? "";


if ($deadlineValue !== "") {

    $deadline =
        strtotime($deadlineValue);


    /*
       Last moment of deadline day.
       Example:
       Deadline = 2026-08-28
       Student can apply until 11:59:59 PM.
    */

    if ($deadline !== false) {

        $deadline =
            strtotime(
                date(
                    "Y-m-d 23:59:59",
                    $deadline
                )
            );


        $currentTime =
            time();


        if ($currentTime > $deadline) {

            header(
                "Location: ../application_form.php?program_id=" .
                urlencode($programID) .
                "&error=" .
                urlencode(
                    "Application deadline has passed."
                )
            );

            exit();

        }

    }

}


/* =========================================================
   CHECK AVAILABLE SEATS
========================================================= */

$availableSeats =
    (int) (
        $program["available_seats"]
        ?? 0
    );


if ($availableSeats <= 0) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "No seats are currently available for this program."
        )
    );

    exit();

}


/* =========================================================
   CHECK DUPLICATE APPLICATION
========================================================= */

$alreadyApplied =
    $applicationModel->checkExistingApplication(
        $studentID,
        $programID
    );


if ($alreadyApplied) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "You have already applied for this program."
        )
    );

    exit();

}


/* =========================================================
   CHECK TRANSCRIPT
========================================================= */

if (
    !isset($_FILES["transcript"]) ||
    $_FILES["transcript"]["error"] !== UPLOAD_ERR_OK
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Academic Transcript is required."
        )
    );

    exit();

}


/* =========================================================
   CHECK CV
========================================================= */

if (
    !isset($_FILES["cv"]) ||
    $_FILES["cv"]["error"] !== UPLOAD_ERR_OK
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "CV / Resume is required."
        )
    );

    exit();

}


/* =========================================================
   MAXIMUM FILE SIZE
========================================================= */

$maxFileSize =
    5 * 1024 * 1024;


/* =========================================================
   VALIDATE TRANSCRIPT SIZE
========================================================= */

if (
    $_FILES["transcript"]["size"]
    > $maxFileSize
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Academic Transcript must be less than 5 MB."
        )
    );

    exit();

}


/* =========================================================
   VALIDATE CV SIZE
========================================================= */

if (
    $_FILES["cv"]["size"]
    > $maxFileSize
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "CV / Resume must be less than 5 MB."
        )
    );

    exit();

}


/* =========================================================
   GET TRANSCRIPT EXTENSION
========================================================= */

$transcriptExtension =
    strtolower(
        pathinfo(
            $_FILES["transcript"]["name"],
            PATHINFO_EXTENSION
        )
    );


/* =========================================================
   ALLOWED TRANSCRIPT TYPES
========================================================= */

$allowedTranscriptExtensions = [

    "pdf",
    "jpg",
    "jpeg",
    "png"

];


if (
    !in_array(
        $transcriptExtension,
        $allowedTranscriptExtensions,
        true
    )
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Academic Transcript must be PDF, JPG, JPEG, or PNG."
        )
    );

    exit();

}


/* =========================================================
   GET CV EXTENSION
========================================================= */

$cvExtension =
    strtolower(
        pathinfo(
            $_FILES["cv"]["name"],
            PATHINFO_EXTENSION
        )
    );


/* =========================================================
   ALLOWED CV TYPES
========================================================= */

$allowedCVExtensions = [

    "pdf",
    "doc",
    "docx"

];


if (
    !in_array(
        $cvExtension,
        $allowedCVExtensions,
        true
    )
) {

    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "CV / Resume must be PDF, DOC, or DOCX."
        )
    );

    exit();

}


/* =========================================================
   PREPARE APPLICATION VALUES
========================================================= */

$cgpaValue =
    (float) $cgpa;


$semesterValue =
    (int) $semester;


$applicationDate =
    date("Y-m-d");


$status =
    "Pending";


/* =========================================================
   START MYSQL TRANSACTION
========================================================= */

mysqli_begin_transaction(
    $conn
);


/* =========================================================
   CREATE APPLICATION

   IMPORTANT:
   This matches your CURRENT ApplicationModel:

   createApplication(
       $studentID,
       $programID,
       $department,
       $cgpa,
       $semester,
       $studyTerm,
       $statementOfPurpose,
       $applicationDate,
       $status,
       $declaration
   )
========================================================= */

$applicationCreated =
    $applicationModel->createApplication(
        $studentID,
        $programID,
        $department,
        $cgpaValue,
        $semesterValue,
        $studyTerm,
        $statement,
        $applicationDate,
        $status,
        $declaration
    );


/* =========================================================
   CHECK APPLICATION CREATION
========================================================= */

if (!$applicationCreated) {

    mysqli_rollback(
        $conn
    );


    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Failed to submit application."
        )
    );

    exit();

}


/* =========================================================
   GET NEW APPLICATION ID
========================================================= */

$applicationID =
    $applicationModel->getLastApplicationID();


if ($applicationID <= 0) {

    mysqli_rollback(
        $conn
    );


    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Could not retrieve the new application ID."
        )
    );

    exit();

}


/* =========================================================
   CREATE UPLOAD DIRECTORY
========================================================= */

$uploadDirectory =
    __DIR__ .
    "/../uploads/applications/" .
    $applicationID .
    "/";


if (!is_dir($uploadDirectory)) {

    $directoryCreated =
        mkdir(
            $uploadDirectory,
            0777,
            true
        );


    if (!$directoryCreated) {

        mysqli_rollback(
            $conn
        );


        header(
            "Location: ../application_form.php?program_id=" .
            urlencode($programID) .
            "&error=" .
            urlencode(
                "Could not create upload directory."
            )
        );

        exit();

    }

}


/* =========================================================
   DOCUMENT CONFIGURATION
========================================================= */

$documents = [

    "transcript" => [

        "document_type" =>
            "Academic Transcript"

    ],


    "cv" => [

        "document_type" =>
            "CV / Resume"

    ]

];


/* =========================================================
   TRACK UPLOADED FILES

   Used for cleanup if something fails.
========================================================= */

$uploadedFiles = [];


/* =========================================================
   UPLOAD DOCUMENTS
========================================================= */

foreach (
    $documents as $field => $document
) {


    /* =====================================================
       GET FILE
    ===================================================== */

    $file =
        $_FILES[$field];


    /* =====================================================
       GET EXTENSION
    ===================================================== */

    $extension =
        strtolower(
            pathinfo(
                $file["name"],
                PATHINFO_EXTENSION
            )
        );


    /* =====================================================
       CREATE SAFE UNIQUE FILE NAME
    ===================================================== */

    $newFileName =
        $field .
        "_" .
        $applicationID .
        "_" .
        time() .
        "_" .
        uniqid() .
        "." .
        $extension;


    /* =====================================================
       CREATE TARGET PATH
    ===================================================== */

    $targetPath =
        $uploadDirectory .
        $newFileName;


    /*
       Path saved in database.
    */

    $databaseFilePath =
        "uploads/applications/" .
        $applicationID .
        "/" .
        $newFileName;


    /* =====================================================
       MOVE FILE
    ===================================================== */

    $fileMoved =
        move_uploaded_file(
            $file["tmp_name"],
            $targetPath
        );


    if (!$fileMoved) {


        /* Delete previously uploaded files */

        foreach (
            $uploadedFiles as $uploadedFile
        ) {

            if (
                file_exists($uploadedFile)
            ) {

                unlink($uploadedFile);

            }

        }


        mysqli_rollback(
            $conn
        );


        header(
            "Location: ../application_form.php?program_id=" .
            urlencode($programID) .
            "&error=" .
            urlencode(
                "Could not save " .
                $document["document_type"] .
                "."
            )
        );

        exit();

    }


    /* Store for possible cleanup */

    $uploadedFiles[] =
        $targetPath;


    /* =====================================================
       SAVE DOCUMENT INFORMATION
    ===================================================== */

    $documentCreated =
        $applicationModel->createDocument(
            $applicationID,
            $document["document_type"],
            $newFileName,
            $databaseFilePath
        );


    if (!$documentCreated) {


        /* Delete all uploaded files */

        foreach (
            $uploadedFiles as $uploadedFile
        ) {

            if (
                file_exists($uploadedFile)
            ) {

                unlink($uploadedFile);

            }

        }


        mysqli_rollback(
            $conn
        );


        header(
            "Location: ../application_form.php?program_id=" .
            urlencode($programID) .
            "&error=" .
            urlencode(
                "Could not save document information."
            )
        );

        exit();

    }

}


/* =========================================================
   COMMIT MYSQL TRANSACTION
========================================================= */

$committed =
    mysqli_commit(
        $conn
    );


if (!$committed) {


    mysqli_rollback(
        $conn
    );


    /* Delete uploaded files */

    foreach (
        $uploadedFiles as $uploadedFile
    ) {

        if (
            file_exists($uploadedFile)
        ) {

            unlink($uploadedFile);

        }

    }


    header(
        "Location: ../application_form.php?program_id=" .
        urlencode($programID) .
        "&error=" .
        urlencode(
            "Application submission failed."
        )
    );

    exit();

}


/* =========================================================
   SUCCESS
========================================================= */

header(
    "Location: ../my_applications.php?success=" .
    urlencode(
        "Application submitted successfully."
    )
);

exit();

?>