<?php

require_once __DIR__ . "/../models/ApplicationStatusModel.php";


class ApplicationStatusController
{
    private $model;
    private $studentId;


    public function __construct($dbConnection, $studentId)
    {
        $this->model = new ApplicationStatusModel($dbConnection);
        $this->studentId = (int)$studentId;
    }


    // Get all applications for dropdown
    public function getApplications()
    {
        return $this->model->getStudentApplications($this->studentId);
    }


    // Get complete selected application data
    public function getApplicationStatus($applicationId)
    {
        $applicationId = (int)$applicationId;

        if ($applicationId <= 0) {
            return array(
                "success" => false,
                "message" => "Invalid application ID."
            );
        }


        $application = $this->model->getApplicationDetails(
            $applicationId,
            $this->studentId
        );


        if (!$application) {
            return array(
                "success" => false,
                "message" => "Application not found."
            );
        }


        $nomination = $this->model->getNominationDetails($applicationId);


        return array(
            "success" => true,
            "application" => $application,
            "nomination" => $nomination
        );
    }
}

?>