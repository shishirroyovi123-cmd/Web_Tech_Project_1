<?php

class NominationModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }


    // Get all nominations
    public function getAllNominations()
    {
        $sql = "
            SELECT
                n.nomination_id,
                n.application_id,
                n.nomination_date,
                n.university_id,
                n.status AS nomination_status,
                n.created_at,
                u.user_id AS student_id,
                u.name AS student_name,
                u.email AS student_email,
                ep.program_id,
                ep.program_name,
                c.country_id,
                c.country_name,
                univ.university_id AS host_university_id,
                univ.university_name,
                a.status AS application_status
            FROM nominations n
            INNER JOIN applications a
                ON n.application_id = a.application_id
            INNER JOIN users u
                ON a.student_id = u.user_id
            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id
            INNER JOIN universities univ
                ON n.university_id = univ.university_id
            INNER JOIN countries c
                ON ep.country_id = c.country_id
            ORDER BY n.nomination_id DESC
        ";

        $result = mysqli_query($this->conn, $sql);

        $nominations = [];

        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {
                $nominations[] = $row;
            }

        }

        return $nominations;
    }


    // Get one nomination
    public function getNominationById($nominationId)
    {
        $nominationId = (int)$nominationId;

        $sql = "
            SELECT
                nomination_id,
                application_id,
                university_id,
                nomination_date,
                status
            FROM nominations
            WHERE nomination_id = $nominationId
        ";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }


    // Get approved applications
    public function getApprovedApplications()
    {
        $sql = "
            SELECT
                a.application_id,
                a.student_id,
                u.name AS student_name,
                u.email AS student_email,
                ep.program_id,
                ep.program_name,
                ep.university_id AS program_university_id,
                univ.university_name AS host_university,
                c.country_name,
                a.status AS application_status,
                a.application_date
            FROM applications a
            INNER JOIN users u
                ON a.student_id = u.user_id
            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id
            INNER JOIN universities univ
                ON ep.university_id = univ.university_id
            INNER JOIN countries c
                ON ep.country_id = c.country_id
            WHERE a.status = 'approved'
            ORDER BY a.application_id DESC
        ";

        $result = mysqli_query($this->conn, $sql);

        $applications = [];

        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {
                $applications[] = $row;
            }

        }

        return $applications;
    }


    // Get all universities
    public function getAllUniversities()
    {
        $sql = "
            SELECT
                university_id,
                university_name
            FROM universities
            ORDER BY university_name ASC
        ";

        $result = mysqli_query($this->conn, $sql);

        $universities = [];

        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {
                $universities[] = $row;
            }

        }

        return $universities;
    }


    // Get one university
    public function getUniversityById($universityId)
    {
        $universityId = (int)$universityId;

        $sql = "
            SELECT
                university_id,
                university_name
            FROM universities
            WHERE university_id = $universityId
        ";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }


    // Create nomination
    public function createNomination($data)
    {
        $applicationId = (int)$data['application_id'];
        $universityId = (int)$data['university_id'];

        $nominationDate = mysqli_real_escape_string(
            $this->conn,
            $data['nomination_date']
        );

        $nominationStatus = mysqli_real_escape_string(
            $this->conn,
            $data['nomination_status']
        );


        $sql = "
            INSERT INTO nominations
            (
                application_id,
                university_id,
                nomination_date,
                status,
                created_at
            )
            VALUES
            (
                $applicationId,
                $universityId,
                '$nominationDate',
                '$nominationStatus',
                NOW()
            )
        ";


        if (mysqli_query($this->conn, $sql)) {

            $updateSQL = "
                UPDATE applications
                SET status = 'nominated'
                WHERE application_id = $applicationId
            ";

            mysqli_query($this->conn, $updateSQL);

            return true;
        }

        return false;
    }


    // Update nomination
    public function updateNomination($data)
    {
        $nominationId = (int)$data['nomination_id'];
        $applicationId = (int)$data['application_id'];
        $universityId = (int)$data['university_id'];

        $nominationDate = mysqli_real_escape_string(
            $this->conn,
            $data['nomination_date']
        );

        $nominationStatus = mysqli_real_escape_string(
            $this->conn,
            $data['nomination_status']
        );


        $sql = "
            UPDATE nominations
            SET
                application_id = $applicationId,
                university_id = $universityId,
                nomination_date = '$nominationDate',
                status = '$nominationStatus'
            WHERE nomination_id = $nominationId
        ";


        return mysqli_query($this->conn, $sql);
    }


    // Update nomination status
    public function updateStatus($nominationId, $newStatus)
    {
        $nominationId = (int)$nominationId;

        $newStatus = mysqli_real_escape_string(
            $this->conn,
            $newStatus
        );


        $sql = "
            UPDATE nominations
            SET status = '$newStatus'
            WHERE nomination_id = $nominationId
        ";


        if (mysqli_query($this->conn, $sql)) {

            if ($newStatus == "accepted") {

                $appStatus = "approved";

            }
            elseif ($newStatus == "rejected") {

                $appStatus = "rejected";

            }
            else {

                $appStatus = "nominated";

            }


            $updateAppSQL = "
                UPDATE applications a
                INNER JOIN nominations n
                    ON a.application_id = n.application_id
                SET a.status = '$appStatus'
                WHERE n.nomination_id = $nominationId
            ";


            mysqli_query($this->conn, $updateAppSQL);

            return true;
        }

        return false;
    }


    // Delete nomination
    public function deleteNomination($nominationId)
    {
        $nominationId = (int)$nominationId;


        $getAppSQL = "
            SELECT application_id
            FROM nominations
            WHERE nomination_id = $nominationId
        ";


        $appResult = mysqli_query(
            $this->conn,
            $getAppSQL
        );


        if ($appResult) {

            $appRow = mysqli_fetch_assoc($appResult);

            if ($appRow) {

                $appId = (int)$appRow['application_id'];


                $updateAppSQL = "
                    UPDATE applications
                    SET status = 'approved'
                    WHERE application_id = $appId
                ";


                mysqli_query(
                    $this->conn,
                    $updateAppSQL
                );
            }
        }


        $deleteSQL = "
            DELETE FROM nominations
            WHERE nomination_id = $nominationId
        ";


        return mysqli_query(
            $this->conn,
            $deleteSQL
        );
    }


    // Get nomination count
    public function getNominationCount()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM nominations
        ";


        $result = mysqli_query(
            $this->conn,
            $sql
        );


        if ($result) {

            $row = mysqli_fetch_assoc($result);

            return $row['total'];
        }

        return 0;
    }
}

?>