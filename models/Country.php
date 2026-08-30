<?php

class Country
{
    private $conn;


    /* ================= CONSTRUCTOR ================= */

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* ================= GET ALL COUNTRIES ================= */

    public function getAll()
    {
        $sql = "SELECT *
                FROM countries
                ORDER BY country_id ASC";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    /* ================= ADD COUNTRY ================= */

    public function add(
        $country_id,
        $country_name,
        $region
    ) {

        $sql = "INSERT INTO countries
                (
                    country_id,
                    country_name,
                    region
                )
                VALUES (?, ?, ?)";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $country_id,
            $country_name,
            $region
        );


        return mysqli_stmt_execute($stmt);
    }


    /* ================= DELETE COUNTRY ================= */

    public function delete($country_id)
    {
        $sql = "DELETE FROM countries
                WHERE country_id = ?";


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
            $country_id
        );


        return mysqli_stmt_execute($stmt);
    }


    /* ================= SEARCH COUNTRY ================= */

    public function search($search)
    {
        $search = "%" . $search . "%";


        $sql = "SELECT
                    country_id,
                    country_name,
                    region
                FROM countries
                WHERE
                    CAST(country_id AS CHAR) LIKE ?
                    OR country_name LIKE ?
                    OR region LIKE ?
                ORDER BY country_id ASC";


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
}

?>