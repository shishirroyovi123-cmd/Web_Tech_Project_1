<?php

$success = "";
$error = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $user_id = trim($_POST["user_id"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];


    /* ================= EMPTY FIELD CHECK ================= */

    if (
        empty($name) ||
        empty($user_id) ||
        empty($email) ||
        empty($username) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($role)
    ) {

        header("Location: register.php?error=All fields are required.");
        exit();

    }


    /* ================= NAME CHECK ================= */

    elseif (!preg_match("/^[A-Z][a-zA-Z ]*$/", $name)) {

        header("Location: register.php?error=Name must start with an uppercase letter.");
        exit();

    }


    /* ================= EMAIL CHECK ================= */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header("Location: register.php?error=Please enter a valid email address.");
        exit();

    }


    /* ================= PASSWORD LENGTH ================= */

    elseif (strlen($password) < 6) {

        header("Location: register.php?error=Password must be at least 6 characters.");
        exit();

    }


    /* ================= PASSWORD MATCH ================= */

    elseif ($password != $confirm_password) {

        header("Location: register.php?error=Passwords do not match.");
        exit();

    }


    /* ================= SUCCESS ================= */

    else {

        header("Location: register.php?success=1");
        exit();

    }

}


/* ================= SUCCESS MESSAGE ================= */

if (isset($_GET["success"])) {

    $success = "Registration Successful!";

}


/* ================= ERROR MESSAGE ================= */

if (isset($_GET["error"])) {

    $error = $_GET["error"];

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">


    <title>SEPMS - Registration</title>


    <link rel="stylesheet"
          href="register.css">


    <!-- JavaScript -->

    <script src="register.js"></script>

</head>


<body>


<div class="register-container">


    <div class="register-box">


        <!-- ================= HEADER ================= -->

        <div class="header">

            <h1>
                SEPMS
            </h1>

            <p>
                Student Exchange Program Management System
            </p>

        </div>


        <h2>
            Create Account
        </h2>


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
                id='php_message'
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


        <!-- ================= FORM ================= -->

        <form
            method="POST"
            action=""
            onsubmit="return validateForm();"
            autocomplete="off"
        >


            <table>


                <!-- ================= NAME ================= -->

                <tr>

                    <td>

                        <label for="name">
                            Name
                        </label>

                    </td>


                    <td>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                        >

                    </td>

                </tr>


                <!-- ================= USER ID ================= -->

                <tr>

                    <td>

                        <label for="user_id">
                            User ID
                        </label>

                    </td>


                    <td>

                        <input
                            type="text"
                            id="user_id"
                            name="user_id"
                            placeholder="Enter your user ID"
                        >

                    </td>

                </tr>


                <!-- ================= EMAIL ================= -->

                <tr>

                    <td>

                        <label for="email">
                            Email
                        </label>

                    </td>


                    <td>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                        >

                    </td>

                </tr>


                <!-- ================= USERNAME ================= -->

                <tr>

                    <td>

                        <label for="username">
                            Username
                        </label>

                    </td>


                    <td>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Create a username"
                        >

                    </td>

                </tr>


                <!-- ================= PASSWORD ================= -->

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
                            placeholder="Create a password"
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
                            placeholder="Re-enter your password"
                        >

                    </td>

                </tr>


                <!-- ================= ROLE ================= -->

                <tr>

                    <td>

                        <label for="role">
                            Role
                        </label>

                    </td>


                    <td>

                        <select
                            id="role"
                            name="role"
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Select your role
                            </option>


                            <option value="student">
                                Student
                            </option>


                            <option value="coordinator">
                                Coordinator
                            </option>


                        </select>

                    </td>

                </tr>


                <!-- ================= BUTTONS ================= -->

                <tr>

                    <td colspan="2">


                        <div class="button-area">


                            <button
                                type="submit"
                                class="confirm-btn"
                            >
                                Confirm
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


        <!-- ================= LOGIN ================= -->

        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>


    </div>


</div>


<!-- ================= CLEAR MESSAGE / URL ================= -->

<script>

    /*
       Remove ?success or ?error
       from the URL after loading.
    */

    if (window.location.search != "") {

        window.history.replaceState(
            {},
            document.title,
            window.location.pathname
        );

    }


    /*
       Cancel button clears messages.
    */

    let cancelButton =
        document.querySelector(".cancel-btn");


    cancelButton.addEventListener("click", function () {

        let jsError =
            document.getElementById("js_error");

        let phpMessage =
            document.getElementById("php_message");

        let successMessage =
            document.getElementById("success_message");


        if (jsError) {

            jsError.innerHTML = "";

            jsError.style.display = "none";

        }


        if (phpMessage) {

            phpMessage.remove();

        }


        if (successMessage) {

            successMessage.remove();

        }

    });

</script>


</body>

</html>