<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recovery = trim($_POST["recovery"]);


    /* Check empty field */

    if (empty($recovery)) {

        header("Location: forgot_password.php?error=All fields are required.");
        exit();

    }


    /* Check email or phone */

    elseif (filter_var($recovery, FILTER_VALIDATE_EMAIL)) {

        header("Location: forgot_password.php?success=OTP request submitted successfully.");
        exit();

    }


    elseif (preg_match("/^[0-9]{10,15}$/", $recovery)) {

        header("Location: forgot_password.php?success=OTP request submitted successfully.");
        exit();

    }


    else {

        header("Location: forgot_password.php?error=Please enter a valid email or phone number.");
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


    <title>SEPMS - Forgot Password</title>


    <link rel="stylesheet"
          href="forgot_password.css">


    <!-- Separate JavaScript -->

    <script src="forgot_password.js"></script>

</head>


<body>


    <div class="forgot-container">


        <div class="forgot-box">


            <!-- Header -->

            <div class="header">

                <h1>
                    SEPMS
                </h1>

                <p>
                    Student Exchange Program Management System
                </p>

            </div>


            <!-- Title -->

            <h2>
                Forgot Password
            </h2>


            <p class="instruction">

                Enter your registered email address or phone number.
                We will send an OTP to verify your account.

            </p>


            <!-- JavaScript Error -->

            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            >
            </p>


            <!-- PHP Error -->

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


            <!-- PHP Success -->

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


            <form
                method="POST"
                action=""
                onsubmit="return validateForgotPassword();"
                autocomplete="off"
            >


                <table>


                    <!-- Email / Phone -->

                    <tr>

                        <td>

                            <label for="recovery">

                                Email or Phone

                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="recovery"
                                name="recovery"
                                placeholder="Enter email or phone number"
                            >

                        </td>

                    </tr>


                    <!-- Buttons -->

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


            <!-- Login Link -->

            <div class="login-link">

                Remember your password?

                <a href="login.php">

                    Login

                </a>

            </div>


            <!-- Registration Link -->

            <div class="register-link">

                Don't have an account?

                <a href="register.php">

                    Create an Account

                </a>

            </div>


        </div>


    </div>



    <!-- Clear URL and Messages -->

    <script>

        /* Remove error/success from URL */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        /* Clear message when Cancel is clicked */

        let cancelButton =
            document.querySelector(".cancel-btn");


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