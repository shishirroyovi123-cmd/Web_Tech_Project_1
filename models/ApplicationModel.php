<?php

class ApplicationModel
{
    private $conn;


    /* =========================================================
       CONSTRUCTOR
    ========================================================= */

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* =========================================================
       GET EXCHANGE PROGRAM BY ID
    ========================================================= */

    public function getProgramById($programID)
    {
        $sql = "
            SELECT
                ep.program_id,
                ep.program_name,
                ep.country_id,
                ep.university_id,
                ep.start_date,
                ep.end_date,
                ep.deadline,
                ep.available_seats,
                ep.description,

                c.country_name,

                u.university_name,
                u.university_email,
                u.university_address

            FROM exchange_programs ep

            INNER JOIN countries c
                ON ep.country_id = c.country_id

            INNER JOIN universities u
                ON ep.university_id = u.university_id

            WHERE ep.program_id = ?

            LIMIT 1
        ";


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
            $programID
        );


        mysqli_stmt_execute($stmt);


        $result = mysqli_stmt_get_result(
            $stmt
        );


        $row = mysqli_fetch_assoc(
            $result
        );


        mysqli_stmt_close(
            $stmt
        );


        return $row;
    }


    /* =========================================================
       GET STUDENT BY ID

       Your current database uses the users table.
    ========================================================= */

    public function getStudentById($studentID)
    {
        $sql = "
            SELECT
                user_id,
                name,
                email,
                username,
                role

            FROM users

            WHERE user_id = ?

            AND LOWER(role) = 'student'

            LIMIT 1
        ";


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
            $studentID
        );


        mysqli_stmt_execute(
            $stmt
        );


        $result = mysqli_stmt_get_result(
            $stmt
        );


        $row = mysqli_fetch_assoc(
            $result
        );


        mysqli_stmt_close(
            $stmt
        );


        return $row;
    }


    /* =========================================================
       CHECK EXISTING APPLICATION

       A student can apply to multiple different programs.

       This only prevents applying to the SAME program twice.
    ========================================================= */

    public function checkExistingApplication(
        $studentID,
        $programID
    ) {

        $sql = "
            SELECT application_id

            FROM applications

            WHERE student_id = ?
            AND program_id = ?

            LIMIT 1
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $studentID,
            $programID
        );


        mysqli_stmt_execute(
            $stmt
        );


        $result = mysqli_stmt_get_result(
            $stmt
        );


        $exists =
            mysqli_num_rows($result) > 0;


        mysqli_stmt_close(
            $stmt
        );


        return $exists;
    }


    /* =========================================================
       CREATE APPLICATION

       Matches your existing applications table:

       application_id
       student_id
       program_id
       department
       cgpa
       semester
       study_term
       statement_of_purpose
       application_date
       status
       declaration
    ========================================================= */

    public function createApplication(
        $studentID,
        $programID,
        $department,
        $cgpa,
        $semester,
        $studyTerm,
        $statementOfPurpose,
        $applicationDate,
        $status,
        $declaration
    ) {

        $sql = "
            INSERT INTO applications
            (
                student_id,
                program_id,
                department,
                cgpa,
                semester,
                study_term,
                statement_of_purpose,
                application_date,
                status,
                declaration
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "iisdissssi",
            $studentID,
            $programID,
            $department,
            $cgpa,
            $semester,
            $studyTerm,
            $statementOfPurpose,
            $applicationDate,
            $status,
            $declaration
        );


        $success = mysqli_stmt_execute(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $success;
    }


    /* =========================================================
       GET LAST INSERTED APPLICATION ID
    ========================================================= */

    public function getLastApplicationID()
    {
        return mysqli_insert_id(
            $this->conn
        );
    }


    /* =========================================================
       GET APPLICATION BY ID

       Used for View Application / Edit Application.
    ========================================================= */

    public function getApplicationById(
        $applicationID,
        $studentID
    ) {

        $sql = "
            SELECT
                a.application_id,
                a.student_id,
                a.program_id,
                a.department,
                a.cgpa,
                a.semester,
                a.study_term,
                a.statement_of_purpose,
                a.application_date,
                a.status,
                a.declaration,

                ep.program_name,
                ep.start_date,
                ep.end_date,
                ep.deadline,
                ep.available_seats,
                ep.description,

                c.country_id,
                c.country_name,

                u.university_id,
                u.university_name

            FROM applications a

            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id

            INNER JOIN countries c
                ON ep.country_id = c.country_id

            INNER JOIN universities u
                ON ep.university_id = u.university_id

            WHERE a.application_id = ?
            AND a.student_id = ?

            LIMIT 1
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $applicationID,
            $studentID
        );


        mysqli_stmt_execute(
            $stmt
        );


        $result = mysqli_stmt_get_result(
            $stmt
        );


        $row = mysqli_fetch_assoc(
            $result
        );


        mysqli_stmt_close(
            $stmt
        );


        return $row;
    }


    /* =========================================================
       UPDATE APPLICATION

       Only use this while application status is Pending.
    ========================================================= */

    public function updateApplication(
        $applicationID,
        $studentID,
        $department,
        $cgpa,
        $semester,
        $studyTerm,
        $statementOfPurpose,
        $declaration
    ) {

        $sql = "
            UPDATE applications

            SET

                department = ?,
                cgpa = ?,
                semester = ?,
                study_term = ?,
                statement_of_purpose = ?,
                declaration = ?

            WHERE application_id = ?
            AND student_id = ?
            AND LOWER(status) = 'pending'
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "sdissiii",
            $department,
            $cgpa,
            $semester,
            $studyTerm,
            $statementOfPurpose,
            $declaration,
            $applicationID,
            $studentID
        );


        $success = mysqli_stmt_execute(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $success;
    }


    /* =========================================================
       GET ALL APPLICATIONS OF ONE STUDENT
    ========================================================= */

    public function getStudentApplications(
        $studentID
    ) {

        $sql = "
            SELECT
                a.application_id,
                a.student_id,
                a.program_id,
                a.department,
                a.cgpa,
                a.semester,
                a.study_term,
                a.statement_of_purpose,
                a.application_date,
                a.status,
                a.declaration,

                ep.program_name,
                ep.start_date,
                ep.end_date,
                ep.deadline,
                ep.available_seats,

                c.country_name,

                u.university_name

            FROM applications a

            INNER JOIN exchange_programs ep
                ON a.program_id = ep.program_id

            INNER JOIN countries c
                ON ep.country_id = c.country_id

            INNER JOIN universities u
                ON ep.university_id = u.university_id

            WHERE a.student_id = ?

            ORDER BY a.application_id DESC
        ";


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
            $studentID
        );


        mysqli_stmt_execute(
            $stmt
        );


        $result = mysqli_stmt_get_result(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $result;
    }


    /* =========================================================
       CREATE DOCUMENT

       Matches your documents table:

       document_id
       application_id
       document_type
       file_name
       file_path
       upload_date
       verification_status
    ========================================================= */

    public function createDocument(
        $applicationID,
        $documentType,
        $fileName,
        $filePath
    ) {

        $sql = "
            INSERT INTO documents
            (
                application_id,
                document_type,
                file_name,
                file_path,
                upload_date,
                verification_status
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                NOW(),
                'Pending'
            )
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $applicationID,
            $documentType,
            $fileName,
            $filePath
        );


        $success = mysqli_stmt_execute(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $success;
    }


    /* =========================================================
       GET DOCUMENTS BY APPLICATION
    ========================================================= */

    public function getDocumentsByApplicationID(
        $applicationID
    ) {

        $sql = "
            SELECT
                document_id,
                application_id,
                document_type,
                file_name,
                file_path,
                upload_date,
                verification_status

            FROM documents

            WHERE application_id = ?

            ORDER BY document_id DESC
        ";


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
            $applicationID
        );


        mysqli_stmt_execute(
            $stmt
        );


        $result = mysqli_stmt_get_result(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $result;
    }


    /* =========================================================
       DELETE DOCUMENT
    ========================================================= */

    public function deleteDocument(
        $documentID,
        $applicationID
    ) {

        $sql = "
            DELETE FROM documents

            WHERE document_id = ?
            AND application_id = ?
        ";


        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );


        if (!$stmt) {

            return false;

        }


        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $documentID,
            $applicationID
        );


        $success = mysqli_stmt_execute(
            $stmt
        );


        mysqli_stmt_close(
            $stmt
        );


        return $success;
    }


    /* =========================================================
       GET DATABASE CONNECTION
    ========================================================= */

    public function getConnection()
    {
        return $this->conn;
    }
}

?>