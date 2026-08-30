<?php

session_start();

require_once __DIR__ . "/config/db.php";


/* =========================================================
   CHECK OTP VERIFICATION
========================================================= */

if (
    !isset($_SESSION["reset_user_id"]) ||
    !isset($_SESSION["otp_verified"]) ||
    $_SESSION["otp_verified"] !== true
) {

    header("Location: forgot_password.php");

    exit();

}


$error = "";


/* =========================================================
   PROCESS NEW PASSWORD
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $password =
        $_POST["password"] ?? "";


    $confirmPassword =
        $_POST["confirm_password"] ?? "";


    /* ================= EMPTY CHECK ================= */

    if (
        $password === "" ||
        $confirmPassword === ""
    ) {

        $error =
            "All fields are required.";

    }


    /* ================= PASSWORD LENGTH ================= */

    elseif (strlen($password) < 6) {

        $error =
            "Password must be at least 6 characters.";

    }


    /* ================= PASSWORD MATCH ================= */

    elseif ($password !== $confirmPassword) {

        $error =
            "Passwords do not match.";

    }


    /* ================= UPDATE PASSWORD ================= */

    else {


        $hashedPassword =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );


        $userID =
            $_SESSION["reset_user_id"];


        $sql = "
            UPDATE users
            SET password = ?
            WHERE user_id = ?
        ";


        $stmt =
            mysqli_prepare(
                $conn,
                $sql
            );


        if (!$stmt) {

            $error =
                "Database error. Please try again.";

        }


        else {


            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $hashedPassword,
                $userID
            );


            /* ================= PASSWORD UPDATED ================= */

            if (mysqli_stmt_execute($stmt)) {


                mysqli_stmt_close($stmt);


                /* Remove password recovery session */

                unset(
                    $_SESSION["reset_user_id"]
                );


                unset(
                    $_SESSION["reset_name"]
                );


                unset(
                    $_SESSION["reset_email"]
                );


                unset(
                    $_SESSION["reset_otp"]
                );


                unset(
                    $_SESSION["otp_expiry"]
                );


                unset(
                    $_SESSION["otp_verified"]
                );


                /* Go to login */

                header(
                    "Location: login.php?success="
                    . urlencode(
                        "Password reset successfully. Please login with your new password."
                    )
                );

                exit();


            }


            else {

                $error =
                    "Password could not be updated. Please try again.";

            }


            mysqli_stmt_close($stmt);


        }


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


    <title>SEPMS - Reset Password</title>


    <link
        rel="stylesheet"
        href="reset_password.css"
    >


</head>


<body>


    <div class="reset-container">


        <div class="reset-box">


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

                Reset Password

            </h2>


            <!-- ================= INSTRUCTION ================= -->

            <p class="instruction">

                Please enter your new password below.

            </p>


            <!-- ================= ERROR MESSAGE ================= -->

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


            <!-- ================= FORM ================= -->

            <form
                method="POST"
                action=""
                autocomplete="off"
            >


                <table>


                    <!-- ================= NEW PASSWORD ================= -->

                    <tr>


                        <td>

                            <label for="password">

                                New Password

                            </label>

                        </td>


                        <td>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter new password"
                                minlength="6"
                                required
                            >

                        </td>


                    </tr>


                    <!-- ================= CONFIRM PASSWORD ================= -->

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
                                minlength="6"
                                required
                            >

                        </td>


                    </tr>


                    <!-- ================= BUTTONS ================= -->

                    <tr>


                        <td colspan="2">


                            <div class="button-area">


                                <button
                                    type="submit"
                                    class="reset-btn"
                                >

                                    Reset Password

                                </button>


                                <button
                                    type="reset"
                                    class="cancel-btn"
                                >

                                    Cancel

                                </button>


                            </div>


                        </td>


                    </tr>


                </table>


            </form>


            <!-- ================= LOGIN LINK ================= -->

            <div class="login-link">


                <a href="login.php">

                    Back to Login

                </a>


            </div>


        </div>


    </div>


</body>


</html>