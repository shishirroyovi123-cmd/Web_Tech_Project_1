<?php

/* =========================================================
   START SESSION
========================================================= */

session_start();


/* =========================================================
   DATABASE CONNECTION
========================================================= */

require_once __DIR__ . "/config/db.php";


$error = "";
$success = "";


/* =========================================================
   LOGIN CHECK
========================================================= */

if (!isset($_SESSION["user_id"])) {

    header(
        "Location: login.php?error="
        . urlencode("Please login first.")
    );

    exit();

}


/* =========================================================
   GET LOGGED-IN USER ID
========================================================= */

$currentUserID =
    (int) $_SESSION["user_id"];


/* =========================================================
   ADMIN CHECK
========================================================= */

if (
    isset($_SESSION["role"])
    &&
    $_SESSION["role"] === "admin"
) {

    header(
        "Location: admin_dashboard.php"
    );

    exit();

}


/* =========================================================
   PROCESS UPDATE PROFILE
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* ================= GET FORM DATA ================= */

    $email =
        trim($_POST["email"] ?? "");


    $full_name =
        trim($_POST["full_name"] ?? "");


    /* ================= EMPTY CHECK ================= */

    if (
        $email === ""
        ||
        $full_name === ""
    ) {

        $error =
            "All fields are required.";

    }


    /* ================= EMAIL VALIDATION ================= */

    elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $error =
            "Please enter a valid email address.";

    }


    /* ================= NAME VALIDATION ================= */

    elseif (
        !preg_match(
            "/^[A-Za-z ]+$/",
            $full_name
        )
    ) {

        $error =
            "Full name must contain letters only.";

    }


    /* ================= CHECK EMAIL ================= */

    else {


        $checkEmailSQL = "

            SELECT user_id

            FROM users

            WHERE email = ?
            AND user_id != ?

            LIMIT 1

        ";


        $checkStmt =
            mysqli_prepare(
                $conn,
                $checkEmailSQL
            );


        if (!$checkStmt) {

            $error =
                "Database error.";

        }

        else {


            mysqli_stmt_bind_param(
                $checkStmt,
                "si",
                $email,
                $currentUserID
            );


            mysqli_stmt_execute(
                $checkStmt
            );


            mysqli_stmt_store_result(
                $checkStmt
            );


            if (
                mysqli_stmt_num_rows(
                    $checkStmt
                ) > 0
            ) {

                $error =
                    "This email is already registered.";

            }


            mysqli_stmt_close(
                $checkStmt
            );


            /* ================= UPDATE PROFILE ================= */

            if ($error === "") {


                $updateSQL = "

                    UPDATE users

                    SET
                        name = ?,
                        email = ?

                    WHERE user_id = ?

                ";


                $updateStmt =
                    mysqli_prepare(
                        $conn,
                        $updateSQL
                    );


                if (!$updateStmt) {

                    $error =
                        "Could not prepare profile update.";

                }

                else {


                    mysqli_stmt_bind_param(
                        $updateStmt,
                        "ssi",
                        $full_name,
                        $email,
                        $currentUserID
                    );


                    if (
                        mysqli_stmt_execute(
                            $updateStmt
                        )
                    ) {


                        /* Update session data */

                        $_SESSION["name"] =
                            $full_name;


                        $_SESSION["email"] =
                            $email;


                        $success =
                            "Profile updated successfully.";

                    }

                    else {

                        $error =
                            "Profile could not be updated.";

                    }


                    mysqli_stmt_close(
                        $updateStmt
                    );

                }

            }

        }

    }

}


/* =========================================================
   GET CURRENT USER INFORMATION
========================================================= */

$sql = "

    SELECT
        user_id,
        name,
        email,
        username,
        role

    FROM users

    WHERE user_id = ?

    LIMIT 1

";


$stmt =
    mysqli_prepare(
        $conn,
        $sql
    );


if (!$stmt) {

    die(
        "Database error."
    );

}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $currentUserID
);


mysqli_stmt_execute(
    $stmt
);


$result =
    mysqli_stmt_get_result(
        $stmt
    );


/* ================= USER NOT FOUND ================= */

if (
    mysqli_num_rows($result) !== 1
) {

    mysqli_stmt_close(
        $stmt
    );


    session_destroy();


    header(
        "Location: login.php?error="
        . urlencode("User account not found.")
    );

    exit();

}


/* ================= GET USER DATA ================= */

$user =
    mysqli_fetch_assoc(
        $result
    );


mysqli_stmt_close(
    $stmt
);


/* =========================================================
   ESCAPE DATA FOR HTML
========================================================= */

$userID =
    htmlspecialchars(
        $user["user_id"]
    );


$username =
    htmlspecialchars(
        $user["username"]
    );


