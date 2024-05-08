<?php

namespace app\services;

use app\config\MysqlDBH;
use app\config\PasswordConfig;
use app\dto\ResponseDto;
use app\model\Patient;
use app\model\Staff;
use app\utils\Helper as UtilsHelper;
use app\utils\PasswordEncoder;
use Helper;

class PatientService
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Patient($mysqlConnector);
    }

    function setPatient(array $data): string
    {
        $response = $this->model->createPatient($data);
        if (is_bool($response)) {
            if ($response) {
                // send in email
                return ResponseDto::json(" New patient admission was successful.", 201);
            }
            return ResponseDto::json("An error was encountered while trying to admit patient!", 500);
        }
        return ResponseDto::json("This patient detail already exist in our system!", 422);
    }

    function getPatients(): string
    {
        $response = $this->model->fetchPatients();
        return ResponseDto::json($response);
    }

    function getPatientsByWard(int $id): string
    {
        $response = $this->model->fetchPatientByWard($id);
        return ResponseDto::json($response);
    }

    function getPatientsByTeam(int $id): string
    {
        $response = $this->model->fetchPatientByTeam($id);
        return ResponseDto::json($response);
    }

    function getPatientsByAssignedDoctor(int $id): string
    {
        $response = $this->model->fetchPatientByAssignedDoctor($id);
        return ResponseDto::json($response, 200);
    }


    function getPatientsByNumber(string $number): string
    {
        $response = $this->model->fetchPatientByNumber($number);
        return ResponseDto::json($response);
    }

    function editPatientStatus(string $no, string $status): string
    {
        $response = $this->model->updatePatientStatus($status, $no);
        if ($response) {
            if ($status == 1) {
                return ResponseDto::json("Patient has been marked for discharged!", 200);
            } else {
                return ResponseDto::json("Patient update was successful.", 200);
            }
        }
        return ResponseDto::json("We are unable to update patient details at this point. Please try again!");
    }

    function makePatientTransfer(array $payload, bool $isTeam): string
    {
        $response = $this->model->transferPatient($payload, $isTeam);
        if ($response) {
            return ResponseDto::json("Patient transfer was successful!", 200);
        }
        return ResponseDto::json("We are unable to make patient transfer at this point. Please try again!");
    }

    function removePatient(string $slug): string
    {
        $response = $this->model->deletePatient($slug);
        if ($response) {
            return ResponseDto::json("Patient has been deleted successful.", 200);
        }
        return ResponseDto::json("An error was encountered while trying to delete patient.", 200);
    }



    function getTotalPatient(): int
    {
        $response = $this->model->fetchPatients();
        return count($response);
    }
}
