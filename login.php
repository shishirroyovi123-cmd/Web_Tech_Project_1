<?php

session_start();


/* =========================================================
   DATABASE
========================================================= */

require_once __DIR__ . "/config/db.php";


$error = "";
$success = "";


/* =========================================================
   LOGIN PROCESS
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $username =
        trim($_POST["username"] ?? "");


    $password =
        $_POST["password"] ?? "";


    /* =====================================================
       EMPTY FIELD CHECK
    ===================================================== */

    if ($username === "" || $password === "") {

        header(
            "Location: login.php?error="
            . urlencode("All fields are required.")
        );

        exit();

    }


    /* =====================================================
       ADMIN LOGIN
       Username: admin
       Password: 123456
    ===================================================== */

    if ($username === "admin" && $password === "123456") {


        $_SESSION["user_id"] =
            "admin";


        $_SESSION["name"] =
            "Admin";


        $_SESSION["username"] =
            "admin";


        $_SESSION["role"] =
            "admin";


        /* =================================================
           REMEMBER ME
        ================================================= */

        if (isset($_POST["remember"])) {

            setcookie(
                "remember_login",
                $username,
                time() + (86400 * 30),
                "/"
            );

        }

        else {

            setcookie(
                "remember_login",
                "",
                time() - 3600,
                "/"
            );

        }


        header(
            "Location: admin_dashboard.php"
        );

        exit();

    }


    /* =====================================================
       FIND STUDENT OR COORDINATOR
       LOGIN USING USERNAME OR USER ID
    ===================================================== */

    $sql = "

        SELECT
            user_id,
            name,
            email,
            username,
            password,
            role

        FROM users

        WHERE
            username = ?
            OR user_id = ?

        LIMIT 1

    ";


    $stmt =
        mysqli_prepare(
            $conn,
            $sql
        );


    if (!$stmt) {

        header(
            "Location: login.php?error="
            . urlencode("Database error.")
        );

        exit();

    }


    /* =====================================================
       USER ID CHECK

       If login input contains only numbers,
       search using User ID.

       Otherwise User ID = 0.
    ===================================================== */

    $userID =
        0;


    if (ctype_digit($username)) {

        $userID =
            (int)$username;

    }


    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $username,
        $userID
    );


    mysqli_stmt_execute(
        $stmt
    );


    $result =
        mysqli_stmt_get_result(
            $stmt
        );


    /* =====================================================
       USER FOUND
    ===================================================== */

    if (
        mysqli_num_rows($result) == 1
    ) {


        $user =
            mysqli_fetch_assoc(
                $result
            );


        /* =================================================
           PASSWORD CHECK
        ================================================= */

        if (
            password_verify(
                $password,
                $user["password"]
            )
        ) {


            /* =============================================
               CREATE SESSION
            ============================================= */

            $_SESSION["user_id"] =
                $user["user_id"];


            $_SESSION["name"] =
                $user["name"];


            $_SESSION["username"] =
                $user["username"];


            $_SESSION["role"] =
                $user["role"];


            /* =============================================
               REMEMBER ME
            ============================================= */

            if (isset($_POST["remember"])) {


                /*
                   Save exactly what the user typed.

                   If user logs in with:
                   Username -> save username

                   If user logs in with:
                   User ID -> save User ID
                */

                setcookie(
                    "remember_login",
                    $username,
                    time() + (86400 * 30),
                    "/"
                );

            }

            else {


                /* Remove cookie */

                setcookie(
                    "remember_login",
                    "",
                    time() - 3600,
                    "/"
                );


                unset(
                    $_COOKIE["remember_login"]
                );

            }


            mysqli_stmt_close(
                $stmt
            );


            /* =============================================
               ROLE BASED REDIRECT
            ============================================= */

            if (
                $user["role"] === "student"
            ) {

                header(
                    "Location: student_dashboard.php"
                );

                exit();

            }


            if (
                $user["role"] === "coordinator"
            ) {

                header(
                    "Location: coordinator_dashboard.php"
                );

                exit();

            }


            /* =============================================
               INVALID ROLE
            ============================================= */

            session_destroy();


            header(
                "Location: login.php?error="
                . urlencode("Invalid user role.")
            );

            exit();

        }


        /* =================================================
           INCORRECT PASSWORD
        ================================================= */

        else {

            mysqli_stmt_close(
                $stmt
            );


            header(
                "Location: login.php?error="
                . urlencode("Incorrect password.")
            );

            exit();

        }

    }


    /* =====================================================
       USER NOT FOUND
    ===================================================== */

    else {

        mysqli_stmt_close(
            $stmt
        );


        header(
            "Location: login.php?error="
            . urlencode(
                "Username or User ID not found."
            )
        );

        exit();

    }

}


