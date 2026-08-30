<?php

require_once __DIR__ . "/config/db.php";


/* =========================================================
   VARIABLES
========================================================= */

$success = "";
$error = "";
$nextUserID = "";


/* =========================================================
   GET NEXT USER ID
========================================================= */

$sql = "
    SELECT COALESCE(MAX(user_id), 0) + 1 AS next_id
    FROM users
";


$result = mysqli_query(
    $conn,
    $sql
);


if ($result) {

    $row =
        mysqli_fetch_assoc(
            $result
        );

    $nextUserID =
        $row["next_id"];

}


/* =========================================================
   SUCCESS MESSAGE
========================================================= */

if (
    isset($_GET["success"])
) {

    $success =
        "Registration Successful!";


    if (
        isset($_GET["user_id"])
    ) {

        $nextUserID =
            $_GET["user_id"];

    }

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
        SEPMS - Registration
    </title>


    <link
        rel="stylesheet"
        href="register.css"
    >


    <script
        src="register.js"
    ></script>

</head>


<body>


<div class="register-container">


    <div class="register-box">


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


        <h2>
            Create Account
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

        if (
            $error !== ""
        ) {

        ?>

            <p
                id="php_message"
                style="
                    color:red;
                    text-align:center;
                    margin-bottom:15px;
                "
            >

                <?php

                echo htmlspecialchars(
                    $error
                );

                ?>

            </p>

        <?php

        }

        ?>



        <!-- =================================================
             SUCCESS MESSAGE
        ================================================= -->

       <?php

if (
    $success !== ""
) {

?>

    <div
        id="success_message"
        style="
            color:green;
            text-align:center;
            margin-bottom:20px;
        "
    >

        <?php

        echo htmlspecialchars(
            $success
        );

        ?>

    </div>


    <script>

        setTimeout(
            function () {

                window.location.href =
                    "login.php";

            },
            1000
        );

    </script>

<?php

}

?>



        <!-- =================================================
             REGISTRATION FORM
        ================================================= -->

        <form
            method="POST"
            action="controllers/RegisterController.php"
            onsubmit="return validateForm();"
            autocomplete="off"
        >


            <table>


                <!-- =================================================
                     NAME
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="name"
                        >

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



                <!-- =================================================
                     USER ID
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="user_id"
                        >

                            User ID

                        </label>

                    </td>


                    <td>

                        <input
                            type="text"
                            id="user_id"
                            name="user_id"
                            value="<?php

                                echo htmlspecialchars(
                                    $nextUserID
                                );

                            ?>"
                            readonly
                        >

                    </td>

                </tr>



                <!-- =================================================
                     EMAIL
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="email"
                        >

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



                <!-- =================================================
                     USERNAME
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="username"
                        >

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



                <!-- =================================================
                     PASSWORD
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="password"
                        >

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



                <!-- =================================================
                     CONFIRM PASSWORD
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="confirm_password"
                        >

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



                <!-- =================================================
                     ROLE
                ================================================= -->

                <tr>

                    <td>

                        <label
                            for="role"
                        >

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


                            <option
                                value="student"
                            >

                                Student

                            </option>


                            <option
                                value="coordinator"
                            >

                                Coordinator

                            </option>

                        </select>

                    </td>

                </tr>



                <!-- =================================================
                     BUTTONS
                ================================================= -->

                <tr>

                    <td colspan="2">

                        <div
                            class="button-area"
                        >


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



        <!-- =================================================
             LOGIN LINK
        ================================================= -->

        <div
            class="login-link"
        >

            Already have an account?

            <a
                href="login.php"
            >

                Login

            </a>

        </div>


    </div>


</div>



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>


/* =========================================================
   REMOVE URL PARAMETERS AFTER PAGE LOAD
========================================================= */

if (
    window.location.search !== ""
) {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );

}



/* =========================================================
   CANCEL BUTTON
========================================================= */

let cancelButton =
    document.querySelector(
        ".cancel-btn"
    );


if (
    cancelButton
) {

    cancelButton.addEventListener(
        "click",
        function () {


            let jsError =
                document.getElementById(
                    "js_error"
                );


            let phpMessage =
                document.getElementById(
                    "php_message"
                );


            let successMessage =
                document.getElementById(
                    "success_message"
                );


            if (
                jsError
            ) {

                jsError.innerHTML =
                    "";

                jsError.style.display =
                    "none";

            }


            if (
                phpMessage
            ) {

                phpMessage.remove();

            }


            if (
                successMessage
            ) {

                successMessage.remove();

            }

        }
    );

}

</script>


</body>

</html>