<?php

session_start();


/* ================= PHPMailer ================= */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/vendor/autoload.php";


/* ================= DATABASE ================= */

require_once __DIR__ . "/config/db.php";


$error = "";


/* ================= PROCESS FORM ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email =
        trim($_POST["recovery"] ?? "");


    /* ================= EMPTY CHECK ================= */

    if ($email === "") {

        header(
            "Location: forgot_password.php?error="
            . urlencode("Email is required.")
        );

        exit();

    }


    /* ================= EMAIL VALIDATION ================= */

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header(
            "Location: forgot_password.php?error="
            . urlencode("Please enter a valid email address.")
        );

        exit();

    }


    /* ================= CHECK EMAIL IN DATABASE ================= */

    $sql = "
        SELECT
            user_id,
            name,
            email
        FROM users
        WHERE email = ?
        LIMIT 1
    ";


    $stmt =
        mysqli_prepare(
            $conn,
            $sql
        );


    if (!$stmt) {

        header(
            "Location: forgot_password.php?error="
            . urlencode("Database error.")
        );

        exit();

    }


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );


    mysqli_stmt_execute($stmt);


    $result =
        mysqli_stmt_get_result($stmt);


    /* ================= ACCOUNT NOT FOUND ================= */

    if (mysqli_num_rows($result) === 0) {

        mysqli_stmt_close($stmt);


        header(
            "Location: forgot_password.php?error="
            . urlencode(
                "No account found with this email address."
            )
        );

        exit();

    }


    /* ================= GET USER DATA ================= */

    $user =
        mysqli_fetch_assoc($result);


    mysqli_stmt_close($stmt);


    /* ================= GENERATE OTP ================= */

    $otp =
        random_int(
            100000,
            999999
        );


    /* ================= SAVE RESET INFORMATION ================= */

    $_SESSION["reset_user_id"] =
        $user["user_id"];


    $_SESSION["reset_name"] =
        $user["name"];


    $_SESSION["reset_email"] =
        $user["email"];


    $_SESSION["reset_otp"] =
        $otp;


    /* OTP VALID FOR 5 MINUTES */

    $_SESSION["otp_expiry"] =
        time() + 300;


    /* ================= SEND OTP EMAIL ================= */

    try {


        $mail =
            new PHPMailer(true);


        /* ================= SMTP SETTINGS ================= */

        $mail->isSMTP();


        $mail->Host =
            "smtp.gmail.com";


        $mail->SMTPAuth =
            true;


        /* ================= YOUR GMAIL ================= */

        $mail->Username =
            "shishirroyovi123@gmail.com";


        /* ================= GMAIL APP PASSWORD ================= */

        $mail->Password =
            "hslb vqye uysc lsxe";


        /* ================= ENCRYPTION ================= */

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;


        $mail->Port =
            587;


        /* ================= EMAIL SENDER ================= */

        $mail->setFrom(
            "shishirroyovi123@gmail.com",
            "SEPMS"
        );


        /* ================= EMAIL RECEIVER ================= */

        $mail->addAddress(
            $user["email"],
            $user["name"]
        );


        /* ================= EMAIL FORMAT ================= */

        $mail->isHTML(true);


        /* ================= EMAIL SUBJECT ================= */

        $mail->Subject =
            "SEPMS Password Reset OTP";


        /* ================= EMAIL BODY ================= */

        $mail->Body = "

            <h2>SEPMS Password Reset</h2>

            <p>
                Hello " .
                htmlspecialchars($user["name"]) .
                ",
            </p>

            <p>
                Your password reset OTP is:
            </p>

            <h1
                style='
                    letter-spacing:5px;
                '
            >
                " .
                $otp .
                "
            </h1>

            <p>
                This OTP will expire in
                <strong>5 minutes</strong>.
            </p>

            <p>
                Do not share this OTP with anyone.
            </p>

        ";


        /* ================= PLAIN TEXT EMAIL ================= */

        $mail->AltBody =
            "Hello " .
            $user["name"] .
            ". Your SEPMS Password Reset OTP is: " .
            $otp .
            ". This OTP will expire in 5 minutes.";


        /* ================= SEND EMAIL ================= */

        $mail->send();


        /* ================= GO TO VERIFY OTP ================= */

        header(
            "Location: verify_otp.php"
        );

        exit();


    }


    /* ================= EMAIL ERROR ================= */

   catch (Exception $e) {

    $errorMessage =
        "Mailer Error: " . $mail->ErrorInfo;

    header(
        "Location: forgot_password.php?error="
        . urlencode($errorMessage)
    );

    exit();

}

}


/* ================= GET ERROR ================= */

if (isset($_GET["error"])) {

    $error =
        $_GET["error"];

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


    <title>SEPMS - Forgot Password</title>


    <link
        rel="stylesheet"
        href="forgot_password.css"
    >


    <script
        src="forgot_password.js"
    ></script>


</head>


<body>


    <div class="forgot-container">


        <div class="forgot-box">


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

                Forgot Password

            </h2>


            <!-- ================= INSTRUCTION ================= -->

            <p class="instruction">

                Enter your registered email address.
                We will send an OTP to verify your account.

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

            if ($error != "") {

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
                onsubmit="return validateForgotPassword();"
                autocomplete="off"
            >


                <table>


                    <!-- ================= EMAIL ================= -->

                    <tr>


                        <td>

                            <label for="recovery">

                                Email

                            </label>

                        </td>


                        <td>

                            <input
                                type="email"
                                id="recovery"
                                name="recovery"
                                placeholder="Enter your registered email"
                            >

                        </td>


                    </tr>


                    <!-- ================= BUTTONS ================= -->

                    <tr>


                        <td colspan="2">


                            <div class="button-area">


                                <button
                                    type="submit"
                                    class="send-btn"
                                >

                                    Send OTP

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

                Remember your password?

                <a href="login.php">

                    Login

                </a>

            </div>


            <!-- ================= REGISTER LINK ================= -->

            <div class="register-link">

                Don't have an account?

                <a href="register.php">

                    Create an Account

                </a>

            </div>


        </div>


    </div>


    <!-- ================= CLEAR ERROR ================= -->

    <script>


        let cancelButton =
            document.querySelector(".cancel-btn");


        cancelButton.addEventListener(
            "click",
            function () {


                let jsError =
                    document.getElementById("js_error");


                let phpError =
                    document.getElementById("php_error");


                if (jsError) {

                    jsError.innerHTML = "";

                    jsError.style.display = "none";

                }


                if (phpError) {

                    phpError.remove();

                }


            }
        );


    </script>


</body>

</html>