/* =========================================================
   REMEMBER ME COOKIE
========================================================= */

$remembered_login =
    "";


if (
    isset($_COOKIE["remember_login"])
    &&
    $_COOKIE["remember_login"] !== ""
) {

    $remembered_login =
        $_COOKIE["remember_login"];

}


/* =========================================================
   ERROR MESSAGE
========================================================= */

if (
    isset($_GET["error"])
) {

    $error =
        $_GET["error"];

}


/* =========================================================
   SUCCESS MESSAGE
========================================================= */

if (
    isset($_GET["success"])
) {

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
        SEPMS - Login
    </title>


    <link
        rel="stylesheet"
        href="login.css"
    >


    <script
        src="login.js"
    ></script>

</head>


<body>


<div class="login-container">


    <div class="login-box">


        <!-- =================================================
             HEADER
        ================================================= -->

        <div class="header">

            <h1>
                SEPMS
            </h1>

            <p>
                Student Exchange Program Management System
            </p>

        </div>


        <!-- =================================================
             LOGIN TITLE
        ================================================= -->

        <h2>
            Login
        </h2>


        <!-- =================================================
             JAVASCRIPT ERROR
        ================================================= -->

        <p
            id="js_error"
            style="
                color:red;
                text-align:center;
                display:none;
            "
        ></p>


        <!-- =================================================
             PHP ERROR
        ================================================= -->

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


        <!-- =================================================
             PHP SUCCESS
        ================================================= -->

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


        <!-- =================================================
             LOGIN FORM
        ================================================= -->

        <form
            method="POST"
            action=""
            onsubmit="return validateLogin();"
            autocomplete="off"
        >


            <table>


                <!-- =========================================
                     USERNAME / USER ID
                ========================================== -->

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
                            value="<?php echo htmlspecialchars($remembered_login); ?>"
                            placeholder="Enter username or user ID"
                            autocomplete="username"
                        >

                    </td>

                </tr>


                <!-- =========================================
                     PASSWORD
                ========================================== -->

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


                <!-- =========================================
                     REMEMBER ME
                ========================================== -->

                <tr>

                    <td colspan="2">

                        <div class="remember-me">

                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="yes"
                                <?php

                                if (
                                    $remembered_login !== ""
                                ) {

                                    echo "checked";

                                }

                                ?>
                            >

                            <label for="remember">

                                Remember Me

                            </label>

                        </div>

                    </td>

                </tr>


                <!-- =========================================
                     FORGOT PASSWORD
                ========================================== -->

                <tr>

                    <td colspan="2">

                        <div class="forgot-password">

                            <a href="forgot_password.php">

                                Forgot Password?

                            </a>

                        </div>

                    </td>

                </tr>


                <!-- =========================================
                     BUTTONS
                ========================================== -->

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


        <!-- =================================================
             REGISTER LINK
        ================================================= -->

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


<script>


/* =========================================================
   REMOVE URL PARAMETERS
========================================================= */

if (window.location.search !== "") {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );

}


/* =========================================================
   CLEAR MESSAGE ON CANCEL
========================================================= */

let cancelButton =
    document.querySelector(
        ".cancel-btn"
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