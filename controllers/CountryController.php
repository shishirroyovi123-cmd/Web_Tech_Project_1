<?php

/* =========================================================
   DATABASE + MODEL
   ========================================================= */

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/Country.php";


$countryModel = new Country($conn);


/* =========================================================
   SEARCH COUNTRY
   ========================================================= */

if (isset($_GET["search"])) {


    $search =
        trim($_GET["search"]);


    /* Search database */

    $result =
        $countryModel->search($search);


    /* Database error */

    if ($result === false) {

        echo "
        <tr>

            <td
                colspan='4'
                class='empty_data'
            >

                Database search error.

            </td>

        </tr>
        ";

        exit();
    }


    /* No result */

    if (
        mysqli_num_rows($result) == 0
    ) {

        echo "
        <tr>

            <td
                colspan='4'
                class='empty_data'
            >

                No matching countries found.

            </td>

        </tr>
        ";

        exit();
    }


    /* =====================================================
       DISPLAY SEARCH RESULTS
       ===================================================== */

    while (
        $row =
        mysqli_fetch_assoc($result)
    ) {


        echo "<tr>";


        /* ================= COUNTRY ID ================= */

        echo "<td>";

        echo htmlspecialchars(
            $row["country_id"]
        );

        echo "</td>";


        /* ================= COUNTRY NAME ================= */

        echo "<td>";

        echo htmlspecialchars(
            $row["country_name"]
        );

        echo "</td>";


        /* ================= REGION ================= */

        echo "<td>";

        echo htmlspecialchars(
            $row["region"]
        );

        echo "</td>";


        /* ================= ACTION ================= */

        echo "<td>";

        echo "<a
                href='CountryController.php?delete=" .
                urlencode(
                    $row["country_id"]
                ) .
                "'
                onclick=\"return confirm('Are you sure you want to delete this country?');\"
              >
                Delete
              </a>";

        echo "</td>";


        echo "</tr>";
    }


    /* Stop here */

    exit();
}



/* =========================================================
   ADD COUNTRY
   ========================================================= */

if (isset($_POST["add_country"])) {


    /* Get form data */

    $country_id =
        trim(
            $_POST["country_id"] ?? ""
        );


    $country_name =
        trim(
            $_POST["country_name"] ?? ""
        );


    $region =
        trim(
            $_POST["region"] ?? ""
        );


    /* =====================================================
       VALIDATION
       ===================================================== */


    /* Empty fields */

    if (
        $country_id === "" ||
        $country_name === "" ||
        $region === ""
    ) {

        header(
            "Location: ../manage_countries.php?error=All fields are required."
        );

        exit();
    }


    /* Country ID */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $country_id
        )
    ) {

        header(
            "Location: ../manage_countries.php?error=Country ID must contain numbers only."
        );

        exit();
    }


    /* Country name */

    if (
        !preg_match(
            "/^[A-Za-z ]+$/",
            $country_name
        )
    ) {

        header(
            "Location: ../manage_countries.php?error=Country name must contain letters only."
        );

        exit();
    }


    /* Region */

    if (
        !preg_match(
            "/^[A-Za-z ]+$/",
            $region
        )
    ) {

        header(
            "Location: ../manage_countries.php?error=Region must contain letters only."
        );

        exit();
    }


    /* =====================================================
       INSERT
       ===================================================== */

    $result =
        $countryModel->add(
            $country_id,
            $country_name,
            $region
        );


    /* Insert successful */

    if ($result) {

        header(
            "Location: ../manage_countries.php?success=Country added successfully."
        );

        exit();
    }


    /* Insert failed */

    $database_error =
        mysqli_error($conn);


    header(
        "Location: ../manage_countries.php?error=" .
        urlencode(
            "Database error: " .
            $database_error
        )
    );

    exit();
}



/* =========================================================
   DELETE COUNTRY
   ========================================================= */

if (isset($_GET["delete"])) {


    $country_id =
        $_GET["delete"];


    /* Delete */

    $result =
        $countryModel->delete(
            $country_id
        );


    /* Delete successful */

    if ($result) {

        header(
            "Location: ../manage_countries.php?success=Country deleted successfully."
        );

        exit();
    }


    /* Delete failed */

    $database_error =
        mysqli_error($conn);


    header(
        "Location: ../manage_countries.php?error=" .
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
    "Location: ../manage_countries.php"
);

exit();

?>