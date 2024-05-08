<?php

namespace app\controller;

use app\services\PatientService;
use app\services\StaffService;

class PatientController
{

    private $userService;

    function __construct()
    {
        $this->userService = new PatientService();
    }

    function addPatient(array $payload)
    {
        return $this->userService->setPatient($payload);
    }

    function fetchAllPatient()
    {
        return $this->userService->getPatients();
    }

    function fetchPatientByWard(int $id)
    {
        return $this->userService->getPatientsByWard($id);
    }

    function fetchPatientByTeam(int $id)
    {
        return $this->userService->getPatientsByTeam($id);
    }

    function modifyPatientStatus(string $no, string $status)
    {
        return $this->userService->editPatientStatus($no, $status);
    }

    function transferPatient(array $payload, bool $isTeam)
    {
        return $this->userService->makePatientTransfer($payload, $isTeam);
    }

    function fetchPatientByAssignedDoctor(int $id)
    {
        return $this->userService->getPatientsByAssignedDoctor($id);
    }

    function deletePatientBySlug(string $slug)
    {
        return $this->userService->removePatient($slug);
    }


    function fetchPatientByNumber(string $number)
    {
        return $this->userService->getPatientsByNumber($number);
    }


    function getPatientCounts()
    {
        return $this->userService->getTotalPatient();
    }
}
