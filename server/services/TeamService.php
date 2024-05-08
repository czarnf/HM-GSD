<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Team;
use app\model\Tr;

class TeamService
{

    private $model;
    private $trmodel;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Team($mysqlConnector);
        $this->trmodel = new Tr($mysqlConnector);
    }

    function setTeam(array $data): string
    {
        $response = $this->model->createTeam($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json(" New team has been created successfully.", 201);
            }
            return ResponseDto::json("An error was encountered while trying to create new team!", 500);
        }
        return ResponseDto::json("This team name already exist in our system!", 422);
    }

    function setTr(array $data): string
    {
        $response = $this->trmodel->createTr($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json(" Transaction update was successful.", 201);
            }
        }
        return ResponseDto::json("An error was encountered while trying to process this transaction!", 500);
    }


    function setDoctorRecord(array $data): string
    {
        $response = $this->trmodel->createRecord($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json(" Doctor record has been saved successfully.", 201);
            }
        }
        return ResponseDto::json("An error was encountered while trying to record visit!", 500);
    }

    function getVisitCount(int $id): int
    {
        $response = $this->trmodel->fetchTotalVisit($id);
        return $response;
    }

    function getDoctorsVisit(int $patientId): string
    {
        $response = $this->trmodel->fetchDoctorVisits($patientId);
        return ResponseDto::json($response);
    }

    function getTeams(): string
    {
        $response = $this->model->fetchTeams();
        return ResponseDto::json($response);
    }


    function getTotalTeam(): int
    {
        $response = $this->model->fetchTeams();
        return count($response);
    }
}
