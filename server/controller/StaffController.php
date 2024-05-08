<?php

namespace app\controller;

use app\services\StaffService;

class StaffController
{

    private $userService;

    function __construct()
    {
        $this->userService = new StaffService();
    }

    function addStaff(array $payload)
    {
        return $this->userService->setStaff($payload);
    }

    function fetchAllStaff()
    {
        return $this->userService->getStaffs();
    }

    function fetchStaffByTeam(int $id)
    {
        return $this->userService->getStaffByTeam($id);
    }

    function staffLoginAuth(string $number, string $password)
    {
        return $this->userService->staffAuth($number, $password);
    }

    function getStaffCounts()
    {
        return $this->userService->getTotalStaffs();
    }

    function getDoctorsCounts()
    {
        return $this->userService->getTotalDoctors();
    }
}
