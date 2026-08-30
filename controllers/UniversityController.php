<?php

/* =========================================================
   DATABASE + MODEL
   ========================================================= */

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/University.php";


$universityModel =
    new University($conn);



/* =========================================================
   SEARCH UNIVERSITY
   ========================================================= */

if (isset($_GET["search"])) {

    $search =
        trim($_GET["search"]);


    $result =
        $universityModel->search($search);


    if ($result === false) {

        echo "
        <tr>
            <td colspan='6' class='empty_data'>
                Database search error.
            </td>
        </tr>
        ";

        exit();
    }


    if (mysqli_num_rows($result) == 0) {

        echo "
        <tr>
            <td colspan='6' class='empty_data'>
                No matching universities found.
            </td>
        </tr>
        ";

        exit();
    }


    while (
        $row =
        mysqli_fetch_assoc($result)
    ) {

        echo "<tr>";


        /* University ID */

        echo "<td>";

        echo htmlspecialchars(
            $row["university_id"]
        );

        echo "</td>";


        /* University Name */

        echo "<td>";

        echo htmlspecialchars(
            $row["university_name"]
        );

        echo "</td>";


        /* Country */

        echo "<td>";

        echo htmlspecialchars(
            $row["country_name"] ?? ""
        );

        echo "</td>";


        /* Email */

        echo "<td>";

        echo htmlspecialchars(
            $row["university_email"]
        );

        echo "</td>";


        /* Address */

        echo "<td>";

        echo htmlspecialchars(
            $row["university_address"]
        );

        echo "</td>";


        /* Action */

        echo "<td>";

        echo "
        <a
            href='UniversityController.php?delete=" .
            urlencode(
                $row["university_id"]
            ) .
            "'
            onclick=\"return confirm('Are you sure you want to delete this university?');\"
        >
            Delete
        </a>
        ";

        echo "</td>";


        echo "</tr>";
    }


    exit();
}



/* =========================================================
   ADD UNIVERSITY
   ========================================================= */

if (isset($_POST["add_university"])) {


    $university_id =
        trim(
            $_POST["university_id"] ?? ""
        );


    $university_name =
        trim(
            $_POST["university_name"] ?? ""
        );


    $country_id =
        trim(
            $_POST["country_id"] ?? ""
        );


    $university_email =
        trim(
            $_POST["university_email"] ?? ""
        );


    $university_address =
        trim(
            $_POST["university_address"] ?? ""
        );



    /* ================= EMPTY CHECK ================= */

    if (
        $university_id === "" ||
        $university_name === "" ||
        $country_id === "" ||
        $university_email === "" ||
        $university_address === ""
    ) {

        header(
            "Location: ../manage_universities.php?error=All fields are required."
        );

        exit();
    }



    /* ================= ID CHECK ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $university_id
        )
    ) {

        header(
            "Location: ../manage_universities.php?error=University ID must contain numbers only."
        );

        exit();
    }



    /* ================= COUNTRY ID CHECK ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $country_id
        )
    ) {

        header(
            "Location: ../manage_universities.php?error=Please select a valid country."
        );

        exit();
    }



    /* ================= NAME CHECK ================= */

    if (
        !preg_match(
            "/^[A-Za-z0-9 .,&'-]+$/",
            $university_name
        )
    ) {

        header(
            "Location: ../manage_universities.php?error=Invalid university name."
        );

        exit();
    }



    /* ================= EMAIL CHECK ================= */

    if (
        !filter_var(
            $university_email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        header(
            "Location: ../manage_universities.php?error=Please enter a valid email."
        );

        exit();
    }



    /* =====================================================
       INSERT
       ===================================================== */

    $result =
        $universityModel->add(
            $university_id,
            $university_name,
            $country_id,
            $university_email,
            $university_address
        );


    if ($result) {

        header(
            "Location: ../manage_universities.php?success=University added successfully."
        );

        exit();
    }


    /* ================= DATABASE ERROR ================= */

    $database_error =
        mysqli_error($conn);


    header(
        "Location: ../manage_universities.php?error=" .
        urlencode(
            "Database error: " .
            $database_error
        )
    );

    exit();
}



/* =========================================================
   DELETE UNIVERSITY
   ========================================================= */

if (isset($_GET["delete"])) {


    $university_id =
        $_GET["delete"];


    $result =
        $universityModel->delete(
            $university_id
        );


    if ($result) {

        header(
            "Location: ../manage_universities.php?success=University deleted successfully."
        );

        exit();
    }


    $database_error =
        mysqli_error($conn);


    header(
        "Location: ../manage_universities.php?error=" .
        urlencode(
            "Database error: " .
            $database_error
        )
    );

    exit();
}



/* =========================================================
   DEFAULT
   ========================================================= */

header(
    "Location: ../manage_universities.php"
);

exit();

?>