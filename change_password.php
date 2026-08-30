<?php

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
   GET CURRENT USER
========================================================= */

$currentUserID =
    $_SESSION["user_id"];


/* =========================================================
   PROCESS CHANGE PASSWORD
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $current_password =
        $_POST["current_password"] ?? "";


    $new_password =
        $_POST["new_password"] ?? "";


    $confirm_password =
        $_POST["confirm_password"] ?? "";


    /* =====================================================
       EMPTY FIELD CHECK
    ===================================================== */

    if (
        $current_password === "" ||
        $new_password === "" ||
        $confirm_password === ""
    ) {

        header(
            "Location: change_password.php?error="
            . urlencode("All fields are required.")
        );

        exit();

    }


    /* =====================================================
       NEW PASSWORD LENGTH
    ===================================================== */

    if (strlen($new_password) < 6) {

        header(
            "Location: change_password.php?error="
            . urlencode(
                "New password must be at least 6 characters."
            )
        );

        exit();

    }


    /* =====================================================
       CONFIRM PASSWORD CHECK
    ===================================================== */

    if ($new_password !== $confirm_password) {

        header(
            "Location: change_password.php?error="
            . urlencode(
                "New passwords do not match."
            )
        );

        exit();

    }


    /* =====================================================
       ADMIN PASSWORD CHANGE
       Admin is not stored in users table
       Current default password: 123456
    ===================================================== */

    if ($_SESSION["role"] === "admin") {


        /*
           If you want the admin password to permanently
           change, it must be stored somewhere.

           For now this checks the current fixed password.
        */

        if ($current_password !== "123456") {

            header(
                "Location: change_password.php?error="
                . urlencode(
                    "Current password is incorrect."
                )
            );

            exit();

        }


        if ($new_password === "123456") {

            header(
                "Location: change_password.php?error="
                . urlencode(
                    "New password must be different from current password."
                )
            );

            exit();

        }


        /*
           IMPORTANT:
           Your current login.php uses:
           admin / 123456

           Therefore a permanent admin password change
           requires changing how admin credentials are stored.
        */

        header(
            "Location: change_password.php?success="
            . urlencode(
                "Admin password validation successful. Admin password storage must be added for permanent changes."
            )
        );

        exit();

    }


    /* =====================================================
       GET CURRENT USER PASSWORD FROM DATABASE
    ===================================================== */

    $sql = "

        SELECT
            user_id,
            password

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

        header(
            "Location: change_password.php?error="
            . urlencode("Database error.")
        );

        exit();

    }


    /*
       user_id in database is an integer
    */

    $currentUserID =
        (int)$currentUserID;


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


    /* =====================================================
       USER NOT FOUND
    ===================================================== */

    if (mysqli_num_rows($result) !== 1) {

        mysqli_stmt_close($stmt);


        header(
            "Location: change_password.php?error="
            . urlencode("User account not found.")
        );

        exit();

    }


    $user =
        mysqli_fetch_assoc(
            $result
        );


    mysqli_stmt_close(
        $stmt
    );


    /* =====================================================
       CHECK CURRENT PASSWORD
    ===================================================== */

    if (
        !password_verify(
            $current_password,
            $user["password"]
        )
    ) {

        header(
            "Location: change_password.php?error="
            . urlencode("Current password is incorrect.")
        );

        exit();

    }


    /* =====================================================
       CHECK SAME PASSWORD
    ===================================================== */

    if (
        password_verify(
            $new_password,
            $user["password"]
        )
    ) {

        header(
            "Location: change_password.php?error="
            . urlencode(
                "New password must be different from current password."
            )
        );

        exit();

    }


    /* =====================================================
       HASH NEW PASSWORD
    ===================================================== */

    $hashedPassword =
        password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );


    /* =====================================================
       UPDATE PASSWORD IN DATABASE
    ===================================================== */

    $updateSQL = "

        UPDATE users

        SET password = ?

        WHERE user_id = ?

    ";


    $updateStmt =
        mysqli_prepare(
            $conn,
            $updateSQL
        );


    if (!$updateStmt) {

        header(
            "Location: change_password.php?error="
            . urlencode("Could not update password.")
        );

        exit();

    }


    mysqli_stmt_bind_param(
        $updateStmt,
        "si",
        $hashedPassword,
        $currentUserID
    );


    /* =====================================================
       SAVE NEW PASSWORD
    ===================================================== */

    if (
        mysqli_stmt_execute(
            $updateStmt
        )
    ) {

        mysqli_stmt_close(
            $updateStmt
        );


        header(
            "Location: change_password.php?success="
            . urlencode(
                "Password changed successfully."
            )
        );

        exit();

    }


    /* =====================================================
       UPDATE FAILED
    ===================================================== */

    mysqli_stmt_close(
        $updateStmt
    );


    header(
        "Location: change_password.php?error="
        . urlencode(
            "Password could not be changed."
        )
    );

    exit();

}


