<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/ExchangeProgram.php";


$programModel =
    new ExchangeProgram($conn);


/* =========================================================
   GET UNIVERSITIES BY COUNTRY
   ========================================================= */

if (isset($_GET["get_universities_by_country"])) {

    $countryID =
        (int) $_GET["get_universities_by_country"];


    $sql = "
        SELECT
            university_id,
            university_name
        FROM universities
        WHERE country_id = ?
        ORDER BY university_name ASC
    ";


    $stmt =
        mysqli_prepare(
            $conn,
            $sql
        );


    if (!$stmt) {

        echo '
        <option value="">
            Database error
        </option>
        ';

        exit();

    }


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $countryID
    );


    mysqli_stmt_execute(
        $stmt
    );


    $result =
        mysqli_stmt_get_result(
            $stmt
        );


    if (
        mysqli_num_rows($result) == 0
    ) {

        echo '
        <option value="">
            No universities available
        </option>
        ';

        exit();

    }


    echo '
    <option value="">
        Select university
    </option>
    ';


    while (
        $row =
        mysqli_fetch_assoc($result)
    ) {

        echo '
        <option value="' .
            htmlspecialchars(
                $row["university_id"]
            ) .
        '">' .
            htmlspecialchars(
                $row["university_name"]
            ) .
        '</option>
        ';
    }


    exit();
}



/* =========================================================
   SEARCH
   ========================================================= */

if (isset($_GET["search"])) {

    $search =
        trim($_GET["search"]);


    $result =
        $programModel->search($search);


    if ($result === false) {

        echo "
        <tr>

            <td
                colspan='9'
                class='empty_data'
            >

                Database search error.

            </td>

        </tr>
        ";

        exit();
    }


    if (
        mysqli_num_rows($result) == 0
    ) {

        echo "
        <tr>

            <td
                colspan='9'
                class='empty_data'
            >

                No matching exchange programs found.

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


        echo "<td>";

        echo htmlspecialchars(
            $row["program_id"]
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["program_name"]
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["country_name"] ?? ""
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["university_name"] ?? ""
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["start_date"]
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["end_date"]
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["deadline"]
        );

        echo "</td>";


        echo "<td>";

        echo htmlspecialchars(
            $row["available_seats"]
        );

        echo "</td>";


        echo "<td>";


        echo "
        <button
            type='button'
            class='edit_btn'
            onclick='editProgram(" .
                (int)$row["program_id"] .
            ")'
        >
            Edit
        </button>


        <a
            href='ExchangeProgramController.php?delete=" .
                urlencode(
                    $row["program_id"]
                ) .
            "'
            class='delete_btn'
            onclick=\"return confirm('Are you sure you want to delete this exchange program?');\"
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
   GET PROGRAM FOR EDIT
   ========================================================= */

if (isset($_GET["get_program"])) {

    $program_id =
        (int)$_GET["get_program"];


    $program =
        $programModel->getById(
            $program_id
        );


    header(
        "Content-Type: application/json"
    );


    if ($program) {

        echo json_encode(
            $program
        );

    }
    else {

        echo json_encode([
            "error" => "Program not found."
        ]);

    }


    exit();
}



/* =========================================================
   ADD PROGRAM
   ========================================================= */

if (isset($_POST["add_program"])) {

    $program_id =
        trim(
            $_POST["program_id"] ?? ""
        );


    $program_name =
        trim(
            $_POST["program_name"] ?? ""
        );


    $country_id =
        trim(
            $_POST["country"] ?? ""
        );


    $university_id =
        trim(
            $_POST["university"] ?? ""
        );


    $start_date =
        trim(
            $_POST["start_date"] ?? ""
        );


    $end_date =
        trim(
            $_POST["end_date"] ?? ""
        );


    $deadline =
        trim(
            $_POST["deadline"] ?? ""
        );


    $available_seats =
        trim(
            $_POST["available_seats"] ?? ""
        );


    $description =
        trim(
            $_POST["description"] ?? ""
        );



    /* ================= EMPTY CHECK ================= */

    if (
        $program_id === "" ||
        $program_name === "" ||
        $country_id === "" ||
        $university_id === "" ||
        $start_date === "" ||
        $end_date === "" ||
        $deadline === "" ||
        $available_seats === "" ||
        $description === ""
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=All fields are required."
        );

        exit();

    }



    /* ================= PROGRAM ID ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $program_id
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Program ID must contain numbers only."
        );

        exit();

    }



    /* ================= COUNTRY ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $country_id
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Please select a valid country."
        );

        exit();

    }



    /* ================= UNIVERSITY ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $university_id
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Please select a valid university."
        );

        exit();

    }



    /* ================= PROGRAM NAME ================= */

    if (
        !preg_match(
            "/^[A-Za-z0-9 .,&'()\-]+$/",
            $program_name
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Invalid program name."
        );

        exit();

    }



    /* ================= SEATS ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $available_seats
        ) ||
        (int)$available_seats < 1
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Available seats must be at least 1."
        );

        exit();

    }



    /* ================= DATES ================= */

    if (
        $end_date < $start_date
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=End date cannot be before start date."
        );

        exit();

    }



    if (
        $deadline >= $start_date
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Application deadline must be before the program start date."
        );

        exit();

    }



    /* ================= ADD ================= */

    $result =
        $programModel->add(
            $program_id,
            $program_name,
            $country_id,
            $university_id,
            $start_date,
            $end_date,
            $deadline,
            $available_seats,
            $description
        );


    if ($result) {

        header(
            "Location: ../manage_exchange_programs.php?success=Exchange program added successfully."
        );

        exit();

    }



    header(
        "Location: ../manage_exchange_programs.php?error=" .
        urlencode(
            "Database error: " .
            mysqli_error($conn)
        )
    );

    exit();

}



