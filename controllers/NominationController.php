<?php

require_once __DIR__ . "/../models/NominationModel.php";


class NominationController
{
    private $model;
    private $error;
    private $success;


    public function __construct($dbConnection)
    {
        $this->model = new NominationModel($dbConnection);

        $this->error = "";
        $this->success = "";
    }


    // Process form request
    public function processRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $form_type = $_POST["form_type"] ?? "";


            switch ($form_type) {

                case "create":

                    $this->handleCreate();

                    break;


                case "update":

                    $this->handleUpdate();

                    break;


                case "status":

                    $this->handleStatusUpdate();

                    break;


                case "delete":

                    $this->handleDelete();

                    break;


                default:

                    $this->error =
                        "Invalid form submission.";

            }

        }


        if (isset($_GET["error"])) {

            $this->error =
                $_GET["error"];

        }


        if (isset($_GET["success"])) {

            $this->success =
                $_GET["success"];

        }
    }


    // Create nomination
    private function handleCreate()
    {
        $application_id =
            trim($_POST["application_id"] ?? "");

        $university_id =
            trim($_POST["university_id"] ?? "");

        $nomination_date =
            $_POST["nomination_date"] ?? "";

        $nomination_status =
            $_POST["nomination_status"] ?? "";


        // Check empty fields
        if (
            empty($application_id) ||
            empty($university_id) ||
            empty($nomination_date) ||
            empty($nomination_status)
        ) {

            $this->error =
                "All fields are required.";

            return;

        }


        // Check IDs
        if (
            !is_numeric($application_id) ||
            !is_numeric($university_id)
        ) {

            $this->error =
                "IDs must contain numbers only.";

            return;

        }


        $data = [

            "application_id" =>
                $application_id,

            "university_id" =>
                $university_id,

            "nomination_date" =>
                $nomination_date,

            "nomination_status" =>
                $nomination_status

        ];


        if (
            $this->model->createNomination($data)
        ) {

            $this->success =
                "Nomination created successfully!";

        }

        else {

            $this->error =
                "Error creating nomination.";

        }
    }


    // Update nomination
    private function handleUpdate()
    {
        $nomination_id =
            trim($_POST["update_nomination_id"] ?? "");

        $application_id =
            trim($_POST["update_application_id"] ?? "");

        $university_id =
            trim($_POST["update_university_id"] ?? "");

        $nomination_date =
            $_POST["update_date"] ?? "";

        $nomination_status =
            $_POST["update_status"] ?? "";


        // Check empty fields
        if (
            empty($nomination_id) ||
            empty($application_id) ||
            empty($university_id) ||
            empty($nomination_date) ||
            empty($nomination_status)
        ) {

            $this->error =
                "All update fields are required.";

            return;

        }


        // Check IDs
        if (
            !is_numeric($nomination_id) ||
            !is_numeric($application_id) ||
            !is_numeric($university_id)
        ) {

            $this->error =
                "IDs must contain numbers only.";

            return;

        }


        $data = [

            "nomination_id" =>
                $nomination_id,

            "application_id" =>
                $application_id,

            "university_id" =>
                $university_id,

            "nomination_date" =>
                $nomination_date,

            "nomination_status" =>
                $nomination_status

        ];


        if (
            $this->model->updateNomination($data)
        ) {

            $this->success =
                "Nomination updated successfully.";

        }

        else {

            $this->error =
                "Error updating nomination.";

        }
    }


    // Update nomination status
    private function handleStatusUpdate()
    {
        $status_nomination_id =
            trim($_POST["status_nomination_id"] ?? "");

        $current_status =
            $_POST["current_status"] ?? "";

        $new_status =
            $_POST["new_status"] ?? "";


        // Check empty fields
        if (
            empty($status_nomination_id) ||
            empty($current_status) ||
            empty($new_status)
        ) {

            $this->error =
                "All status fields are required.";

            return;

        }


        // Check nomination ID
        if (
            !is_numeric($status_nomination_id)
        ) {

            $this->error =
                "Nomination ID must contain numbers only.";

            return;

        }


        // Check same status
        if (
            $current_status == $new_status
        ) {

            $this->error =
                "New status must be different from current status.";

            return;

        }


        if (
            $this->model->updateStatus(
                $status_nomination_id,
                $new_status
            )
        ) {

            $this->success =
                "Nomination status updated successfully.";

        }

        else {

            $this->error =
                "Error updating status.";

        }
    }


    // Delete nomination
    private function handleDelete()
    {
        $nomination_id =
            (int)($_POST["delete_nomination_id"] ?? 0);


        if ($nomination_id <= 0) {

            $this->error =
                "Invalid nomination ID.";

            return;

        }


        if (
            $this->model->deleteNomination(
                $nomination_id
            )
        ) {

            $this->success =
                "Nomination deleted successfully.";

        }

        else {

            $this->error =
                "Error deleting nomination.";

        }
    }


    // Get data for view
    public function getViewData()
    {
        return [

            "error" =>
                $this->error,

            "success" =>
                $this->success,

            "nominations" =>
                $this->model->getAllNominations(),

            "nominationCount" =>
                $this->model->getNominationCount(),

            "approvedApplications" =>
                $this->model->getApprovedApplications(),

            "universitiesList" =>
                $this->model->getAllUniversities()

        ];
    }


    // Get one nomination for AJAX
    public function getNominationData($nominationId)
    {
        $data =
            $this->model->getNominationById(
                $nominationId
            );


        if ($data) {

            return [

                "success" => true,

                "data" => $data

            ];

        }


        return [

            "success" => false,

            "message" =>
                "Nomination not found."

        ];
    }
}

?>