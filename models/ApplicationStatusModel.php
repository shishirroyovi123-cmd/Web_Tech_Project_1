<?php

class ApplicationStatusModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // Get all applications of the logged-in student
    public function getStudentApplications($studentId)
    {
        $studentId = (int)$studentId;

        $sql = "
            SELECT
                a.application_id,
                ep.program_name
            FROM applications a
            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id
            WHERE a.student_id = $studentId
            ORDER BY a.application_id DESC
        ";

        $result = mysqli_query($this->conn, $sql);

        $applications = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $applications[] = $row;
            }
        }

        return $applications;
    }


    // Get selected application details
    public function getApplicationDetails($applicationId, $studentId)
    {
        $applicationId = (int)$applicationId;
        $studentId = (int)$studentId;

        $sql = "
            SELECT
                a.application_id,
                a.program_id,
                a.application_date,
                a.status AS application_status,

                ep.program_name,

                univ.university_name,

                c.country_name

            FROM applications a

            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id

            INNER JOIN universities univ
                ON ep.university_id = univ.university_id

            INNER JOIN countries c
                ON ep.country_id = c.country_id

            WHERE a.application_id = $applicationId
            AND a.student_id = $studentId
        ";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }


    // Get nomination information
    public function getNominationDetails($applicationId)
    {
        $applicationId = (int)$applicationId;

        $sql = "
            SELECT
                nomination_id,
                nomination_date,
                status
            FROM nominations
            WHERE application_id = $applicationId
            ORDER BY nomination_id DESC
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}

?>