/* =========================================================
   UPDATE PROGRAM
   ========================================================= */

if (isset($_POST["update_program"])) {

    $program_id =
        trim(
            $_POST["program_id"] ?? ""
        );


    $program_name =
        trim(
            $_POST["program_name"] ?? ""
        );


    $country_id =
        trim(
            $_POST["country"] ?? ""
        );


    $university_id =
        trim(
            $_POST["university"] ?? ""
        );


    $start_date =
        trim(
            $_POST["start_date"] ?? ""
        );


    $end_date =
        trim(
            $_POST["end_date"] ?? ""
        );


    $deadline =
        trim(
            $_POST["deadline"] ?? ""
        );


    $available_seats =
        trim(
            $_POST["available_seats"] ?? ""
        );


    $description =
        trim(
            $_POST["description"] ?? ""
        );



    /* ================= EMPTY CHECK ================= */

    if (
        $program_id === "" ||
        $program_name === "" ||
        $country_id === "" ||
        $university_id === "" ||
        $start_date === "" ||
        $end_date === "" ||
        $deadline === "" ||
        $available_seats === "" ||
        $description === ""
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=All fields are required."
        );

        exit();

    }



    /* ================= COUNTRY ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $country_id
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Invalid country."
        );

        exit();

    }



    /* ================= UNIVERSITY ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $university_id
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Invalid university."
        );

        exit();

    }



    /* ================= PROGRAM NAME ================= */

    if (
        !preg_match(
            "/^[A-Za-z0-9 .,&'()\-]+$/",
            $program_name
        )
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Invalid program name."
        );

        exit();

    }



    /* ================= SEATS ================= */

    if (
        !preg_match(
            "/^[0-9]+$/",
            $available_seats
        ) ||
        (int)$available_seats < 1
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Available seats must be at least 1."
        );

        exit();

    }



    /* ================= DATES ================= */

    if (
        $end_date < $start_date
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=End date cannot be before start date."
        );

        exit();

    }



    if (
        $deadline >= $start_date
    ) {

        header(
            "Location: ../manage_exchange_programs.php?error=Application deadline must be before the program start date."
        );

        exit();

    }



    /* ================= UPDATE ================= */

    $result =
        $programModel->update(
            $program_id,
            $program_name,
            $country_id,
            $university_id,
            $start_date,
            $end_date,
            $deadline,
            $available_seats,
            $description
        );


    if ($result) {

        header(
            "Location: ../manage_exchange_programs.php?success=Exchange program updated successfully."
        );

        exit();

    }



    header(
        "Location: ../manage_exchange_programs.php?error=" .
        urlencode(
            "Database error: " .
            mysqli_error($conn)
        )
    );

    exit();

}



/* =========================================================
   DELETE
   ========================================================= */

if (isset($_GET["delete"])) {

    $program_id =
        (int)$_GET["delete"];


    $result =
        $programModel->delete(
            $program_id
        );


    if ($result) {

        header(
            "Location: ../manage_exchange_programs.php?success=Exchange program deleted successfully."
        );

        exit();

    }



    header(
        "Location: ../manage_exchange_programs.php?error=" .
        urlencode(
            "Database error: " .
            mysqli_error($conn)
        )
    );

    exit();

}



/* =========================================================
   DEFAULT
   ========================================================= */

header(
    "Location: ../manage_exchange_programs.php"
);

exit();

?>