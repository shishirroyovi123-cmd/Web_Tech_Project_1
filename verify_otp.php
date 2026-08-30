<?php

session_start();


/* =========================================================
   CHECK IF USER CAME FROM FORGOT PASSWORD
========================================================= */

if (
    !isset($_SESSION["reset_user_id"]) ||
    !isset($_SESSION["reset_otp"]) ||
    !isset($_SESSION["otp_expiry"])
) {

    header("Location: forgot_password.php");

    exit();

}


/* =========================================================
   CHECK OTP EXPIRY
========================================================= */

if (time() > $_SESSION["otp_expiry"]) {

    unset($_SESSION["reset_otp"]);
    unset($_SESSION["otp_expiry"]);

    header(
        "Location: forgot_password.php?error="
        . urlencode(
            "OTP has expired. Please request a new OTP."
        )
    );

    exit();

}


$error = "";


/* =========================================================
   VERIFY OTP
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $otp =
        trim($_POST["otp"] ?? "");


    /* ================= EMPTY CHECK ================= */

    if ($otp === "") {

        $error =
            "Please enter the OTP.";

    }


    /* ================= OTP FORMAT CHECK ================= */

    elseif (
        !ctype_digit($otp) ||
        strlen($otp) !== 6
    ) {

        $error =
            "OTP must contain exactly 6 digits.";

    }


    /* ================= OTP MATCH CHECK ================= */

    elseif (
        $otp !== (string) $_SESSION["reset_otp"]
    ) {

        $error =
            "Invalid OTP. Please try again.";

    }


    /* ================= OTP VERIFIED ================= */

    else {

        $_SESSION["otp_verified"] =
            true;


        /* Remove OTP after successful verification */

        unset($_SESSION["reset_otp"]);

        unset($_SESSION["otp_expiry"]);


        /* Go to Reset Password page */

        header(
            "Location: reset_password.php"
        );

        exit();

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


    <title>SEPMS - Verify OTP</title>


    <link
        rel="stylesheet"
        href="verify_otp.css"
    >


</head>


<body>


    <div class="otp-container">


        <div class="otp-box">


            <!-- ================= HEADER ================= -->

            <div class="header">


                <h1>

                    SEPMS

                </h1>


                <p>

                    Student Exchange Program
                    Management System

                </p>


            </div>


            <!-- ================= TITLE ================= -->

            <h2>

                Verify OTP

            </h2>


            <!-- ================= INSTRUCTION ================= -->

            <p class="instruction">

                Enter the 6-digit OTP sent to your
                registered email address.

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


            <!-- ================= OTP FORM ================= -->

            <form
                method="POST"
                action=""
                autocomplete="off"
            >


                <table>


                    <!-- ================= OTP ================= -->

                    <tr>


                        <td>

                            <label for="otp">

                                OTP

                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="otp"
                                name="otp"
                                placeholder="Enter 6-digit OTP"
                                maxlength="6"
                                inputmode="numeric"
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
                                    class="verify-btn"
                                >

                                    Verify OTP

                                </button>


                                <a
                                    href="forgot_password.php"
                                    class="cancel-btn"
                                >

                                    Cancel

                                </a>


                            </div>


                        </td>


                    </tr>


                </table>


            </form>


            <!-- ================= BACK LINK ================= -->

            <div class="login-link">


                <a href="forgot_password.php">

                    Back

                </a>


            </div>


        </div>


    </div>


</body>


</html>