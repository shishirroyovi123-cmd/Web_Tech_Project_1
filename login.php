<?php

session_start();

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];


    /* Check empty fields */

    if (empty($username) || empty($password)) {

        header("Location: login.php?error=All fields are required.");
        exit();

    }


    /* Basic password check */

    elseif (strlen($password) < 6) {

        header("Location: login.php?error=Password must be at least 6 characters.");
        exit();

    }


    /* For now, no database */

    else {

        /* ================= SESSION ================= */

        $_SESSION["user_id"] = $username;
        $_SESSION["username"] = $username;


        /* ================= COOKIE ================= */

        if (isset($_POST["remember"])) {

            setcookie(
                "username",
                $username,
                time() + (86400 * 30)
            );

        }
        else {

            setcookie(
                "username",
                "",
                time() - 3600
            );

        }


        header("Location: login.php?success=Login validation successful.");
        exit();

    }

}


/* ================= COOKIE ================= */

$remembered_username = "";

if (isset($_COOKIE["username"])) {

    $remembered_username = $_COOKIE["username"];

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

    <title>SEPMS - Login</title>

    <link rel="stylesheet"
          href="login.css">

    <!-- Separate JavaScript -->

    <script src="login.js"></script>

</head>


<body>


    <div class="login-container">


        <div class="login-box">


            <!-- ================= HEADER ================= -->

            <div class="header">

                <h1>SEPMS</h1>

                <p>
                    Student Exchange Program Management System
                </p>

            </div>



            <!-- ================= LOGIN TITLE ================= -->

            <h2>Login</h2>



            <!-- ================= JAVASCRIPT ERROR ================= -->

            <p
                id="js_error"
                style="
                    color:red;
                    text-align:center;
                    display:none;
                "
            >
            </p>



            <!-- ================= PHP ERROR ================= -->

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



            <!-- ================= PHP SUCCESS ================= -->

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



            <!-- ================= LOGIN FORM ================= -->

            <form
                method="POST"
                action=""
                onsubmit="return validateLogin();"
                autocomplete="off"
            >

                <table>


                    <!-- Username / User ID -->

                    <tr>

                        <td>

                            <label for="username">
                                Username or User ID
                            </label>

                        </td>


                        <td>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="<?php echo $remembered_username; ?>"
                                placeholder="Enter username or user ID"
                                autocomplete="username"
                            >

                        </td>

                    </tr>



                    <!-- Password -->

                    <tr>

                        <td>

                            <label for="password">
                                Password
                            </label>

                        </td>


                        <td>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                            >

                        </td>

                    </tr>



                    <!-- Remember Me -->

                    <tr>

                        <td colspan="2">

                            <div class="remember-me">

                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    value="yes"
                                >

                                <label for="remember">
                                    Remember Me
                                </label>

                            </div>

                        </td>

                    </tr>



                    <!-- Forgot Password -->

                    <tr>

                        <td colspan="2">

                            <div class="forgot-password">

                                <a href="forgot_password.php">
                                    Forgot Password?
                                </a>

                            </div>

                        </td>

                    </tr>



                    <!-- Login and Cancel -->

                    <tr>

                        <td colspan="2">

                            <div class="button-area">

                                <button
                                    type="submit"
                                    class="login-btn"
                                >
                                    Login
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



            <!-- ================= REGISTER LINK ================= -->

            <div class="register-link">

                <span>
                    Don't have an account?
                </span>

                <a href="register.php">
                    Create an Account
                </a>

            </div>


        </div>


    </div>



    <!-- ================= CLEAR MESSAGE ================= -->

    <script>

        /*
            Remove ?error or ?success
            from the URL.
        */

        if (window.location.search != "") {

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );

        }


        /*
            Clear message when Cancel
            button is clicked.
        */

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