$role =
    htmlspecialchars(
        ucfirst($user["role"])
    );


$emailValue =
    htmlspecialchars(
        $user["email"]
    );


$nameValue =
    htmlspecialchars(
        $user["name"]
    );


/* =========================================================
   BACK TO DASHBOARD LINK
========================================================= */

$dashboardLink = "";

if ($user["role"] === "student") {

    $dashboardLink =
        "student_dashboard.php";

}

elseif ($user["role"] === "coordinator") {

    $dashboardLink =
        "coordinator_dashboard.php";

}

else {

    $dashboardLink =
        "login.php";

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
        SEPMS - Update Profile
    </title>


    <link
        rel="stylesheet"
        href="update_profile.css"
    >


</head>


<body>


<div class="profile_container">


    <div class="profile_form_box">


        <!-- ================= TITLE ================= -->

        <h1>
            Update Profile
        </h1>


        <p class="description">

            Update your account information.

        </p>


        <!-- ================= ERROR MESSAGE ================= -->

        <?php if ($error !== "") { ?>

            <p
                id="error_message"
                class="error_message"
            >

                <?php
                echo htmlspecialchars($error);
                ?>

            </p>

        <?php } ?>


        <!-- ================= SUCCESS MESSAGE ================= -->

        <?php if ($success !== "") { ?>

            <p
                id="success_message"
                class="success_message"
            >

                <?php
                echo htmlspecialchars($success);
                ?>

            </p>

        <?php } ?>


        <!-- ================= UPDATE FORM ================= -->

        <form
            id="profile_form"
            action=""
            method="POST"
            autocomplete="off"
        >


            <!-- ================= ACCOUNT INFORMATION ================= -->

            <div class="section_title">

                Account Information

            </div>


            <div class="form_row">


                <!-- USER ID -->

                <div class="form_group">

                    <label for="user_id">
                        User ID
                    </label>


                    <input
                        type="text"
                        id="user_id"
                        value="<?php echo $userID; ?>"
                        readonly
                    >


                </div>


                <!-- USERNAME -->

                <div class="form_group">

                    <label for="username">
                        Username
                    </label>


                    <input
                        type="text"
                        id="username"
                        value="<?php echo $username; ?>"
                        readonly
                    >


                </div>


            </div>


            <div class="form_row">


                <!-- ROLE -->

                <div class="form_group">

                    <label for="role">
                        Role
                    </label>


                    <input
                        type="text"
                        id="role"
                        value="<?php echo $role; ?>"
                        readonly
                    >


                </div>


                <!-- EMAIL -->

                <div class="form_group">

                    <label for="email">
                        Email Address
                    </label>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $emailValue; ?>"
                        placeholder="Enter your email"
                        required
                    >


                </div>


            </div>


            <!-- ================= PERSONAL INFORMATION ================= -->

            <div class="section_title">

                Personal Information

            </div>


            <div class="form_row">


                <!-- FULL NAME -->

                <div class="form_group">

                    <label for="full_name">
                        Full Name
                    </label>


                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="<?php echo $nameValue; ?>"
                        placeholder="Enter your full name"
                        required
                    >


                </div>


            </div>


            <!-- ================= BUTTONS ================= -->

            <div class="form_buttons">


                <!-- UPDATE -->

                <button
                    type="submit"
                    class="update_button"
                >

                    Update Profile

                </button>


                <!-- CANCEL -->

                <button
                    type="button"
                    class="cancel_button"
                    id="cancel_button"
                >

                    Cancel

                </button>


                <!-- BACK TO DASHBOARD -->

                <a
                    href="<?php echo $dashboardLink; ?>"
                    class="back_button"
                >

                    Back to Dashboard

                </a>


            </div>


        </form>


    </div>


</div>


<script>


/* =========================================================
   CANCEL BUTTON
   RESTORE ORIGINAL DATABASE VALUES
========================================================= */

let cancelButton =
    document.getElementById(
        "cancel_button"
    );


if (cancelButton) {

    cancelButton.addEventListener(
        "click",
        function () {


            /* Restore email */

            document.getElementById(
                "email"
            ).value =
                <?php
                echo json_encode(
                    $user["email"]
                );
                ?>;


            /* Restore full name */

            document.getElementById(
                "full_name"
            ).value =
                <?php
                echo json_encode(
                    $user["name"]
                );
                ?>;


            /* Remove error */

            let errorMessage =
                document.getElementById(
                    "error_message"
                );


            if (errorMessage) {

                errorMessage.remove();

            }


            /* Remove success */

            let successMessage =
                document.getElementById(
                    "success_message"
                );


            if (successMessage) {

                successMessage.remove();

            }


        }
    );

}


</script>


</body>

</html>