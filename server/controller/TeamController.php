<?php

namespace app\controller;

use app\services\TeamService;

class TeamController
{

    private $teamService;

    function __construct()
    {
        $this->teamService = new TeamService();
    }

    function addTeam(array $payload)
    {
        return $this->teamService->setTeam($payload);
    }

    function addTr(array $payload)
    {
        return $this->teamService->setTr($payload);
    }

    function addDoctorRecord(array $payload)
    {
        return $this->teamService->setDoctorRecord($payload);
    }

    function fetchDoctorsVisit(int $patientId)
    {
        return $this->teamService->getDoctorsVisit($patientId);
    }

    function fetchTeams()
    {
        return $this->teamService->getTeams();
    }


    function getTeamCounts()
    {
        return $this->teamService->getTotalTeam();
    }

    function getDoctorVisitCount(int $id)
    {
        return $this->teamService->getVisitCount($id);
    }
}
