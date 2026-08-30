<?php

class University
{
    private $conn;


    /* ================= CONSTRUCTOR ================= */

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* ================= GET ALL UNIVERSITIES ================= */

    public function getAll()
    {
        $sql = "SELECT
                    u.university_id,
                    u.university_name,
                    u.country_id,
                    c.country_name,
                    u.university_email,
                    u.university_address
                FROM universities u
                LEFT JOIN countries c
                    ON u.country_id = c.country_id
                ORDER BY u.university_id ASC";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    /* ================= ADD UNIVERSITY ================= */

    public function add(
        $university_id,
        $university_name,
        $country_id,
        $university_email,
        $university_address
    ) {

        $sql = "INSERT INTO universities
                (
                    university_id,
                    university_name,
                    country_id,
                    university_email,
                    university_address
                )
                VALUES (?, ?, ?, ?, ?)";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "isiss",
            $university_id,
            $university_name,
            $country_id,
            $university_email,
            $university_address
        );


        return mysqli_stmt_execute($stmt);
    }


    /* ================= SEARCH UNIVERSITY ================= */

   /* ================= SEARCH UNIVERSITY ================= */

public function search($search)
{
    /*
       Search ONLY by complete university name
    */

    $sql = "SELECT
                u.university_id,
                u.university_name,
                u.country_id,
                c.country_name,
                u.university_email,
                u.university_address
            FROM universities u
            LEFT JOIN countries c
                ON u.country_id = c.country_id
            WHERE
                u.university_name = ?
            ORDER BY u.university_id ASC";


    $stmt = mysqli_prepare(
        $this->conn,
        $sql
    );


    if (!$stmt) {

        return false;
    }


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $search
    );


    if (!mysqli_stmt_execute($stmt)) {

        return false;
    }


    return mysqli_stmt_get_result($stmt);
}

    /* ================= DELETE UNIVERSITY ================= */

    public function delete($university_id)
    {
        $sql = "DELETE FROM universities
                WHERE university_id = ?";


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
            $university_id
        );


        return mysqli_stmt_execute($stmt);
    }


    /* ================= GET ONE UNIVERSITY ================= */

    public function getById($university_id)
    {
        $sql = "SELECT
                    university_id,
                    university_name,
                    country_id,
                    university_email,
                    university_address
                FROM universities
                WHERE university_id = ?";


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
            $university_id
        );


        mysqli_stmt_execute($stmt);


        return mysqli_stmt_get_result($stmt);
    }


    /* ================= UPDATE UNIVERSITY ================= */

    public function update(
        $university_id,
        $university_name,
        $country_id,
        $university_email,
        $university_address
    ) {

        $sql = "UPDATE universities
                SET
                    university_name = ?,
                    country_id = ?,
                    university_email = ?,
                    university_address = ?
                WHERE university_id = ?";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "sissi",
            $university_name,
            $country_id,
            $university_email,
            $university_address,
            $university_id
        );


        return mysqli_stmt_execute($stmt);
    }
}

?>