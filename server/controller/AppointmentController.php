<?php

namespace app\controller;

use app\services\AppointmentService;

class AppointmentController
{

    private $userService;

    function __construct()
    {
        $this->userService = new AppointmentService();
    }

    function addAppointment(array $payload)
    {
        return $this->userService->setAppointment($payload);
    }

    function fetchAllAppointment()
    {
        return $this->userService->getAppointments();
    }

    function fetchAppointmentByTeam(int $id)
    {
        return $this->userService->getAppointmentsByTeam($id);
    }

    function fetchAppointmentBySlug(string $slug)
    {
        return $this->userService->getAppointmentsBySlug($slug);
    }

    function deleteAppointmentBySlug(string $slug)
    {
        return $this->userService->removeAppointmentsBySlug($slug);
    }

    function modifyAppointmentStatus(string $slug, string $status)
    {
        return $this->userService->editAppointmentStatus($slug, $status);
    }


    function getAppointmentCounts()
    {
        return $this->userService->getTotalAppointment();
    }
}
