<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $department = trim($_POST["department"]);
    $cgpa = trim($_POST["cgpa"]);
    $semester = trim($_POST["semester"]);
    $study_term = $_POST["study_term"];
    $statement = trim($_POST["statement_of_purpose"]);


    /* Check required fields */

    if (
        empty($department) ||
        empty($cgpa) ||
        empty($semester) ||
        empty($study_term) ||
        empty($statement)
    ) {

        header("Location: application_form.php?error=All required fields are required.");
        exit();

    }


    /* Check CGPA */

    elseif ($cgpa < 0 || $cgpa > 4) {

        header("Location: application_form.php?error=CGPA must be between 0 and 4.");
        exit();

    }


    /* Check semester */

    elseif ($semester < 1) {

        header("Location: application_form.php?error=Please enter a valid semester.");
        exit();

    }


    /* Check statement */

    elseif (strlen($statement) < 20) {

        header("Location: application_form.php?error=Statement of Purpose must contain at least 20 characters.");
        exit();

    }


    /* Check required documents */

    elseif (
        !isset($_FILES["passport_id"]) ||
        $_FILES["passport_id"]["error"] != 0
    ) {

        header("Location: application_form.php?error=Passport or National ID is required.");
        exit();

    }


    elseif (
        !isset($_FILES["passport_photo"]) ||
        $_FILES["passport_photo"]["error"] != 0
    ) {

        header("Location: application_form.php?error=Passport-size photograph is required.");
        exit();

    }


    elseif (
        !isset($_FILES["academic_transcript"]) ||
        $_FILES["academic_transcript"]["error"] != 0
    ) {

        header("Location: application_form.php?error=Academic transcript is required.");
        exit();

    }


    /* Successful validation */

    else {

        header("Location: application_form.php?success=Application validation successful.");
        exit();

    }

}


/* ================= ERROR MESSAGE ================= */

if (isset($_GET["error"])) {

    $error = $_GET["error"];

}


/* ================= SUCCESS MESSAGE ================= */

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

    <title>SEPMS - Exchange Application</title>

    <link rel="stylesheet"
          href="application_form.css">

    <script src="application_form.js"></script>

</head>


<body>


    <!-- ================= HEADER ================= -->
