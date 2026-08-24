<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];


    /* Check empty fields */

    if (
        empty($current_password) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {

        header("Location: change_password.php?error=All fields are required.");
        exit();

    }


    /* Check new password length */

    elseif (strlen($new_password) < 6) {

        header("Location: change_password.php?error=New password must be at least 6 characters.");
        exit();

    }


    /* Check password match */

    elseif ($new_password != $confirm_password) {

        header("Location: change_password.php?error=New passwords do not match.");
        exit();

    }


    /* Check same password */

    elseif ($current_password == $new_password) {

        header("Location: change_password.php?error=New password must be different from current password.");
        exit();

    }


    /* Successful validation */

    else {

        header("Location: change_password.php?success=Password changed successfully.");
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


    <title>SEPMS - Change Password</title>


    <link rel="stylesheet"
          href="change_password.css">


    <!-- JavaScript -->

    <script src="change_password.js"></script>

</head>


<body>


    <div class="change_container">


        <div class="change_box">


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
                Change Password
            </h2>


            <!-- Instruction -->

            <p class="instruction">

                Enter your current password and create a new password
                for your account.

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


            <!-- Change Password Form -->

            <form
                method="POST"
                action=""
                onsubmit="return validatePassword();"
                autocomplete="off"
            >


                <table>


                    <!-- Current Password -->

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



                    <!-- New Password -->

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



                    <!-- Confirm Password -->

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



                    <!-- Buttons -->

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


            <!-- Dashboard Link -->

            <div class="dashboard_link">


                <a href="admin_dashboard.php">

                    Back to Dashboard

                </a>


            </div>


        </div>


    </div>



    <!-- Clear URL and Messages -->

    <script>


        /* Remove ?error or ?success from URL */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        /* Clear message when Cancel is clicked */

        let cancelButton =
            document.querySelector(".cancel_btn");


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