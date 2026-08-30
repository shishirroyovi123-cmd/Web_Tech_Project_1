<?php

class ExchangeProgram
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* =====================================================
       GET ALL PROGRAMS
       ===================================================== */

    public function getAll()
    {
        $sql = "SELECT
                    ep.program_id,
                    ep.program_name,
                    ep.country_id,
                    c.country_name,
                    ep.university_id,
                    u.university_name,
                    ep.start_date,
                    ep.end_date,
                    ep.deadline,
                    ep.available_seats,
                    ep.description
                FROM exchange_programs ep
                LEFT JOIN countries c
                    ON ep.country_id = c.country_id
                LEFT JOIN universities u
                    ON ep.university_id = u.university_id
                ORDER BY ep.program_id ASC";

        return mysqli_query($this->conn, $sql);
    }


    /* =====================================================
       GET ONE PROGRAM
       ===================================================== */

    public function getById($program_id)
    {
        $sql = "SELECT
                    program_id,
                    program_name,
                    country_id,
                    university_id,
                    start_date,
                    end_date,
                    deadline,
                    available_seats,
                    description
                FROM exchange_programs
                WHERE program_id = ?";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $program_id
        );

        mysqli_stmt_execute($stmt);

        $result =
            mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }


    /* =====================================================
       ADD PROGRAM
       ===================================================== */

    public function add(
        $program_id,
        $program_name,
        $country_id,
        $university_id,
        $start_date,
        $end_date,
        $deadline,
        $available_seats,
        $description
    ) {

        $sql = "INSERT INTO exchange_programs
                (
                    program_id,
                    program_name,
                    country_id,
                    university_id,
                    start_date,
                    end_date,
                    deadline,
                    available_seats,
                    description
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "isiisssis",
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

        return mysqli_stmt_execute($stmt);
    }


    /* =====================================================
       UPDATE PROGRAM
       ===================================================== */

    public function update(
        $program_id,
        $program_name,
        $country_id,
        $university_id,
        $start_date,
        $end_date,
        $deadline,
        $available_seats,
        $description
    ) {

        $sql = "UPDATE exchange_programs
                SET
                    program_name = ?,
                    country_id = ?,
                    university_id = ?,
                    start_date = ?,
                    end_date = ?,
                    deadline = ?,
                    available_seats = ?,
                    description = ?
                WHERE program_id = ?";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "siisssisi",
            $program_name,
            $country_id,
            $university_id,
            $start_date,
            $end_date,
            $deadline,
            $available_seats,
            $description,
            $program_id
        );

        return mysqli_stmt_execute($stmt);
    }


    /* =====================================================
       SEARCH
       PROGRAM NAME + COUNTRY + UNIVERSITY
       ===================================================== */

    public function search($search)
    {
        $search =
            "%" . $search . "%";

        $sql = "SELECT
                    ep.program_id,
                    ep.program_name,
                    ep.country_id,
                    c.country_name,
                    ep.university_id,
                    u.university_name,
                    ep.start_date,
                    ep.end_date,
                    ep.deadline,
                    ep.available_seats,
                    ep.description
                FROM exchange_programs ep
                LEFT JOIN countries c
                    ON ep.country_id = c.country_id
                LEFT JOIN universities u
                    ON ep.university_id = u.university_id
                WHERE
                    ep.program_name LIKE ?
                    OR c.country_name LIKE ?
                    OR u.university_name LIKE ?
                ORDER BY ep.program_id ASC";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $search,
            $search,
            $search
        );

        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }

        return mysqli_stmt_get_result($stmt);
    }


    /* =====================================================
       DELETE PROGRAM
       ===================================================== */

    public function delete($program_id)
    {
        $sql = "DELETE FROM exchange_programs
                WHERE program_id = ?";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $program_id
        );

        return mysqli_stmt_execute($stmt);
    }
}

?>