<?php include "header.php"; ?>


    <!-- ================= PAGE LAYOUT ================= -->

    <div class="page_layout">


        <!-- ================= SIDEBAR ================= -->

        <aside class="sidebar">

            <div class="sidebar_heading">
                STUDENT PANEL
            </div>


            <nav class="sidebar_menu">


                <a href="student_dashboard.php"
                   class="sidebar_item ">
                    Dashboard
                </a>


                <a href="search_apply.php"
                   class="sidebar_item ">
                    Search & Apply
                </a>


                <a href="application_form.php"
                   class="sidebar_item ">
                    My Applications
                </a>


                <a href="application_status.php"
                   class="sidebar_item">
                    Application Status
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
                    Exchange Program Application
                </h1>

                <p>
                    Complete your application and upload the required
                    documents.
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
                    $error
                </p>
                ";

            }

            ?>


            <?php

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



            <!-- ================= FORM ================= -->

            <form
                method="POST"
                action=""
                enctype="multipart/form-data"
                onsubmit="return validateApplication();"
            >


                <!-- =================================================
                           SELECTED PROGRAM
                ================================================== -->

                <section class="content_box">

                    <h2>
                        Selected Exchange Program
                    </h2>


                    <div class="program_information">


                        <div class="information_item">

                            <span class="information_label">
                                Program ID
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                Program Name
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                Country
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                University
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                Start Date
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                End Date
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                Application Deadline
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                        <div class="information_item">

                            <span class="information_label">
                                Available Seats
                            </span>

                            <span class="information_value">
                                —
                            </span>

                        </div>


                    </div>


                    <div class="description_box">

                        <span class="information_label">
                            Description
                        </span>

                        <p>
                            Program description will appear here.
                        </p>

                    </div>

                </section>



                <!-- =================================================
                           STUDENT INFORMATION
                ================================================== -->

                <section class="content_box">

                    <h2>
                        Student Information
                    </h2>

                    <p class="section_description">
                        Your registered information will appear here
                        automatically.
                    </p>


                    <div class="form_row">


                        <div class="form_group">

                            <label>
                                Student ID
                            </label>

                            <input
                                type="text"
                                name="student_id"
                                placeholder="Student ID"
                                readonly
                            >

                        </div>


                        <div class="form_group">

                            <label>
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                placeholder="Full Name"
                                readonly
                            >

                        </div>


                    </div>


                    <div class="form_row">


                        <div class="form_group">

                            <label>
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                placeholder="Email Address"
                                readonly
                            >

                        </div>


                        <div class="form_group">

                            <label>
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                placeholder="Phone Number"
                                readonly
                            >

                        </div>


                    </div>


                    <div class="form_row">


                        <div class="form_group">

                            <label>
                                Department
                            </label>

                            <input
                                type="text"
                                name="department"
                                id="department"
                                placeholder="Department"
                            >

                        </div>


                        <div class="form_group">

                            <label>
                                Current CGPA
                            </label>

                            <input
                                type="number"
                                name="cgpa"
                                id="cgpa"
                                step="0.01"
                                min="0"
                                max="4"
                                placeholder="Enter current CGPA"
                            >

                        </div>


                    </div>


                    <div class="form_row">


                        <div class="form_group">

                            <label>
                                Current Semester
                            </label>

                            <input
                                type="number"
                                name="semester"
                                id="semester"
                                min="1"
                                placeholder="Enter current semester"
                            >

                        </div>


                        <div class="form_group">

                            <label>
                                Preferred Study Term
                            </label>

                            <select
                                name="study_term"
                                id="study_term"
                            >

                                <option value="">
                                    Select term
                                </option>

                                <option value="Spring">
                                    Spring
                                </option>

                                <option value="Summer">
                                    Summer
                                </option>

                                <option value="Fall">
                                    Fall
                                </option>

                            </select>

                        </div>


                    </div>

                </section>



                <!-- =================================================
                           APPLICATION INFORMATION
                ================================================== -->

                <section class="content_box">

                    <h2>
                        Application Information
                    </h2>


                    <div class="form_group full_width">

                        <label for="statement_of_purpose">

                            Statement of Purpose

                        </label>


                        <textarea
                            id="statement_of_purpose"
                            name="statement_of_purpose"
                            rows="6"
                            placeholder="Explain why you want to participate in this exchange program..."
                        ></textarea>

                    </div>

                </section>



                <!-- =================================================
                           DOCUMENT UPLOAD
                ================================================== -->

                <section class="content_box">

                    <h2>
                        Required Documents
                    </h2>


                    <p class="section_description">

                        Upload clear and valid copies of the documents
                        required for your exchange application.

                    </p>


                    <div class="document_list">


                        <!-- Passport -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Passport / National ID
                                </h3>

                                <p>
                                    Upload a clear copy of your passport
                                    or national identification document.
                                </p>

                                <span class="required">
                                    Required
                                </span>

                            </div>


                            <input
                                type="file"
                                name="passport_id"
                                id="passport_id"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >

                        </div>



                        <!-- Photograph -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Passport-size Photograph
                                </h3>

                                <p>
                                    Upload a recent passport-size photograph.
                                </p>

                                <span class="required">
                                    Required
                                </span>

                            </div>


                            <input
                                type="file"
                                name="passport_photo"
                                id="passport_photo"
                                accept=".jpg,.jpeg,.png"
                            >

                        </div>



                        <!-- Transcript -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Academic Transcript
                                </h3>

                                <p>
                                    Upload your latest academic transcript.
                                </p>

                                <span class="required">
                                    Required
                                </span>

                            </div>


                            <input
                                type="file"
                                name="academic_transcript"
                                id="academic_transcript"
                                accept=".pdf"
                            >

                        </div>



                        <!-- Certificate -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Academic Certificate
                                </h3>

                                <p>
                                    Upload your academic or degree certificate.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="academic_certificate"
                                accept=".pdf"
                            >

                        </div>



                        <!-- CV -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    CV / Resume
                                </h3>

                                <p>
                                    Upload your current CV or resume.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="cv"
                                accept=".pdf"
                            >

                        </div>



                        <!-- English Certificate -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    English Language Certificate
                                </h3>

                                <p>
                                    IELTS, TOEFL or another accepted
                                    English language certificate.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="english_certificate"
                                accept=".pdf"
                            >

                        </div>



                        <!-- Recommendation -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Recommendation Letter
                                </h3>

                                <p>
                                    Recommendation letter from an academic authority.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="recommendation_letter"
                                accept=".pdf"
                            >

                        </div>



                        <!-- Financial -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Financial / Bank Statement
                                </h3>

                                <p>
                                    Financial document if required by the program.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="financial_statement"
                                accept=".pdf"
                            >

                        </div>



                        <!-- Medical -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Medical Certificate
                                </h3>

                                <p>
                                    Medical certificate if required by the program.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="medical_certificate"
                                accept=".pdf"
                            >

                        </div>



                        <!-- Other -->

                        <div class="document_row">

                            <div class="document_information">

                                <h3>
                                    Other Supporting Document
                                </h3>

                                <p>
                                    Upload any additional document required
                                    for your application.
                                </p>

                                <span class="optional">
                                    Optional
                                </span>

                            </div>


                            <input
                                type="file"
                                name="other_document"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >

                        </div>


                    </div>

                </section>



                <!-- =================================================
                           DECLARATION
                ================================================== -->

                <section class="content_box">

                    <h2>
                        Declaration
                    </h2>


                    <label class="checkbox_label">

                        <input
                            type="checkbox"
                            name="declaration"
                            id="declaration"
                        >


                        <span>

                            I confirm that the information and documents
                            provided in this application are accurate and
                            authentic.

                        </span>

                    </label>


                    <div class="form_buttons">


                        <button
                            type="submit"
                            class="submit_button"
                        >
                            Submit Application
                        </button>


                        <button
                            type="reset"
                            class="cancel_button"
                        >
                            Cancel
                        </button>


                    </div>


                </section>


            </form>



            <!-- ================= FOOTER ================= -->

             <?php include "footer.php"; ?>


    <!-- ================= CLEAR URL / MESSAGES ================= -->

    <script>

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        let cancelButton =
            document.querySelector(".cancel_button");


        cancelButton.addEventListener("click", function () {

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


</body>

</html>