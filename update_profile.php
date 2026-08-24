<?php

$error = "";
$success = "";


/* ================= PHP VALIDATION ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);
    $date_of_birth = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $address = trim($_POST["address"]);


    /* Check required fields */

    if (
        empty($email) ||
        empty($full_name) ||
        empty($phone) ||
        empty($gender)
    ) {

        $error = "All required fields are required.";

    }


    /* Check Email */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }


    /* Check Name */

    elseif (!preg_match("/^[A-Za-z ]+$/", $full_name)) {

        $error = "Full name must contain letters only.";

    }


    /* Check Phone */

    elseif (!is_numeric($phone)) {

        $error = "Phone number must contain numbers only.";

    }


    /* Successful validation */

    else {

        $success = "Profile information is valid.";

        /*
        Database UPDATE will be added later.
        */

    }

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Update Profile</title>

    <link rel="stylesheet"
          href="update_profile.css">

</head>


<body>


    <div class="profile_container">


        <div class="profile_form_box">


            <!-- ================= PAGE TITLE ================= -->

            <h1>

                Update Profile

            </h1>


            <p class="description">

                Update the information you provided during registration.

            </p>



            <!-- ================= PHP MESSAGE ================= -->

            <?php

            if ($error != "") {

                echo "

                <p style='color:red; text-align:center;'>

                    $error

                </p>

                ";

            }


            if ($success != "") {

                echo "

                <p style='color:green; text-align:center;'>

                    $success

                </p>

                ";

            }

            ?>



            <form
                action=""
                method="post"
            >


                <!-- ================= ACCOUNT INFORMATION ================= -->

                <div class="section_title">

                    Account Information

                </div>



                <div class="form_row">


                    <!-- User ID -->

                    <div class="form_group">


                        <label for="user_id">

                            User ID

                        </label>


                        <input
                            type="text"
                            id="user_id"
                            name="user_id"
                            value=""
                            placeholder="Your User ID"
                            readonly
                        >


                    </div>



                    <!-- Username -->

                    <div class="form_group">


                        <label for="username">

                            Username

                        </label>


                        <input
                            type="text"
                            id="username"
                            name="username"
                            value=""
                            placeholder="Your Username"
                            readonly
                        >


                    </div>


                </div>



                <div class="form_row">


                    <!-- Role -->

                    <div class="form_group">


                        <label for="role">

                            Role

                        </label>


                        <input
                            type="text"
                            id="role"
                            name="role"
                            value=""
                            placeholder="Student / Coordinator / Admin"
                            readonly
                        >


                    </div>



                    <!-- Email -->

                    <div class="form_group">


                        <label for="email">

                            Email Address

                        </label>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"
                            placeholder="Enter your email"
                            required
                        >


                    </div>


                </div>



                <!-- ================= PERSONAL INFORMATION ================= -->

                <div class="section_title">

                    Personal Information

                </div>



                <div class="form_row">


                    <!-- Full Name -->

                    <div class="form_group">


                        <label for="full_name">

                            Full Name

                        </label>


                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="<?php echo isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>"
                            placeholder="Enter your full name"
                            required
                        >


                    </div>



                    <!-- Phone -->

                    <div class="form_group">


                        <label for="phone">

                            Phone Number

                        </label>


                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"
                            placeholder="Enter your phone number"
                            required
                        >


                    </div>


                </div>



                <div class="form_row">


                    <!-- Date of Birth -->

                    <div class="form_group">


                        <label for="date_of_birth">

                            Date of Birth

                        </label>


                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>"
                        >


                    </div>



                    <!-- Gender -->

                    <div class="form_group">


                        <label for="gender">

                            Gender

                        </label>


                        <select
                            id="gender"
                            name="gender"
                            required
                        >


                            <option value="">

                                Select Gender

                            </option>


                            <option value="Male">

                                Male

                            </option>


                            <option value="Female">

                                Female

                            </option>


                            <option value="Other">

                                Other

                            </option>


                        </select>


                    </div>


                </div>



                <!-- ================= ADDRESS ================= -->

                <div class="section_title">

                    Address Information

                </div>



                <div class="form_group full_width">


                    <label for="address">

                        Address

                    </label>


                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Enter your current address"
                    ><?php

                    echo isset($_POST['address'])
                        ? $_POST['address']
                        : '';

                    ?></textarea>


                </div>



                <!-- ================= BUTTONS ================= -->

                <div class="form_buttons">


                    <button
                        type="submit"
                        class="update_button"
                    >

                        Update Profile

                    </button>



                    <button
                        type="reset"
                        class="cancel_button"
                    >

                        Cancel

                    </button>


                </div>


            </form>


        </div>


    </div>


</body>

</html>