/* =========================================================
   ERROR MESSAGE
========================================================= */

if (isset($_GET["error"])) {

    $error =
        $_GET["error"];

}


/* =========================================================
   SUCCESS MESSAGE
========================================================= */

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
        SEPMS - Change Password
    </title>


    <link
        rel="stylesheet"
        href="change_password.css"
    >


    <script
        src="change_password.js"
    ></script>

</head>


<body>


<div class="change_container">


    <div class="change_box">


        <!-- ================= HEADER ================= -->

        <div class="header">

            <h1>
                SEPMS
            </h1>

            <p>
                Student Exchange Program Management System
            </p>

        </div>


        <!-- ================= TITLE ================= -->

        <h2>
            Change Password
        </h2>


        <!-- ================= INSTRUCTION ================= -->

        <p class="instruction">

            Enter your current password and create a new password
            for your account.

        </p>


        <!-- ================= JAVASCRIPT ERROR ================= -->

        <p
            id="js_error"
            style="
                color:red;
                text-align:center;
                display:none;
            "
        ></p>


        <!-- ================= PHP ERROR ================= -->

        <?php

        if ($error !== "") {

        ?>

            <p
                id="php_error"
                style="
                    color:red;
                    text-align:center;
                "
            >

                <?php
                echo htmlspecialchars($error);
                ?>

            </p>

        <?php

        }

        ?>


        <!-- ================= PHP SUCCESS ================= -->

        <?php

        if ($success !== "") {

        ?>

            <p
                id="success_message"
                style="
                    color:green;
                    text-align:center;
                "
            >

                <?php
                echo htmlspecialchars($success);
                ?>

            </p>

        <?php

        }

        ?>


        <!-- ================= CHANGE PASSWORD FORM ================= -->

        <form
            method="POST"
            action=""
            onsubmit="return validatePassword();"
            autocomplete="off"
        >


            <table>


                <!-- CURRENT PASSWORD -->

                <tr>

                    <td>

                        <label for="current_password">
                            Current Password
                        </label>

                    </td>


                    <td>

                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            placeholder="Enter current password"
                        >

                    </td>

                </tr>


                <!-- NEW PASSWORD -->

                <tr>

                    <td>

                        <label for="new_password">
                            New Password
                        </label>

                    </td>


                    <td>

                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            placeholder="Enter new password"
                        >

                    </td>

                </tr>


                <!-- CONFIRM PASSWORD -->

                <tr>

                    <td>

                        <label for="confirm_password">
                            Confirm Password
                        </label>

                    </td>


                    <td>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Confirm new password"
                        >

                    </td>

                </tr>


                <!-- BUTTONS -->

                <tr>

                    <td colspan="2">

                        <div class="button_area">


                            <button
                                type="submit"
                                class="change_btn"
                            >

                                Change Password

                            </button>


                            <button
                                type="reset"
                                class="cancel_btn"
                            >

                                Cancel

                            </button>


                        </div>

                    </td>

                </tr>


            </table>


        </form>


        <!-- ================= DASHBOARD LINK ================= -->

        <div class="dashboard_link">


            <?php

            if (
                isset($_SESSION["role"])
                &&
                $_SESSION["role"] === "student"
            ) {

            ?>

                <a href="student_dashboard.php">
                    Back to Dashboard
                </a>

            <?php

            }

            elseif (
                isset($_SESSION["role"])
                &&
                $_SESSION["role"] === "coordinator"
            ) {

            ?>

                <a href="coordinator_dashboard.php">
                    Back to Dashboard
                </a>

            <?php

            }

            else {

            ?>

                <a href="admin_dashboard.php">
                    Back to Dashboard
                </a>

            <?php

            }

            ?>


        </div>


    </div>


</div>


<!-- ================= CLEAR MESSAGES ================= -->

<script>


if (window.location.search !== "") {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );

}


let cancelButton =
    document.querySelector(
        ".cancel_btn"
    );


if (cancelButton) {

    cancelButton.addEventListener(
        "click",
        function () {


            let jsError =
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


            if (jsError) {

                jsError.innerHTML =
                    "";

                jsError.style.display =
                    "none";

            }


            if (phpError) {

                phpError.remove();

            }


            if (successMessage) {

                successMessage.remove();

            }


        }
    );

}


</script>


</body>

</html>