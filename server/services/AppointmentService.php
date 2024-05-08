<?php

namespace app\services;

use app\config\MysqlDBH;
use app\config\PasswordConfig;
use app\dto\ResponseDto;
use app\model\Appointment;
use app\model\Patient;
use app\model\Staff;
use app\utils\Helper as UtilsHelper;
use app\utils\PasswordEncoder;
use Helper;

class AppointmentService
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Appointment($mysqlConnector);
    }

    function setAppointment(array $data): string
    {
        $response = $this->model->createAppointment($data);
        if (is_bool($response)) {
            if ($response) {
                // send in email
                return ResponseDto::json("Appointment has been registered and scheduled. See you soon.", 201);
            }
            return ResponseDto::json("An error was encountered while trying to schedule appointment!", 500);
        }
        return ResponseDto::json("This appointment has already been scheduled. See you soon!", 422);
    }

    function getAppointments(): string
    {
        $response = $this->model->fetchAppointments();
        return ResponseDto::json($response);
    }

    function getAppointmentsByTeam(int $id): string
    {
        $response = $this->model->fetchAppointmentByTeam($id);
        return ResponseDto::json($response);
    }

    function getAppointmentsBySlug(string $slug): string
    {
        $response = $this->model->fetchAppointmentBySlug($slug);
        return ResponseDto::json($response);
    }

    function removeAppointmentsBySlug(string $slug): string
    {
        $response = $this->model->deleteAppointmentBySlug($slug);
        if ($response) {
            return ResponseDto::json("Appointment has been deleted successful.", 200);
        }
        return ResponseDto::json("An error was encountered while trying to delete appointment.", 200);
    }

    function editAppointmentStatus(string $slug, string $status): string
    {
        $response = $this->model->updateAppointmentStatus($status, $slug);
        if ($response) {
            if ($status == 1) {
                return ResponseDto::json("Appointment has been marked present!", 200);
            } else if ($status == 2) {
                return ResponseDto::json("Appointment has been marked completed!", 200);
            } else {
                return ResponseDto::json("Appointment update was successful.", 200);
            }
        }
        return ResponseDto::json("We are unable to update contact setting at this point. Please try again!");
    }



    function getTotalAppointment(): int
    {
        $response = $this->model->fetchAppointments();
        return count($response);
    }
}
